<?php

namespace App\Livewire\Public;

use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
 use App\Services\Ai\AiAssistant;
use Illuminate\Support\Facades\RateLimiter;


class VenueDirectory extends Component
{
    public const SORTS = [
        'recommended' => 'Recommandées',
        'price' => 'Prix croissant',
        'capacity' => 'Capacité',
    ];

#[Url(as: 'eq', history: true)]
public array $amenities = [];

public string $ask = '';


    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $city = '';

    #[Url(as: 'date', history: true)]
    public string $date = '';

    #[Url(as: 'guests', history: true)]
    public int $guests = 0;

    #[Url(as: 'tri', history: true)]
    public string $sort = 'recommended';

    public function incrementGuests(): void
    {
        $this->guests = min(5000, $this->guests + 10);
    }

    public function decrementGuests(): void
    {
        $this->guests = max(0, $this->guests - 10);
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'city', 'date', 'guests');
        $this->sort = 'recommended';
    }

    public function render()
    {
        $range = $this->dateRange();

        $venues = Venue::listed()
            ->with('photos')
            ->withMax(['spaces as max_capacity' => fn ($q) => $q->active()], 'capacity')
            ->withMin(['packages as min_price' => fn ($q) => $q->active()], 'base_price')
            ->when($this->search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('city', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%")))
            ->when($this->city !== '', fn ($q) => $q->where('city', $this->city))
            ->when($this->guests > 0 || $range, fn ($q) => $q->whereHas(
                'spaces', fn ($s) => $this->availableSpaces($s, $range)
            ))
            ->when($this->sort === 'price', fn ($q) => $q->orderByRaw('min_price IS NULL, min_price ASC'))
            ->when($this->sort === 'capacity', fn ($q) => $q->orderByDesc('max_capacity'))
            ->when(! empty($this->amenities), fn (Builder $q) => $this->applyAmenities($q))
            ->when(! in_array($this->sort, ['price', 'capacity'], true), fn ($q) => $q->orderBy('name'))
            ->get();

        $cities = Venue::listed()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('livewire.public.venue-directory', [
            'venues' => $venues,
            'cities' => $cities,
            'sorts' => self::SORTS,
            'linkQuery' => array_filter([
                'date' => $this->date ?: null,
                'guests' => $this->guests ?: null,
            ]),
            'dateLabel' => $range ? $range[0]->copy()->locale('fr')->isoFormat('dddd D MMMM') : null,
            'hasFilters' => $this->search !== '' || $this->city !== '' || $this->date !== '' || $this->guests > 0,
        ])->layout('components.public-layout', ['title' => 'Explorer les salles']);
    }

    private function dateRange(): ?array
    {
        if ($this->date === '') {
            return null;
        }

        try {
            $day = Carbon::parse($this->date);
        } catch (\Throwable) {
            return null;
        }

        return [$day->copy()->startOfDay(), $day->copy()->endOfDay()];
    }

    /** Un espace compte s'il est assez grand et libre pendant toute la journée demandée. */
    private function availableSpaces(Builder $spaces, ?array $range): void
    {
        $spaces->active();

        if ($this->guests > 0) {
            $spaces->where('capacity', '>=', $this->guests);
        }

        if ($range) {
            [$start, $end] = $range;

            $spaces->whereDoesntHave('bookings', fn (Builder $b) => $b->blocking()
                ->where('starts_at', '<=', $end->toDateTimeString())
                ->where('ends_at', '>=', $start->toDateTimeString()));
        }
    }



public function askAssistant(AiAssistant $ai)
{
    $this->validate(['ask' => ['required', 'string', 'min:3', 'max:400']]);

    $key = 'ai-search:'.(auth()->id() ?? request()->ip());

    if (RateLimiter::tooManyAttempts($key, config('ai.rate_limit_per_hour'))) {
        $this->addError('ask', 'Vous avez atteint la limite de recherches pour cette heure.');

        return;
    }

    RateLimiter::hit($key, 3600);

    try {
        $filters = $ai->searchFilters($this->ask, [
            'cities' => Venue::listed()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city')->all(),
            'amenities' => config('venue.amenities'),
        ]);
    } catch (\RuntimeException $e) {
        $this->addError('ask', $e->getMessage().' Utilisez les filtres ci-dessous.');

        return;
    }

    return $this->redirect(route('explorer', array_filter([
        'city' => $filters['city'],
        'date' => $filters['date'],
        'guests' => $filters['guests'] ?: null,
        'eq' => $filters['amenities'] ?: null,
    ])), navigate: true);
}

private function applyAmenities(Builder $query): void
{
    foreach (array_intersect($this->amenities, array_keys(config('venue.amenities'))) as $amenity) {
        $query->whereJsonContains('amenities', $amenity);
    }
}
}