<?php

namespace App\Services\Report;

class ReportRenderer
{
    public function render(array $data): string
    {
        return view('pages.reports.pdf', [
            'report' => $data
        ])->render();
    }
}