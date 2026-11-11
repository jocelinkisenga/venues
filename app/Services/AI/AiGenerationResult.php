<?php

namespace App\Services\AI;

use Prism\Prism\ValueObjects\Usage;

class AiGenerationResult
{
    public function __construct(
        public readonly string $text,
        public readonly ?Usage $usage,
        public readonly string $provider,
        public readonly string $model,
        public readonly int $modelId,
        public readonly bool $fallbackUsed,
    ) {}
}