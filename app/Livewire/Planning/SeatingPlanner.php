<?php

namespace App\Livewire\Planning;

use App\Models\Booking;
use App\Models\FloorPlan;
use App\Models\FloorTable;
use App\Models\Guest;
use App\Services\SeatingPlanService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SeatingPlanner extends Component
{
    public int $bookingId;
    public int $planId;
    public bool $canEdit = false;
    public string $arrangePerRow = '0';
    // Panneau de la table ouverte
    public ?int $modalTableId = null;
    public string $tableName = '';
    public string $tableShape = 'round';
    public string $tableCategory = 'standard';
    public string $tableSeats = '8';
    public bool $showSettings = false;

    // Recherche et convive exceptionnel
    public string $guestSearch = '';
    public bool $showQuick = false;
    public string $quickName = '';
    public ?string $quickPhone = null;
    public string $quickCompanions = '0';

    public ?string $modalNotice = null;
    public ?string $modalError = null;

    // Outils latéraux
    public string $newName = '';
    public string $newShape = 'round';
    public string $newCategory = 'standard';
    public string $newSeats = '8';
    public string $layout = 'banquet';
    public string $layoutSeats = '8';
    public string $importText = '';
    public ?array $importReport = null;

    public ?string $notice = null;
    public ?string $error = null;

    public function mount(Booking $booking, SeatingPlanService $service): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);

        $this->bookingId = $booking->id;
        $this->planId = $service->planFor($booking)->id;
        $this->canEdit = Auth::user()->role->canSell();
    }

    /* ---------- Panneau de table ---------- */

    public function selectTable(int $id): void
    {
        $table = $this->table($id);

        $this->modalTableId = $table->id;
        $this->tableName = $table->name;
        $this->tableShape = $table->shape;
        $this->tableCategory = $table->category;
        $this->tableSeats = (string) $table->seats;

        $this->resetPanel();
    }

    public function closeModal(): void
    {
        $this->modalTableId = null;
        $this->resetPanel();
    }

    public function addGuest(int $guestId, SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $table = $this->selected();
        $guest = $this->guest($guestId);

        try {
            $service->assign($this->plan(), $guest, $table);
        } catch (\DomainException $e) {
            $this->modalError = $e->getMessage();
            $this->modalNotice = null;

            return;
        }

        $this->modalNotice = "{$guest->full_name} placé à {$table->name}.";
        $this->modalError = null;
        $this->guestSearch = '';
    }

    public function removeGuest(int $guestId, SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $table = $this->selected();
        $guest = $this->guest($guestId);

        $service->assign($this->plan(), $guest, null);

        $this->modalNotice = "{$guest->full_name} retiré de {$table->name}.";
        $this->modalError = null;
    }

    public function createGuest(SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $this->quickPhone = filled($this->quickPhone) ? trim($this->quickPhone) : null;

        $data = $this->validate([
            'quickName' => ['required', 'string', 'max:120'],
            'quickPhone' => ['nullable', 'string', 'max:30', 'regex:/^\+?[0-9\s]{8,20}$/'],
            'quickCompanions' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $table = $this->selected();

        try {
            $guest = $service->createExceptionalGuest(
                $this->plan(), $table, $data['quickName'], $data['quickPhone'], (int) $data['quickCompanions'],
            );
        } catch (\DomainException $e) {
            $this->modalError = $e->getMessage();
            $this->modalNotice = null;

            return;
        }

        $this->modalNotice = "{$guest->full_name} ajouté à {$table->name}.";
        $this->modalError = null;
        $this->showQuick = false;
        $this->quickName = '';
        $this->quickPhone = null;
        $this->quickCompanions = '0';
    }

    public function saveTable(SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $data = $this->validate([
            'tableName' => ['required', 'string', 'max:40'],
            'tableShape' => ['required', Rule::in(array_keys(config('venue.seating.shapes')))],
            'tableCategory' => ['required', Rule::in(array_keys(config('venue.seating.categories')))],
            'tableSeats' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        try {
            $service->update($this->selected(), [
                'name' => $data['tableName'],
                'shape' => $data['tableShape'],
                'category' => $data['tableCategory'],
                'seats' => $data['tableSeats'],
            ]);
        } catch (\DomainException $e) {
            $this->modalError = $e->getMessage();
            $this->modalNotice = null;

            return;
        }

        $this->modalNotice = 'Table mise à jour.';
        $this->modalError = null;
        $this->showSettings = false;
    }

    public function deleteSelected(SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $name = $this->selected()->name;
        $service->remove($this->selected());

        $this->closeModal();
        $this->notice = "Table « {$name} » supprimée. Ses convives sont redevenus non placés.";
    }

    /* ---------- Plan ---------- */

    public function moveTable(int $tableId, int $x, int $y, SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $service->move($this->table($tableId), $x, $y);
    }

    public function arrangeTables(SeatingPlanService $service): void
{
    $this->authorizeEdit();

    $this->validate([
        'arrangePerRow' => ['required', Rule::in(['0', '3', '4', '5', '6', '8'])],
    ]);

    $service->arrange($this->plan(), (int) $this->arrangePerRow);

    $this->notice = 'Tables alignées en rangées.';
    $this->error = null;
}

    public function addTable(SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $data = $this->validate([
            'newName' => ['nullable', 'string', 'max:40'],
            'newShape' => ['required', Rule::in(array_keys(config('venue.seating.shapes')))],
            'newCategory' => ['required', Rule::in(array_keys(config('venue.seating.categories')))],
            'newSeats' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        try {
            $table = $service->addTable($this->plan(), [
                'name' => $data['newName'],
                'shape' => $data['newShape'],
                'category' => $data['newCategory'],
                'seats' => $data['newSeats'],
            ]);
        } catch (\DomainException $e) {
            $this->error = $e->getMessage();
            $this->notice = null;

            return;
        }

        $this->newName = '';
        $this->error = null;
        $this->selectTable($table->id);
    }

    public function applyLayout(SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $data = $this->validate([
            'layout' => ['required', Rule::in(array_keys(config('venue.seating.layouts')))],
            'layoutSeats' => ['required', 'integer', 'min:1', 'max:30'],
        ]);

        $booking = $this->booking();

        try {
            $count = $service->applyLayout($this->plan(), $data['layout'], (int) $data['layoutSeats'], $booking->guests_count);
        } catch (\DomainException $e) {
            $this->error = $e->getMessage();
            $this->notice = null;

            return;
        }

        $this->closeModal();
        $this->error = null;
        $this->notice = "{$count} table(s) générée(s) pour {$booking->guests_count} personnes.";
    }

    public function importList(SeatingPlanService $service): void
    {
        $this->authorizeEdit();

        $this->validate(['importText' => ['required', 'string', 'max:20000']]);

        $this->importReport = $service->importGuests($this->plan(), $this->importText);
        $this->importText = '';
        $this->notice = 'Liste importée.';
        $this->error = null;
    }

    public function publish(): void
    {
        $this->authorizeEdit();

        $this->plan()->update(['published_at' => now()]);

        $this->notice = 'Plan validé. Toute modification le retirera de la validation.';
        $this->error = null;
    }

    /* ---------- Rendu ---------- */

    public function render(SeatingPlanService $service)
    {
        $plan = $this->plan();
        $stats = $service->stats($plan);

        $modal = $this->modalTableId
            ? collect($stats['tables'])->firstWhere('id', $this->modalTableId)
            : null;

        $free = $modal ? max(0, $modal['seats'] - $modal['used']) : 0;

        $candidates = $modal
            ? $service->candidates($plan, $this->guestSearch)->map(fn (Guest $g) => [
                'id' => $g->id,
                'name' => $g->full_name,
                'status' => $g->rsvp_status,
                'seats' => $g->seatCount(),
                'fits' => $g->seatCount() <= $free,
            ])->all()
            : [];

        return view('livewire.planning.seating-planner', [
            'plan' => $plan,
            'booking' => $this->booking(),
            'tables' => $stats['tables'],
            'unseated' => $stats['unseated'],
            'totals' => $stats['totals'],
            'modal' => $modal,
            'free' => $free,
            'candidates' => $candidates,
            'categories' => config('venue.seating.categories'),
            'shapes' => config('venue.seating.shapes'),
            'layouts' => config('venue.seating.layouts'),
        ])->layout('layouts.app');
    }

    /* ---------- Outils ---------- */

    private function resetPanel(): void
    {
        $this->guestSearch = '';
        $this->showQuick = false;
        $this->showSettings = false;
        $this->quickName = '';
        $this->quickPhone = null;
        $this->quickCompanions = '0';
        $this->modalNotice = null;
        $this->modalError = null;
    }

    private function booking(): Booking
    {
        return Booking::with('venue')->findOrFail($this->bookingId);
    }

    private function plan(): FloorPlan
    {
        return FloorPlan::where('booking_id', $this->bookingId)->findOrFail($this->planId);
    }

    private function table(int $id): FloorTable
    {
        return FloorTable::where('floor_plan_id', $this->planId)->findOrFail($id);
    }

    private function selected(): FloorTable
    {
        abort_unless($this->modalTableId, 404);

        return $this->table($this->modalTableId);
    }

    private function guest(int $id): Guest
    {
        return Guest::where('booking_id', $this->bookingId)->findOrFail($id);
    }

    private function authorizeEdit(): void
    {
        abort_unless($this->canEdit, 403);
    }
}