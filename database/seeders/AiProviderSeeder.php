<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name' => 'Google Gemini',
                'slug' => 'gemini',
                'driver' => 'gemini',
            ],
            [
                'name' => 'Anthropic',
                'slug' => 'anthropic',
                'driver' => 'anthropic',
            ],
            [
                'name' => 'OpenAI',
                'slug' => 'openai',
                'driver' => 'openai',
            ],
            [
                'name' => 'DeepSeek',
                'slug' => 'deepseek',
                'driver' => 'deepseek',
            ],
            [
                'name' => 'Z.AI',
                'slug' => 'zai',
                'driver' => 'zai',
            ],
        ];

        foreach ($providers as $provider) {
            AiProvider::updateOrCreate(
                ['slug' => $provider['slug']],
                $provider
            );
        }
    }
}