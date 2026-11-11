<?php

namespace App\Services\Report;

use App\Models\AiReport;

class ReportDataBuilder
{
    public function build(AiReport $report): array
    {
        return [
            'id' => $report->id,
            'title' => $report->title,
            'type' => $report->type,
            'status' => $report->status,

            'user' => [
                'id' => $report->user_id,
            ],

            'summary' => $this->cleanMarkdown($report->summary),
            'content' => $this->cleanMarkdown($report->content),

            'meta' => $report->meta ?? [],

            'confidence' => $report->confidence_score ?? null,

            'has_pdf' => !empty($report->pdf_path),

            'generated_at' => $report->created_at?->toDateTimeString(),
        ];
    }

    /**
     * IMPORTANT: supprime *, #, ##, etc.
     */
    private function cleanMarkdown(?string $text): string
    {
        if (!$text) return '';

        // basic markdown cleanup (safe for PDF rendering)
        $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/', '$1', $text);
        $text = preg_replace('/#+\s/', '', $text);

        return trim($text);
    }
}