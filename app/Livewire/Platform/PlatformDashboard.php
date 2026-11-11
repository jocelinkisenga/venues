<?php

namespace App\Livewire\Platform;

use App\Models\Organization;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PlatformDashboard extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::user()->is_super_admin, 403);
    }

    public function toggleSuspension(int $venueId): void
    {
        abort_unless(Auth::user()->is_super_admin, 403);

        $venue = Venue::findOrFail($venueId);
        $venue->update(['suspended_at' => $venue->isSuspended() ? null : now()]);
    }

    public function render()
    {
        // Chiffre d'affaires net des 30 derniers jours, par salle, dans la devise de la salle
        $revenue = Payment::join('bookings', 'bookings.id', '=', 'payments.booking_id')
            ->where('payments.status', 'succeeded')
            ->where('payments.paid_at', '>=', now()->subDays(30))
            ->selectRaw('bookings.venue_id, SUM(payments.amount) as total')
            ->groupBy('bookings.venue_id')
            ->pluck('total', 'venue_id');

        $rows = Venue::with('organization.owner')
            ->withCount('bookings')
            ->orderBy('name')
            ->get()
            ->map(fn (Venue $venue) => [
                'venue' => $venue,
                'revenue' => (float) ($revenue[$venue->id] ?? 0),
            ]);

        return view('livewire.platform.platform-dashboard', [
            'rows' => $rows,
            'stats' => [
                'organizations' => Organization::count(),
                'venues' => Venue::count(),
                'active' => Venue::whereNull('suspended_at')->count(),
                'users' => User::count(),
            ],
        ])->layout('layouts.app');
    }
}