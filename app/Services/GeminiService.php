<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * @param string $prompt
     * @param callable $onChunk (callback pour le streaming)
     */
    // Remplacez votre fonction streamGenerateContent par celle-ci :
public function streamGenerateContent(string $prompt, callable $onChunk): void
{
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:streamGenerateContent?key={$this->apiKey}";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // Important pour le stream
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['contents' => [['parts' => [['text' => $prompt]]]]]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    // La fonction callback appelée par curl pour chaque ligne
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($onChunk) {
        if (str_starts_with($data, 'data: ')) {
            $json = json_decode(substr($data, 6), true);
            if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                $onChunk($json['candidates'][0]['content']['parts'][0]['text']);
            }
        }
        return strlen($data);
    });

    curl_exec($ch);
    curl_close($ch);
}

    }

