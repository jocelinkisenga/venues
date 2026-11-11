<?php

namespace App\Livewire\Crm;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LeadPipeline extends Component
{
    public string $full_name = '';
    public string $phone = '';
    public string $event_type = 'Mariage';
    public ?string $expected_guests = null;
    public ?string $event_date = null;
    public ?string $source = 'WhatsApp';

    public function create(): void
    {
        $user = Auth::user();
        abort_unless($user->role->canSell(), 403);

        $this->expected_guests = $this->expected_guests ?: null;
        $this->event_date = $this->event_date ?: null;

        $data = $this->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'event_type' => ['required', 'string', 'max:60'],
            'expected_guests' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'event_date' => ['nullable', 'date'],
            'source' => ['nullable', 'string', 'max:60'],
        ]);

        Lead::create([
            'venue_id' => $user->venue_id,
            'assigned_to' => $user->id,
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'event_type' => $data['event_type'],
            'expected_guests' => $data['expected_guests'],
            'event_date' => $data['event_date'],
            'source' => $data['source'],
            'status' => LeadStatus::New,
        ]);

        $this->reset('full_name', 'phone', 'expected_guests', 'event_date');
    }

    public function move(int $leadId, string $status): void
    {
        abort_unless(Auth::user()->role->canSell(), 403);
        abort_unless(in_array($status, array_column(LeadStatus::cases(), 'value'), true), 422);

        Lead::where('venue_id', Auth::user()->venue_id)->findOrFail($leadId)->update(['status' => $status]);
    }

    public function render()
    {
        $leads = Lead::where('venue_id', Auth::user()->venue_id)
            ->latest()
            ->get()
            ->groupBy(fn (Lead $lead) => $lead->status->value);

        return view('livewire.crm.lead-pipeline', [
            'columns' => LeadStatus::cases(),
            'leads' => $leads,
            'eventTypes' => \App\Models\EventType::where('venue_id', Auth::user()->venue_id)->orderBy('name')->pluck('name'),
        ])->layout('layouts.app');
    }
}
