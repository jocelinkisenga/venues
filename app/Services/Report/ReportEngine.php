<?php

namespace App\Services\Report;

use App\Models\AiReport;

class ReportEngine
{
    public function __construct(
        private ReportDataBuilder $builder,
        private ReportRenderer $renderer,
        private PdfExportService $pdf
    ) {}

    public function generate(AiReport $report): string
    {
        $data = $this->builder->build($report);

        $html = $this->renderer->render($data);

        $pdfPath = $this->pdf->generate(
            $html,
            "report_{$report->id}.pdf"
        );

        $report->update([
            'pdf_path' => $pdfPath
        ]);

        return $pdfPath;
    }
}