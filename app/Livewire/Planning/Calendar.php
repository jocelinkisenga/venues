<?php

namespace App\Livewire\Planning;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Space;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Calendar extends Component
{
    public string $month;          // format Y-m
    public ?int $spaceId = null;

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function previous(): void
    {
        $this->month = Carbon::createFromFormat('Y-m-d', $this->month.'-01')->subMonth()->format('Y-m');
    }

    public function next(): void
    {
        $this->month = Carbon::createFromFormat('Y-m-d', $this->month.'-01')->addMonth()->format('Y-m');
    }

    /** Déplacement par glisser-déposer : conserve la durée et l'heure de début. */
    public function moveBooking(int $bookingId, string $date, AvailabilityService $availability): void
    {
        $user = Auth::user();
        abort_unless($user->role->canSell(), 403);

        $booking = Booking::where('venue_id', $user->venue_id)->with('space')->findOrFail($bookingId);

        if (in_array($booking->status, [BookingStatus::InProgress, BookingStatus::Cleaning, BookingStatus::Finished, BookingStatus::Cancelled], true)) {
            $this->addError('calendar', 'Cette réservation ne peut plus être déplacée.');

            return;
        }

        $newStart = Carbon::parse($date)->setTimeFrom($booking->starts_at);
        $newEnd = $newStart->copy()->addMinutes((int) $booking->starts_at->diffInMinutes($booking->ends_at));

        try {
            $availability->assertAvailable($booking->space, $newStart, $newEnd, $booking->id);
        } catch (ValidationException $e) {
            $this->addError('calendar', $e->errors()['starts_at'][0]);

            return;
        }

        $booking->update(['starts_at' => $newStart, 'ends_at' => $newEnd]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $venueId = Auth::user()->venue_id;
        $first = Carbon::createFromFormat('Y-m-d', $this->month.'-01');
        $gridStart = $first->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $first->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $bookings = Booking::where('venue_id', $venueId)
            ->where('status', '!=', BookingStatus::Cancelled->value)
            ->when($this->spaceId, fn ($q) => $q->where('space_id', $this->spaceId))
            ->where('starts_at', '<=', $gridEnd->copy()->endOfDay()->toDateTimeString())
            ->where('ends_at', '>=', $gridStart->toDateTimeString())
            ->with('space')
            ->orderBy('starts_at')
            ->get();

        $days = [];
        for ($day = $gridStart->copy(); $day->lte($gridEnd); $day->addDay()) {
            $key = $day->toDateString();
            $days[$key] = [
                'date' => $day->copy(),
                'inMonth' => $day->month === $first->month,
                'bookings' => $bookings->filter(
                    fn (Booking $b) => $b->starts_at->toDateString() <= $key && $b->ends_at->toDateString() >= $key
                )->values(),
            ];
        }

        return view('livewire.planning.calendar', [
            'days' => $days,
            'label' => $first->copy()->locale('fr')->translatedFormat('F Y'),
            'spaces' => Space::where('venue_id', $venueId)->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
