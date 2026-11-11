<?php

namespace App\Livewire\Bookings;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\EventType;
use App\Models\Lead;
use App\Models\Package;
use App\Models\Space;
use App\Services\AvailabilityService;
use App\Services\CatalogService;
use App\Services\QuoteCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class BookingForm extends Component
{
    public const ZONES = [
        'grande_salle' => 'Grande salle',
        'terrasse' => 'Terrasse',
        'vip' => 'Espace VIP',
        'bar' => 'Bar',
        'autre' => 'Autre',
    ];

    // Réservation
    public ?int $space_id = null;
    public ?int $package_id = null;
    public ?int $lead_id = null;
    public string $event_type = '';
    public string $client_name = '';
    public string $client_phone = '';
    public int $guests_count = 100;
    public string $starts_at = '';
    public string $ends_at = '';
    public bool $option = true;
    public string $notes = '';

    // Création inline : espace
    public bool $showSpaceForm = false;
    public string $newSpaceName = '';
    public int $newSpaceCapacity = 100;
    public string $newSpaceZone = 'grande_salle';

    // Création inline : type d'événement
    public bool $showEventTypeForm = false;
    public string $newEventTypeName = '';

    // Création inline : forfait
    public bool $showPackageForm = false;
    public string $newPackageName = '';
    public string $newPackageType = 'seche';
    public string $newPackageTimeType = 'soiree';
    public int $newPackageDays = 1;
    public int $newPackageIncludedHours = 8;
    public string $newPackageBasePrice = '';
    public string $newPackageOvertimeRate = '0';
    public array $newPackageSpaceIds = [];

    public function mount(): void
    {
        $this->authorizeSales();

        $venueId = Auth::user()->venue_id;
       $types = EventType::where('venue_id', $venueId)->active()->orderBy('name')->pluck('name');
        $this->event_type = $types->contains('Mariage') ? 'Mariage' : ($types->first() ?? '');

        $this->starts_at = now()->addDays(7)->setTime(16, 0)->format('Y-m-d\TH:i');
        $this->ends_at = now()->addDays(8)->setTime(6, 0)->format('Y-m-d\TH:i');
    }

    /** Un forfait lié à d'autres espaces n'est plus valide si l'espace change. */
    public function updatedSpaceId(): void
    {
        if (! $this->package_id) {
            return;
        }

        $package = Package::with('spaces:id')->find($this->package_id);

        if ($package && ! $package->isAvailableIn($this->spaceFilter())) {
            $this->package_id = null;
        }
    }

    /** Pré-remplit les dates selon le type de forfait choisi. L'utilisateur peut les ajuster. */
    public function updatedPackageId(): void
    {
        $package = $this->package_id
    ? Package::where('venue_id', Auth::user()->venue_id)->active()->find($this->package_id)
    : null;

        if (! $package) {
            return;
        }

        $start = Carbon::parse($this->starts_at ?: now());

        $this->ends_at = $package->time_type === 'multi_jours'
            ? $start->copy()->addDays(max(1, $package->days) - 1)->setTime(23, 0)->format('Y-m-d\TH:i')
            : $start->copy()->addDay()->setTime(6, 0)->format('Y-m-d\TH:i');
    }

    public function addSpace(CatalogService $catalog): void
    {
        $this->authorizeSales();

        $this->validate([
            'newSpaceName' => ['required', 'string', 'max:120'],
            'newSpaceCapacity' => ['required', 'integer', 'min:1', 'max:5000'],
            'newSpaceZone' => ['required', Rule::in(array_keys(self::ZONES))],
        ]);

        $space = $catalog->addSpace(Auth::user()->venue, $this->newSpaceName, $this->newSpaceCapacity, $this->newSpaceZone);

        $this->space_id = $space->id;
        $this->reset('newSpaceName');
        $this->newSpaceCapacity = 100;
        $this->showSpaceForm = false;
    }

    public function addEventType(CatalogService $catalog): void
    {
        $this->authorizeSales();

        $this->validate(['newEventTypeName' => ['required', 'string', 'max:80']]);

        try {
            $type = $catalog->addEventType(Auth::user()->venue, $this->newEventTypeName);
        } catch (\DomainException $e) {
            $this->addError('newEventTypeName', $e->getMessage());

            return;
        }

        $this->event_type = $type->name;
        $this->reset('newEventTypeName');
        $this->showEventTypeForm = false;
    }

    public function addPackage(CatalogService $catalog): void
    {
        $this->authorizeSales();
        $venueId = Auth::user()->venue_id;

        $data = $this->validate([
            'newPackageName' => ['required', 'string', 'max:120'],
            'newPackageType' => ['required', Rule::in(['seche', 'cle_en_main'])],
            'newPackageTimeType' => ['required', Rule::in(['soiree', 'multi_jours'])],
            'newPackageDays' => ['required', 'integer', 'min:1', 'max:30'],
            'newPackageIncludedHours' => ['required', 'integer', 'min:1', 'max:48'],
            'newPackageBasePrice' => ['required', 'numeric', 'min:0'],
            'newPackageOvertimeRate' => ['required', 'numeric', 'min:0'],
            'newPackageSpaceIds' => ['array'],
            'newPackageSpaceIds.*' => [Rule::exists('spaces', 'id')->where('venue_id', $venueId)],
        ]);

        $spaceIds = array_map('intval', $this->newPackageSpaceIds);

        $package = $catalog->addPackage(Auth::user()->venue, [
            'name' => $data['newPackageName'],
            'type' => $data['newPackageType'],
            'time_type' => $data['newPackageTimeType'],
            'days' => $data['newPackageDays'],
            'included_hours' => $data['newPackageIncludedHours'],
            'base_price' => $data['newPackageBasePrice'],
            'overtime_rate' => $data['newPackageOvertimeRate'],
        ], $spaceIds);

        $this->reset('newPackageName', 'newPackageBasePrice', 'newPackageSpaceIds');
        $this->newPackageOvertimeRate = '0';
        $this->showPackageForm = false;

        // Sélection automatique, sauf si le forfait ne s'applique pas à l'espace en cours
        if ($this->space_id && $spaceIds && ! in_array($this->space_id, $spaceIds, true)) {
            session()->flash('info', 'Forfait créé. Il n\'est pas proposé pour l\'espace sélectionné.');

            return;
        }

        $this->package_id = $package->id;
        $this->updatedPackageId();
    }

    public function save(AvailabilityService $availability, QuoteCalculator $calculator)
    {
        $this->authorizeSales();
        $user = Auth::user();

        $rules = (new StoreBookingRequest)->setUserResolver(fn () => $user)->rules();
$rules['event_type'] = [
    'required', 'string', 'max:60',
    Rule::exists('event_types', 'name')->where('venue_id', $user->venue_id)->whereNull('archived_at'),
];

$data = $this->validate($rules);

$space = Space::where('venue_id', $user->venue_id)->active()->findOrFail($data['space_id']);

        if ($data['guests_count'] > $space->capacity) {
            $this->addError('guests_count', "Capacité maximale de cet espace : {$space->capacity} personnes.");

            return;
        }

        // if (! empty($data['package_id'])) {
        //     $package = Package::with('spaces:id')->where('venue_id', $user->venue_id)->findOrFail($data['package_id']);

        //     if (! $package->isAvailableIn($space->id)) {
        //         $this->addError('package_id', "Ce forfait n'est pas proposé pour cet espace.");

        //         return;
        //     }
        // }
        if (! empty($data['package_id'])) {
    $package = Package::with('spaces:id')
        ->where('venue_id', $user->venue_id)
        ->active()
        ->findOrFail($data['package_id']);
    // ...
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
        $venue = Auth::user()->venue;
$spaces = Space::where('venue_id', $venue->id)->active()->orderBy('name')->get();

$packages = Package::with('spaces:id')
    ->where('venue_id', $venue->id)
    ->active()
    ->orderBy('name')
    ->get()
    ->filter(fn (Package $p) => $p->isAvailableIn($this->spaceFilter()))
    ->values();

        return view('livewire.bookings.booking-form', [
            'venue' => $venue,
            'spaces' => $spaces,
            'packages' => $packages,
            'selectedPackage' => $this->package_id ? Package::find($this->package_id) : null,
            'eventTypes' => EventType::where('venue_id', $venue->id)->orderBy('name')->pluck('name'),
            'leads' => Lead::where('venue_id', $venue->id)
                ->whereIn('status', ['visit_planned', 'quote_sent'])
                ->latest()->get(),
            'zones' => self::ZONES,
            'eventTypes' => EventType::where('venue_id', $venue->id)->active()->orderBy('name')->pluck('name')
        ])->layout('layouts.app');
    }

    private function spaceFilter(): ?int
    {
        return $this->space_id ? (int) $this->space_id : null;
    }

    private function authorizeSales(): void
    {
        abort_unless(Auth::user()->role->canSell(), 403);
    }
}