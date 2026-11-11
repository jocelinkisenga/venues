<?php

namespace App\Services\AI;

use App\Models\AiModel;
use App\Models\AiSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\ValueObjects\ProviderRateLimit;
use Illuminate\Support\Arr;
use Throwable;

class AiModelManager
{
    public function generate(
        array $messages,
        string $systemPrompt,
        array $options = []
    ): AiGenerationResult {
        $settings = AiSetting::current();

        $models = $this->resolveModels($settings);

        if ($models->isEmpty()) {
            throw new \RuntimeException(
                'No model configured.'
            );
        }

        $lastException = null;

        foreach ($models as $index => $model) {
            try {
                return $this->execute(
                    model: $model,
                    messages: $messages,
                    systemPrompt: $systemPrompt,
                    maxRetries: $settings->max_retries,
                    options: $options,
                    fallbackUsed: $index > 0
                );

            } catch (Throwable $e) {
                $lastException = $e;

                Log::warning('AI model failed', [
                    'provider' => $model->provider->slug,
                    'model' => $model->model_identifier,
                    'fallback_index' => $index,
                    'exception' => $e::class,
                    'message' => $e->getMessage(),
                ]);

                if (
                    ! $settings->automatic_failover ||
                    ! $this->shouldFailover($e)
                ) {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException(
            'All ai models not working.',
            previous: $lastException
        );
    }

    protected function resolveModels(AiSetting $settings): Collection
    {
        $ids = [];

        if ($settings->primary_model_id) {
            $ids[] = $settings->primary_model_id;
        }

        if ($settings->fallback_enabled) {
            $ids = array_merge(
                $ids,
                $settings->fallback_model_ids ?? []
            );
        }

        return AiModel::query()
            ->with('provider')
            ->whereIn('id', array_unique($ids))
            ->where('is_enabled', true)
            ->whereHas('provider', function ($query) {
                $query->where('is_enabled', true);
            })
            ->get()
            ->sortBy(function (AiModel $model) use ($ids) {
                return array_search($model->id, $ids);
            })
            ->values();
    }

    protected function execute(
        AiModel $model,
        array $messages,
        string $systemPrompt,
        int $maxRetries,
        array $options,
        bool $fallbackUsed
    ): AiGenerationResult {
        $request = Prism::text()
            ->using(
                $model->provider->driver,
                $model->model_identifier
            )
            ->withSystemPrompt($systemPrompt)
            ->withMessages($messages)
            ->withClientRetry($maxRetries, 100);

        if (isset($options['timeout'])) {
            $request = $request->withClientOptions([
                'timeout' => $options['timeout'],
            ]);
        }

        $response = $request->generate();

        return new AiGenerationResult(
            text: trim($response->text ?? ''),
            usage: $response->usage,
            provider: $model->provider->slug,
            model: $model->model_identifier,
            modelId: $model->id,
            fallbackUsed: $fallbackUsed,
        );
    }

    protected function shouldFailover(Throwable $e): bool
    {
        if ($e instanceof PrismRateLimitedException) {
            return true;
        }

        $message = strtolower($e->getMessage());

        foreach ([
            'timeout',
            'timed out',
            'rate limit',
            'too many requests',
            'temporarily unavailable',
            'service unavailable',
            'bad gateway',
            'gateway timeout',
            '502',
            '503',
            '504',
        ] as $term) {
            if (str_contains($message, $term)) {
                return true;
            }
        }

        return false;
    }
}