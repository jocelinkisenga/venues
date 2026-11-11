<?php

namespace App\Services\Report;

use Barryvdh\Snappy\Facades\SnappyPdf;

class PdfExportService
{
    public function generate(string $html, string $filename = null): string
    {
        $filename = $filename ?? 'report_' . time() . '.pdf';

        $pdf = SnappyPdf::loadHTML($html)
            ->setPaper('letter') // 🇺🇸 US STANDARD
            ->setOption('margin-top', 12)
            ->setOption('margin-bottom', 12)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10)
            ->setOption('encoding', 'utf-8');

        $path = storage_path("app/reports/{$filename}");
        $pdf->save($path);

        return $path;
    }
}