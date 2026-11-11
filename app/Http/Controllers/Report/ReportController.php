<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiReport;


class ReportController extends Controller
{


  public function show($reportId)
  {
    $report = AiReport::findOrFail($reportId);
    return view("client.reports.report-show", compact("report"));
  }
}
