<?php

namespace App\Livewire\Finance;

use App\Services\ProfitabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profitability extends Component
{
    public string $from = '';
    public string $to = '';

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->to = now()->endOfMonth()->toDateString();
    }

    public function render(ProfitabilityService $service)
    {
        $venue = Auth::user()->venue;

        $report = $service->period($venue, Carbon::parse($this->from), Carbon::parse($this->to));

        return view('livewire.finance.profitability', [
            'report' => $report,
            'venue' => $venue,
        ])->layout('layouts.app');
    }
}