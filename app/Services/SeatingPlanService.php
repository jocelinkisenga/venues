<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\FloorPlan;
use App\Models\FloorTable;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeatingPlanService
{
    public const GRID = 10;
    public const MARGIN = 60;

    /** Dimensions en pixels de chaque forme. */
    public const SHAPE_SIZES = [
        'round' => [120, 120],
        'square' => [110, 110],
        'rectangle' => [180, 80],
    ];

    public function __construct(private GuestService $guests) {}

    public function planFor(Booking $booking): FloorPlan
    {
        return FloorPlan::firstOrCreate(
            ['booking_id' => $booking->id],
            ['name' => 'Plan '.$booking->reference, 'width' => 1200, 'height' => 800, 'revision' => 1],
        );
    }

    /* ---------- Tables ---------- */

    public function addTable(FloorPlan $plan, array $data): FloorTable
    {
        [$width, $height] = self::SHAPE_SIZES[$data['shape']];

        $name = filled($data['name'] ?? null)
            ? $this->checkName($plan, $data['name'])
            : $this->nextName($plan);

        [$x, $y] = $this->freeSpot($plan, $width, $height);

        $table = $plan->tables()->create([
            'name' => $name,
            'shape' => $data['shape'],
            'category' => $data['category'] ?? 'standard',
            'seats' => (int) $data['seats'],
            'x' => $x,
            'y' => $y,
            'width' => $width,
            'height' => $height,
        ]);

        $this->fitCanvas($plan);
        $this->touch($plan);

        return $table;
    }

    public function update(FloorTable $table, array $data): void
    {
        $plan = $table->plan;
        [$width, $height] = self::SHAPE_SIZES[$data['shape']];

        $table->update([
            'name' => $this->checkName($plan, $data['name'], $table->id),
            'shape' => $data['shape'],
            'category' => $data['category'],
            'seats' => (int) $data['seats'],
            'width' => $width,
            'height' => $height,
        ]);

        $this->fitCanvas($plan);
        $this->touch($plan);
    }

    /** Position alignée sur la grille, jamais négative. */
    public function move(FloorTable $table, int $x, int $y): void
    {
        $table->update([
            'x' => max(0, (int) round($x / self::GRID) * self::GRID),
            'y' => max(0, (int) round($y / self::GRID) * self::GRID),
        ]);

        $this->fitCanvas($table->plan);
        $this->touch($table->plan);
    }

    /** Supprimer une table libère ses invités, qui redeviennent « non placés ». */
    public function remove(FloorTable $table): void
    {
        DB::transaction(function () use ($table) {
            $plan = $table->plan;

            Guest::where('floor_table_id', $table->id)->update(['floor_table_id' => null]);
            $table->delete();

            $this->touch($plan);
        });
    }

    /**
     * Génère les tables d'une disposition pour le nombre d'invités.
     * Refusé si des invités sont déjà placés, pour ne rien perdre.
     */
    public function applyLayout(FloorPlan $plan, string $layout, int $seats, int $guestCount): int
    {
        if ($plan->tables()->whereHas('guests')->exists()) {
            throw new \DomainException('Des invités sont déjà placés. Retirez-les avant de générer un nouveau plan.');
        }

        $count = max(1, (int) ceil($guestCount / max(1, $seats)));
        $positions = $this->positionsFor($layout, $count);

        DB::transaction(function () use ($plan, $seats, $positions) {
            $plan->tables()->delete();

            foreach ($positions as $i => [$x, $y, $shape, $width, $height]) {
                $plan->tables()->create([
                    'name' => 'Table '.($i + 1),
                    'shape' => $shape,
                    'category' => 'standard',
                    'seats' => $seats,
                    'x' => $x,
                    'y' => $y,
                    'width' => $width,
                    'height' => $height,
                ]);
            }

            $this->fitCanvas($plan);
            $this->touch($plan);
        });

        return $count;
    }

    /* ---------- Invités ---------- */

    /**
     * Place un invité à une table, ou le retire (table null).
     * Les accompagnants comptent dans la capacité.
     */
    public function assign(FloorPlan $plan, Guest $guest, ?FloorTable $table): void
    {
        if ((int) $guest->booking_id !== (int) $plan->booking_id) {
            throw new \DomainException("Cet invité n'appartient pas à cet événement.");
        }

        if ($table) {
            if ((int) $table->floor_plan_id !== (int) $plan->id) {
                throw new \DomainException("Cette table n'appartient pas au plan de cet événement.");
            }

            if ($guest->rsvp_status === 'declined') {
                throw new \DomainException("{$guest->full_name} a décliné l'invitation.");
            }

            if ((int) $guest->floor_table_id !== (int) $table->id) {
                $used = $this->seatsUsed($table, $guest->id);

                if ($used + $guest->seatCount() > $table->seats) {
                    throw new \DomainException("La table {$table->name} est complète ({$used}/{$table->seats}).");
                }
            }
        }

        $guest->update(['floor_table_id' => $table?->id]);
        $this->touch($plan);
    }

    /**
     * Import d'une liste : « Nom ; téléphone ; table ».
     * Crée les invités inconnus, met à jour les autres, et place chacun à sa table.
     */
    public function importGuests(FloorPlan $plan, string $text): array
    {
        $booking = $plan->booking;
        $tables = $plan->tables()->get()->keyBy(fn (FloorTable $t) => Str::lower($t->name));

        $report = [
            'created' => 0,
            'updated' => 0,
            'seated' => 0,
            'skipped' => 0,
            'unknown_tables' => [],
            'full_tables' => [],
        ];

        foreach (preg_split('/\r\n|\r|\n/', $text) as $raw) {
            $parts = array_map('trim', explode(';', trim($raw)));
            $name = Str::squish($parts[0] ?? '');

            if ($name === '') {
                $report['skipped']++;

                continue;
            }

            $phone = filled($parts[1] ?? null) ? preg_replace('/[^\d+]/', '', $parts[1]) : null;
            $tableLabel = trim($parts[2] ?? '');

            $guest = Guest::where('booking_id', $booking->id)
                ->whereRaw('LOWER(full_name) = ?', [Str::lower($name)])
                ->first();

            if ($guest) {
                if ($phone) {
                    $guest->update(['phone' => $phone]);
                }
                $report['updated']++;
            } else {
                $guest = $this->guests->create($booking, $name, $phone);
                $report['created']++;
            }

            if ($tableLabel === '') {
                continue;
            }

            $table = $tables->get(Str::lower($tableLabel));

            if (! $table) {
                $report['unknown_tables'][$tableLabel] = true;

                continue;
            }

            try {
                $this->assign($plan, $guest->fresh(), $table);
                $report['seated']++;
            } catch (\DomainException) {
                $report['full_tables'][$table->name] = true;
            }
        }

        $report['unknown_tables'] = array_keys($report['unknown_tables']);
        $report['full_tables'] = array_keys($report['full_tables']);

        return $report;
    }

    /* ---------- État du plan ---------- */

    /**
     * Données complètes pour l'écran et la synchronisation :
     * état de chaque table, invités placés, invités non placés, totaux.
     */
    public function stats(FloorPlan $plan): array
    {
        $plan->loadMissing(['tables.guests', 'booking']);

        $tables = $plan->tables->map(function (FloorTable $t) {
            $seated = $t->guests->where('rsvp_status', '!=', 'declined')->sortBy('full_name')->values();
            $used = (int) $seated->sum(fn (Guest $g) => $g->seatCount());

            return [
                'id' => $t->id,
                'name' => $t->name,
                'shape' => $t->shape,
                'category' => $t->category,
                'seats' => (int) $t->seats,
                'x' => (int) $t->x,
                'y' => (int) $t->y,
                'width' => (int) $t->width,
                'height' => (int) $t->height,
                'used' => $used,
                'status' => self::statusFor($used, (int) $t->seats),
                'guests' => $seated->map(fn (Guest $g) => [
    'id' => $g->id,
    'name' => $g->full_name,
    'seats' => $g->seatCount(),
    'status' => $g->rsvp_status,
    'companions' => (int) $g->companions,
])->all(),
            ];
        })->values()->all();

        $unseated = Guest::where('booking_id', $plan->booking_id)
            ->whereNull('floor_table_id')
            ->where('rsvp_status', '!=', 'declined')
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'companions'])
            ->map(fn (Guest $g) => [
                'id' => $g->id,
                'name' => $g->full_name,
                'status' => $g->rsvp_status,
                'companions' => (int) $g->companions,
                'seats' => $g->seatCount(),
            ])
            ->all();

        $count = fn (string $status) => collect($tables)->where('status', $status)->count();

        return [
            'tables' => $tables,
            'unseated' => $unseated,
            'totals' => [
                'tables' => count($tables),
                'full' => $count('full'),
                'partial' => $count('partial'),
                'empty' => $count('empty'),
                'over' => $count('over'),
                'seats' => collect($tables)->sum('seats'),
                'seated' => collect($tables)->sum('used'),
                'expected' => (int) $plan->booking->guests_count,
                'unseatedPeople' => collect($unseated)->sum('seats'),
            ],
        ];
    }

    public static function statusFor(int $used, int $seats): string
    {
        return match (true) {
            $used === 0 => 'empty',
            $used < $seats => 'partial',
            $used === $seats => 'full',
            default => 'over',
        };
    }

    public function seatsUsed(FloorTable $table, ?int $excludeGuestId = null): int
    {
        return (int) $table->guests()
            ->where('rsvp_status', '!=', 'declined')
            ->when($excludeGuestId, fn ($q) => $q->whereKeyNot($excludeGuestId))
            ->get()
            ->sum(fn (Guest $g) => $g->seatCount());
    }

    /* ---------- Outils internes ---------- */

    /** Toute modification retire la validation et incrémente la version. */
    private function touch(FloorPlan $plan): void
    {
        $plan->forceFill([
            'published_at' => null,
            'revision' => $plan->revision + 1,
        ])->save();
    }

    /** Agrandit le plan pour que toutes les tables restent visibles. */
    private function fitCanvas(FloorPlan $plan): void
    {
        $tables = $plan->tables()->get(['x', 'y', 'width', 'height']);

        $plan->forceFill([
            'width' => max(1200, (int) $tables->max(fn ($t) => $t->x + $t->width) + self::MARGIN),
            'height' => max(800, (int) $tables->max(fn ($t) => $t->y + $t->height) + self::MARGIN),
        ])->save();
    }

    /**
 * Range toutes les tables en grille, dans l'ordre de création.
 * Ne modifie ni les convives, ni les noms, ni les formes.
 */
