<?php

namespace App\Livewire\Bookings;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Lead;
use App\Models\Package;
use App\Models\Space;
use App\Services\AvailabilityService;
use App\Services\QuoteCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class BookingForm extends Component
{
    public ?int $space_id = null;
    public ?int $package_id = null;
    public ?int $lead_id = null;
    public string $event_type = 'Mariage';
    public string $client_name = '';
    public string $client_phone = '';
    public int $guests_count = 100;
    public string $starts_at = '';
    public string $ends_at = '';
    public bool $option = true;
    public string $notes = '';

    public function mount(): void
    {
        // Par défaut : soirée de 16h00 jusqu'à 6h00 le lendemain (forfait nuit)
        $this->starts_at = now()->addDays(7)->setTime(16, 0)->format('Y-m-d\TH:i');
        $this->ends_at = now()->addDays(8)->setTime(6, 0)->format('Y-m-d\TH:i');
    }

    public function save(AvailabilityService $availability, QuoteCalculator $calculator)
    {
        $user = Auth::user();
        $request = (new StoreBookingRequest)->setUserResolver(fn () => $user);
        $data = $this->validate($request->rules());

        $space = Space::where('venue_id', $user->venue_id)->findOrFail($data['space_id']);

        if ($data['guests_count'] > $space->capacity) {
            $this->addError('guests_count', "Capacité maximale de cet espace : {$space->capacity} personnes.");

            return;
        }

        $start = Carbon::parse($data['starts_at']);
        $end = Carbon::parse($data['ends_at']);

        try {
            $availability->assertAvailable($space, $start, $end);
        } catch (ValidationException $e) {
            $this->addError('starts_at', $e->errors()['starts_at'][0]);

            return;
        }

        $booking = Booking::create([
            'venue_id' => $user->venue_id,
            'space_id' => $space->id,
            'package_id' => $data['package_id'] ?? null,
            'lead_id' => $data['lead_id'] ?? null,
            'created_by' => $user->id,
            'reference' => Booking::generateReference(),
            'event_type' => $data['event_type'],
            'client_name' => $data['client_name'],
            'client_phone' => $data['client_phone'],
            'guests_count' => $data['guests_count'],
            'starts_at' => $start,
            'ends_at' => $end,
            'status' => $data['option'] ? BookingStatus::Option : BookingStatus::Confirmed,
            'option_expires_at' => $data['option'] ? now()->addHours(config('venue.option_hold_hours')) : null,
            'notes' => $data['notes'] ?? null,
        ]);

        $calculator->createForBooking($booking);

        return $this->redirect(route('bookings.quote', $booking), navigate: true);
    }

    public function render()
    {
        $venueId = Auth::user()->venue_id;

        return view('livewire.bookings.booking-form', [
            'spaces' => Space::where('venue_id', $venueId)->orderBy('name')->get(),
            'packages' => Package::where('venue_id', $venueId)->orderBy('name')->get(),
            'leads' => Lead::where('venue_id', $venueId)->whereIn('status', ['visit_planned', 'quote_sent'])->latest()->get(),
            'eventTypes' => config('venue.event_types'),
        ])->layout('layouts.app');
    }
}
