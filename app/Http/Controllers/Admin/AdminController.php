<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;
use App\Services\StripeRevenueService;

class AdminController extends Controller
{
  protected $revenueService;

  public function __construct(StripeRevenueService $revenueService)
  {
    $this->revenueService = $revenueService;
  }

  public function index()
  {

    return view("admin.dashboard", [
      "revenueMtd" => $this->revenueService->getMonthToDateRevenue(),
      "revenueAllTime" => $this->revenueService->getAllTimeRevenue(),
    ]);
  }
}