public function arrange(FloorPlan $plan, int $perRow = 0): void
{
    $tables = $plan->tables()->get();

    if ($tables->isEmpty()) {
        return;
    }

    $perRow = $perRow > 0 ? min($perRow, 8) : (int) ceil(sqrt($tables->count()));

    // Cellule assez large pour la plus grande table, avec un espace de 80 px, arrondie à la grille
    $cellWidth = (int) (ceil(($tables->max('width') + 80) / self::GRID) * self::GRID);
    $cellHeight = (int) (ceil(($tables->max('height') + 80) / self::GRID) * self::GRID);

    DB::transaction(function () use ($plan, $tables, $perRow, $cellWidth, $cellHeight) {
        foreach ($tables->values() as $index => $table) {
            $table->update([
                'x' => self::MARGIN + ($index % $perRow) * $cellWidth,
                'y' => self::MARGIN + intdiv($index, $perRow) * $cellHeight,
            ]);
        }

        $this->fitCanvas($plan);
        $this->touch($plan);
    });
}

    private function checkName(FloorPlan $plan, string $name, ?int $ignoreId = null): string
    {
        $name = Str::limit(Str::squish($name), 40, '');

        if ($name === '') {
            throw new \DomainException('Le nom de la table est obligatoire.');
        }

        $taken = $plan->tables()
            ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists();

        if ($taken) {
            throw new \DomainException("Le nom « {$name} » est déjà utilisé dans ce plan.");
        }

        return $name;
    }

    private function nextName(FloorPlan $plan): string
    {
        $i = $plan->tables()->count() + 1;

        while ($plan->tables()->whereRaw('LOWER(name) = ?', [Str::lower("table {$i}")])->exists()) {
            $i++;
        }

        return "Table {$i}";
    }

    /** Première place libre sur la grille, sans chevaucher une autre table. */
    private function freeSpot(FloorPlan $plan, int $width, int $height): array
    {
        $tables = $plan->tables()->get(['x', 'y', 'width', 'height']);
        $maxX = max($plan->width, 1200);

        for ($y = 40; $y <= $plan->height; $y += 40) {
            for ($x = 40; $x + $width <= $maxX; $x += 40) {
                $overlaps = $tables->contains(fn ($t) =>
                    $x < $t->x + $t->width + 20 && $x + $width + 20 > $t->x
                    && $y < $t->y + $t->height + 20 && $y + $height + 20 > $t->y);

                if (! $overlaps) {
                    return [$x, $y];
                }
            }
        }

        // Plan plein : on ajoute la table sous les autres
        $bottom = $tables->isEmpty() ? 40 : (int) $tables->max(fn ($t) => $t->y + $t->height) + 40;

        return [40, $bottom];
    }

    /** @return array<int, array{0: int, 1: int, 2: string, 3: int, 4: int}> */
    private function positionsFor(string $layout, int $count): array
    {
        return match ($layout) {
            'theatre' => collect(range(0, $count - 1))
                ->map(fn ($i) => [60 + ($i % 4) * 220, 60 + intdiv($i, 4) * 110, 'rectangle', 180, 70])
                ->all(),
            'cocktail' => collect(range(0, $count - 1))
                ->map(fn ($i) => [60 + ($i % 8) * 130, 60 + intdiv($i, 8) * 130, 'round', 100, 100])
                ->all(),
            'u' => $this->uPositions($count),
            default => collect(range(0, $count - 1))
                ->map(fn ($i) => [60 + ($i % 5) * 170, 60 + intdiv($i, 5) * 170, 'round', 120, 120])
                ->all(),
        };
    }

    /** Disposition en U : une colonne à gauche, une rangée au fond, une colonne à droite. */
    private function uPositions(int $count): array
    {
        $left = (int) ceil($count / 3);
        $right = (int) floor(($count - $left) / 2);
        $bottom = $count - $left - $right;

        $positions = [];

        for ($i = 0; $i < $left; $i++) {
            $positions[] = [60, 60 + $i * 110, 'rectangle', 180, 70];
        }

        for ($i = 0; $i < $bottom; $i++) {
            $positions[] = [260 + $i * 200, 60 + max($left, $right) * 110, 'rectangle', 180, 70];
        }

        $rightX = 260 + max(1, $bottom) * 200;

        for ($i = 0; $i < $right; $i++) {
            $positions[] = [$rightX, 60 + $i * 110, 'rectangle', 180, 70];
        }

        return $positions;
    }

    /** Invités à placer : confirmés d'abord, sans refus, avec recherche sur le nom. */
