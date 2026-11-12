<?php

namespace App\Livewire\Public;

use App\Models\EventType;
use App\Models\Package;
use App\Models\Space;
use App\Models\Venue;
use App\Services\PublicBookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
// use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class VenueShow extends Component
{
    public const STEPS = [
        1 => 'Date et invités',
        2 => 'Espace et forfait',
        3 => 'Vos coordonnées',
    ];

    public Venue $venue;

    #[Url(as: 'date')]
    public string $eventDate = '';

    #[Url(as: 'guests')]
    public int $guests_count = 50;

    public string $preset = 'evening';
    public int $days = 1;
    public int $step = 1;

    public ?int $space_id = null;
    public ?int $package_id = null;
    public string $event_type = '';
    public string $starts_at = '';
    public string $ends_at = '';
    public string $client_name = '';
    public string $client_phone = '';
    public string $client_email = '';
    public string $message = '';

    // Champ piège, masqué aux personnes
    public string $website = '';

    public function mount(string $slug): void
    {
        $this->venue = Venue::listed()->where('slug', $slug)->firstOrFail();

        if (! $this->isValidFutureDate($this->eventDate)) {
            $this->eventDate = now()->addDays(7)->format('Y-m-d');
        }

        $this->guests_count = max(1, min(5000, $this->guests_count));
        $this->space_id = $this->venue->spaces()->active()->orderBy('name')->value('id');
        $this->event_type = EventType::where('venue_id', $this->venue->id)->active()->orderBy('name')->value('name') ?? '';

        $this->applyPreset('evening');
    }

    /* ---------- Réactions aux saisies ---------- */

    public function updatedEventDate(): void
    {
        $this->applyPreset($this->preset);
    }

    public function updatedDays(): void
    {
        if ($this->preset === 'multi') {
            $this->applyPreset('multi');
        }
    }

    public function updatedStartsAt(): void
    {
        $this->preset = 'custom';
    }

    public function updatedEndsAt(): void
    {
        $this->preset = 'custom';
    }

    public function applyPreset(string $preset): void
    {
        $this->preset = in_array($preset, ['evening', 'day', 'multi'], true) ? $preset : 'evening';
        $this->days = max(1, min(10, $this->days));

        $day = Carbon::parse($this->eventDate);

        [$start, $end] = match ($this->preset) {
            'day' => [$day->copy()->setTime(10, 0), $day->copy()->setTime(18, 0)],
            'multi' => [$day->copy()->setTime(9, 0), $day->copy()->addDays($this->days - 1)->setTime(23, 0)],
            default => [$day->copy()->setTime(16, 0), $day->copy()->addDay()->setTime(6, 0)],
        };

        $this->starts_at = $start->format('Y-m-d\TH:i');
        $this->ends_at = $end->format('Y-m-d\TH:i');
    }

    public function incrementGuests(): void
    {
        $this->guests_count = min(5000, $this->guests_count + 10);
    }

    public function decrementGuests(): void
    {
        $this->guests_count = max(1, $this->guests_count - 10);
    }

    /* ---------- Parcours en étapes ---------- */

    public function next(): void
    {
        $this->validate($this->rulesFor($this->step));
        $this->step = min(3, $this->step + 1);
    }

    public function back(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    /** On peut revenir à une étape précédente, mais pas sauter en avant. */
    public function goTo(int $step): void
    {
        if ($step < $this->step) {
            $this->step = $step;
        }
    }

    /** Le bouton principal de chaque étape : continuer, puis envoyer à la fin. */
    public function handleSubmit(PublicBookingService $service)
    {
        if ($this->step < 3) {
            $this->next();

            return;
        }

        return $this->submit($service);
    }

    public function submit(PublicBookingService $service)
    {
        if ($this->website !== '') {
            return;
        }

        $key = 'online-request:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('submit', 'Trop de demandes depuis votre connexion. Réessayez plus tard.');

            return;
        }

        $this->package_id = $this->package_id ?: null;

        $data = $this->validate(array_merge($this->rulesFor(1), $this->rulesFor(2), $this->rulesFor(3)));

        try {
            $booking = $service->request($this->venue, $data);
        } catch (\DomainException $e) {
            $this->addError('submit', $e->getMessage());
            $this->step = str_contains($e->getMessage(), 'Capacité') || str_contains($e->getMessage(), 'forfait') ? 2 : 1;

            return;
        }

        RateLimiter::hit($key, 3600);

        return $this->redirect(
            URL::temporarySignedRoute('public.thanks', now()->addDays(7), ['booking' => $booking->id]),
        );
    }

    /* ---------- Rendu ---------- */

    public function render()
    {
        $spaceId = $this->space_id ? (int) $this->space_id : null;

        $spaces = $this->venue->spaces()->active()->orderBy('name')->get();
        $selectedSpace = $spaces->firstWhere('id', $spaceId);

        $packages = $this->venue->packages()->active()->orderBy('name')->get()
            ->filter(fn (Package $p) => $p->isAvailableIn($spaceId))
            ->values();

        $selectedPackage = $packages->firstWhere('id', $this->package_id ? (int) $this->package_id : null);

        $start = $this->parseOrNull($this->starts_at);
        $end = $this->parseOrNull($this->ends_at);

        return view('livewire.public.venue-show', [
            'spaces' => $spaces,
            'packages' => $packages,
            'selectedSpace' => $selectedSpace,
            'selectedPackage' => $selectedPackage,
            'eventTypes' => EventType::where('venue_id', $this->venue->id)->active()->orderBy('name')->pluck('name'),
            'steps' => self::STEPS,
            'minNotice' => (int) config('venue.online_min_notice_hours'),
            'minPrice' => $this->venue->packages()->active()->min('base_price'),
            'capacityWarning' => $selectedSpace && $this->guests_count > $selectedSpace->capacity,
            'summary' => $this->summary($start, $end, $selectedPackage),
        ])->layout('components.public-layout', [
            'title' => $this->venue->name,
            'description' => Str::limit((string) $this->venue->description, 150),
        ]);
    }

    private function summary(?Carbon $start, ?Carbon $end, ?Package $package): array
    {
        $hours = ($start && $end && $end->gt($start)) ? (int) $start->diffInHours($end) : 0;

        $estimate = null;

        if ($package) {
            $overtime = max(0, $hours - $package->included_hours) * (float) $package->overtime_rate;
            $estimate = $this->venue->money((float) $package->base_price + $overtime);
        }

        return [
            'date' => $start?->copy()->locale('fr')->isoFormat('dddd D MMMM YYYY'),
            'time' => $start && $end
                ? $start->format('H:i').' → '.($start->isSameDay($end) ? $end->format('H:i') : $end->format('d/m H:i'))
                : null,
            'duration' => $hours > 0 ? $hours.' h' : null,
            'estimate' => $estimate,
        ];
    }

    /* ---------- Règles et utilitaires ---------- */

    private function rulesFor(int $step): array
    {
        $venueId = $this->venue->id;

        return match ($step) {
            1 => [
                'eventDate' => ['required', 'date'],
                'guests_count' => ['required', 'integer', 'min:1', 'max:5000'],
                'event_type' => ['required', 'string', 'max:60', Rule::exists('event_types', 'name')
                    ->where('venue_id', $venueId)->whereNull('archived_at')],
                'starts_at' => ['required', 'date'],
                'ends_at' => ['required', 'date', 'after:starts_at'],
            ],
            2 => [
                'space_id' => ['required', Rule::exists('spaces', 'id')
                    ->where('venue_id', $venueId)->whereNull('archived_at')],
                'package_id' => ['nullable', Rule::exists('packages', 'id')
                    ->where('venue_id', $venueId)->whereNull('archived_at')],
            ],
            default => [
                'client_name' => ['required', 'string', 'max:120'],
                'client_phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s]{8,20}$/'],
                'client_email' => ['nullable', 'email', 'max:255'],
                'message' => ['nullable', 'string', 'max:1000'],
            ],
        };
    }

    private function isValidFutureDate(string $value): bool
    {
        try {
            return $value !== '' && Carbon::parse($value)->endOfDay()->isFuture();
        } catch (\Throwable) {
            return false;
        }
    }

    private function parseOrNull(string $value): ?Carbon
    {
        try {
            return $value === '' ? null : Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}