<?php

namespace App\Services;

use App\Actions\StoreAiLog;
use App\Services\AI\AiModelManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\ProviderRateLimit;

class ChatService
{
    public $aiModelManager;
    public function __construct(
    ) {
        $this->aiModelManager = new AiModelManager();
    }

    /**
     * Génère une réponse à partir d'un prompt système et du message utilisateur.
     * L'historique est optionnel : on ne garde que les $maxHistory derniers messages.
     *
     * @param array $messages Format : [['role' => 'user|assistant', 'content' => '...'], ...]
     */
    public function generateResponse(
        string $systemPrompt,
        string $userMessage,
        array $messages = [],
        ?int $chatId = null,
        int $maxHistory = 5
    ): string {
        $conversation = [];

        foreach (array_slice($messages, -$maxHistory) as $message) {
            if (empty($message['content'])) {
                continue;
            }

            $conversation[] = $message['role'] === 'assistant'
                ? new AssistantMessage($message['content'])
                : new UserMessage($message['content']);
        }

        $conversation[] = new UserMessage($userMessage);

        $result = $this->callModel($conversation, $systemPrompt);

        $text = trim($result->text);

        if ($text === '') {
            throw new \Exception('Le fournisseur IA a retourné une réponse vide.');
        }

        return $text;
    }

    /**
     * Génère 4 questions fréquentes pour une thématique donnée.
     * Méthode non critique : en cas d'échec, on renvoie simplement un tableau vide.
     */
    public function getSuggestedQuestions(?string $theme,  ?string $profile = null): array
    {

        $systemPrompt = 'you are an expert in a given subject. you respond only using json format, blank line before every table'
            . ' sentences only, no surounding tex and  markdown caracters.'.'Unstack my taxes is a tax education and advisory plateform by Ilands solutions LLC, helping Gig economy workers who whant to find deductions they are missing and understand quartely estimated taxes, american expats who need clear guidance'.'only tax deduction and advisory, delivered online, no preparation and file tax returns'.'pricing membership pro 9$, premium 39$';

        $prompt = "for a website wich the theme is based on  : \"{$theme}\", generate 4 questions, if you don't know something suggest contacting our team "
            . "short and pertinentes wich users asks frequently. "
            . 'Example  format : ["Question 1 ?", "Question 2 ?"]';

                   if ($profile) {
        $prompt .= "\nVisitor profile (tailor the questions to it):\n" . $profile;
    }

        try {
            $result = $this->callModel([new UserMessage($prompt)], $systemPrompt);
        } catch (\Throwable $e) {
            logger()->warning('Suggested questions generation failed', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }

        // Nettoyage d'un éventuel ```json ... ``` renvoyé par le modèle
        $cleaned = preg_replace('/```json\s*|\s*```/', '', $result->text);
        $questions = json_decode(trim($cleaned), true);

        if (! is_array($questions)) {
            return [];
        }

        return array_values(array_filter($questions, 'is_string'));
    }

    /**
     * Appel centralisé au modèle, avec gestion du rate limit (comme TaxAdvisoryService).
     */
    protected function callModel(array $conversation, string $systemPrompt)
    {
        try {
            return $this->aiModelManager->generate(
                messages: $conversation,
                systemPrompt: $systemPrompt,
                options: ['timeout' => 60]
            );
        } catch (PrismRateLimitedException $e) {
            $limit = Arr::first(
                $e->rateLimits,
                fn (ProviderRateLimit $r) => $r->remaining === 0
            );

            logger()->warning('Provider rate limit reached', [
                'limit' => $limit?->name,
                'reset_at' => $limit?->resetsAt,
            ]);

            throw new \Exception('The service is currently unavailable, please try again later.');
        }
    }
}