public function candidates(FloorPlan $plan, string $search = '', int $limit = 12): \Illuminate\Support\Collection
{
    $term = trim($search);

    return Guest::where('booking_id', $plan->booking_id)
        ->whereNull('floor_table_id')
        ->where('rsvp_status', '!=', 'declined')
        ->when($term !== '', fn ($q) => $q->where('full_name', 'like', "%{$term}%"))
        ->orderByRaw("CASE WHEN rsvp_status = 'confirmed' THEN 0 ELSE 1 END")
        ->orderBy('full_name')
        ->limit($limit)
        ->get();
}

/** Crée un convive hors liste, confirmé, et le place. La place est vérifiée avant toute création. */
public function createExceptionalGuest(FloorPlan $plan, FloorTable $table, string $name, ?string $phone, int $companions): Guest
{
    $needed = 1 + max(0, $companions);
    $free = (int) $table->seats - $this->seatsUsed($table);

    if ($needed > $free) {
        throw new \DomainException("La table {$table->name} n'a que {$free} place(s) libre(s).");
    }

    return DB::transaction(function () use ($plan, $table, $name, $phone, $companions) {
        $guest = $this->guests->create($plan->booking, $name, $phone);
        $guest->update(['rsvp_status' => 'confirmed', 'companions' => $companions]);
        $this->assign($plan, $guest->fresh(), $table);

        return $guest->fresh();
    });
}
}