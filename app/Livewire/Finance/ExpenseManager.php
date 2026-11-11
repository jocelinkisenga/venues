<?php

namespace App\Livewire\Finance;

use App\Models\Booking;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExpenseManager extends Component
{
    public const CATEGORIES = [
        'electricite' => 'Électricité', 'carburant' => 'Carburant', 'nourriture' => 'Nourriture & boissons',
        'nettoyage' => 'Nettoyage', 'entretien' => 'Entretien & réparations', 'location' => 'Loyer & charges',
        'salaire' => 'Salaires', 'communication' => 'Communication', 'autre' => 'Autre',
    ];

    public string $category = 'electricite';
    public string $label = '';
    public string $supplier = '';
    public string $amount = '';
    public string $spent_on = '';
    public ?int $bookingId = null;
    public string $month = '';

    public function mount(): void
    {
        $this->spent_on = now()->toDateString();
        $this->month = now()->format('Y-m');
    }

    public function add(): void
    {
        abort_unless(Auth::user()->role->value === 'proprietaire', 403);

        $venueId = Auth::user()->venue_id;

        $data = $this->validate([
            'category' => ['required', Rule::in(array_keys(self::CATEGORIES))],
            'label' => ['required', 'string', 'max:150'],
            'supplier' => ['nullable', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:1'],
            'spent_on' => ['required', 'date'],
            'bookingId' => ['nullable', Rule::exists('bookings', 'id')->where('venue_id', $venueId)],
        ]);

        Expense::create([
            'venue_id' => $venueId,
            'booking_id' => $data['bookingId'] ?? null,
            'recorded_by' => Auth::id(),
            'category' => $data['category'],
            'label' => $data['label'],
            'supplier' => $data['supplier'] ?: null,
            'amount' => $data['amount'],
            'spent_on' => $data['spent_on'],
        ]);

        $this->reset('label', 'supplier', 'amount', 'bookingId');
    }

    public function delete(int $id): void
    {
        abort_unless(Auth::user()->role->value === 'proprietaire', 403);

        Expense::where('venue_id', Auth::user()->venue_id)->findOrFail($id)->delete();
    }

    public function render()
    {
        $venue = Auth::user()->venue;
        $start = Carbon::createFromFormat('Y-m-d', $this->month.'-01')->startOfMonth();

        $expenses = Expense::with('booking')
            ->where('venue_id', $venue->id)
            ->whereBetween('spent_on', [$start->toDateString(), $start->copy()->endOfMonth()->toDateString()])
            ->orderByDesc('spent_on')
            ->get();

        return view('livewire.finance.expense-manager', [
            'expenses' => $expenses,
            'total' => (float) $expenses->sum('amount'),
            'categories' => self::CATEGORIES,
            'bookings' => Booking::where('venue_id', $venue->id)->latest('id')->limit(50)->get(),
            'venue' => $venue,
        ])->layout('layouts.app');
    }
}