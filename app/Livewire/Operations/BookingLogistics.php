<?php

namespace App\Livewire\Operations;

use App\Models\Booking;
use App\Models\EquipmentItem;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BookingLogistics extends Component
{
    public Booking $booking;
    public ?int $itemId = null;
    public string $quantity = '1';
    public array $returned = [];
    public array $broken = [];

    public function mount(Booking $booking): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        $this->booking = $booking;
    }

    public function checkout(InventoryService $inventory): void
    {
        $data = $this->validate([
            'itemId' => ['required', Rule::exists('equipment_items', 'id')->where('venue_id', $this->booking->venue_id)],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        $item = EquipmentItem::where('venue_id', $this->booking->venue_id)->findOrFail($data['itemId']);

        try {
            $inventory->checkout($this->booking, $item, (int) $data['quantity'], Auth::user());
        } catch (\DomainException $e) {
            $this->addError('quantity', $e->getMessage());

            return;
        }

        $this->reset('itemId');
        $this->quantity = '1';
    }

    public function checkin(int $itemId, InventoryService $inventory): void
    {
        $item = EquipmentItem::where('venue_id', $this->booking->venue_id)->findOrFail($itemId);

        try {
            $inventory->checkin(
                $this->booking,
                $item,
                (int) ($this->returned[$itemId] ?? 0),
                (int) ($this->broken[$itemId] ?? 0),
                Auth::user(),
            );
        } catch (\DomainException $e) {
            $this->addError('retour-'.$itemId, $e->getMessage());

            return;
        }

        unset($this->returned[$itemId], $this->broken[$itemId]);
    }

    public function render(InventoryService $inventory)
    {
        $rows = EquipmentItem::where('venue_id', $this->booking->venue_id)->orderBy('name')->get()
            ->map(fn (EquipmentItem $item) => [
                'item' => $item,
                'stock' => $item->stock(),
                'out' => $inventory->outstanding($this->booking, $item),
            ]);

        return view('livewire.operations.booking-logistics', [
            'rows' => $rows,
            'items' => $rows->pluck('item'),
        ])->layout('layouts.app');
    }
}