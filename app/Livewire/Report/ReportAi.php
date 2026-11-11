<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AiReport;
use Livewire\Attributes\Layout;


#[Layout('layouts.client')]
class ReportAi extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public function render()
    {
        $reports = AiReport::query()
            ->where('user_id', auth()->user()->id)

            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                        ->orWhere('summary', 'like', "%{$this->search}%");
                });
            })

            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })

            ->latest()
            ->paginate(10);

        return view('livewire.report.report-ai', [
            'reports' => $reports
        ]);
    }
}