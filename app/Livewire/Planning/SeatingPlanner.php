<?php

namespace App\Livewire\Planning;

use App\Models\Booking;
use App\Models\SeatingTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SeatingPlanner extends Component
{
    public const LAYOUTS = [
        'banquet' => ['label' => 'Banquet (tables rondes)', 'shape' => 'round'],
        'u' => ['label' => 'Disposition en U', 'shape' => 'rectangle'],
        'theatre' => ['label' => 'Style théâtre', 'shape' => 'rectangle'],
        'cocktail' => ['label' => 'Cocktail debout', 'shape' => 'round'],
    ];

    public Booking $booking;
    public string $tableLayout = 'banquet';
    public string $seatsPerTable = '8';

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        $this->booking = $booking;

        if ($existing = $booking->seatingTables()->first()) {
            $this->tableLayout = $existing->layout;
            $this->seatsPerTable = (string) $existing->seats;
        }
    }

    /** Génère les tables pour couvrir le nombre d'invités validé dans le devis. */
    public function generate(): void
    {
        $this->validate([
            'tableLayout' => ['required', 'in:'.implode(',', array_keys(self::LAYOUTS))],
            'seatsPerTable' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $seats = (int) $this->seatsPerTable;
        $count = (int) ceil($this->booking->guests_count / $seats);
        $shape = self::LAYOUTS[$this->tableLayout]['shape'];

        $this->booking->seatingTables()->delete();

        for ($i = 0; $i < $count; $i++) {
            $this->booking->seatingTables()->create([
                'layout' => $this->tableLayout,
                'label' => 'T'.($i + 1),
                'shape' => $shape,
                'seats' => $seats,
                'x' => ($i % 6) * 140,
                'y' => intdiv($i, 6) * 140,
                'is_vip' => false,
            ]);
        }
    }

    public function toggleVip(int $tableId): void
    {
        $table = $this->booking->seatingTables()->findOrFail($tableId);
        $table->update(['is_vip' => ! $table->is_vip]);
    }

    public function moveTable(int $tableId, int $x, int $y): void
    {
        $this->booking->seatingTables()->findOrFail($tableId)->update([
            'x' => max(0, $x),
            'y' => max(0, $y),
        ]);
    }

    public function render()
    {
        $tables = $this->booking->seatingTables()->orderBy('id')->get();
        $seated = (int) $tables->sum('seats');

        return view('livewire.planning.seating-planner', [
            'tables' => $tables,
            'seated' => $seated,
            'missing' => max(0, $this->booking->guests_count - $seated),
            'layouts' => self::LAYOUTS,
        ])->layout('layouts.app');
    }
}
