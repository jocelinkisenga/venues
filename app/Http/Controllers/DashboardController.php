<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboard): View
    {
        $user = $request->user();

        return view('dashboard', [
            'venue' => $user->venue,
            'canSell' => $user->role->canSell(),
            'data' => $dashboard->for($user->venue),
        ]);
    }
}