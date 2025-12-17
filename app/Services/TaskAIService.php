<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TaskAIService
{
    public function inferScoring(string $title, ?string $description = null): array
    {
        // Fallback if key is missing (VERY IMPORTANT for demo)
        if (! config('services.gemini.key')) {
            return $this->mock(false);
        }

        $prompt = $this->buildPrompt($title, $description);

        $response = Http::post(
            config('services.gemini.url').'?key='
            .config('services.gemini.key'),
            [
                'contents' => [[
                    'parts' => [['text' => $prompt]],
                ]],
                'generationConfig' => [
                    'temperature' => 0.2,
                ],
            ]
        );

        Log::info('AI Response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body(),
        ]);

        $text = data_get(
            $response->json(),
            'candidates.0.content.parts.0.text'
        );

        $parsed = $this->parseJson($text);

        return array_merge(
            $this->mock(true),
            $parsed,
        );
    }

    private function buildPrompt(string $title, ?string $description): string
    {
        return <<<PROMPT
            You are a task classification assistant.

            Allowed values:
            - urgency: low, medium, high
            - impact: low, medium, high
            - effort: low, medium, high

            Return ONLY valid JSON.
            No explanations.

            Task title: "{$title}"
            Task description: "{$description}"

            Infer urgency, impact, and effort.
            PROMPT;
    }

    private function parseJson(?string $text): array
    {
        if (! $text) {
            return $this->mock();
        }

        // Gemini may wrap JSON in ```json blocks
        $clean = trim(preg_replace('/```json|```/', '', $text));

        $decoded = json_decode($clean, true);

        if (! is_array($decoded)) {
            return $this->mock();
        }

        return array_intersect_key(
            $decoded,
            array_flip(['urgency', 'impact', 'effort'])
        );
    }

    private function mock(bool $aiUsed = false): array
    {
        return [
            'urgency' => 'medium',
            'impact' => 'medium',
            'effort' => 'medium',
            'ai_used' => $aiUsed,
        ];
    }
}
