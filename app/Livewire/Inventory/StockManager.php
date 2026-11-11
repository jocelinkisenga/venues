<?php

namespace App\Livewire\Inventory;

use App\Models\EquipmentItem;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StockManager extends Component
{
    public const CATEGORIES = [
        'chaise' => 'Chaises', 'table' => 'Tables', 'sono' => 'Sono & éclairage',
        'decor' => 'Décoration', 'vaisselle' => 'Vaisselle', 'autre' => 'Autre',
    ];

    public string $name = '';
    public string $category = 'chaise';
    public string $replacement_cost = '0';

    public ?int $itemId = null;
    public string $quantity = '1';
    public string $movementType = 'achat';
    public string $note = '';

    public function addItem(): void
    {
        abort_unless(Auth::user()->role->canSell(), 403);

        $data = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in(array_keys(self::CATEGORIES))],
            'replacement_cost' => ['required', 'numeric', 'min:0'],
        ]);

        EquipmentItem::create($data + ['venue_id' => Auth::user()->venue_id]);

        $this->reset('name', 'replacement_cost');
    }

    public function adjust(InventoryService $inventory): void
    {
        abort_unless(Auth::user()->role->canSell(), 403);

        $this->validate([
            'itemId' => ['required', Rule::exists('equipment_items', 'id')->where('venue_id', Auth::user()->venue_id)],
            'quantity' => ['required', 'integer', 'min:-100000', 'max:100000'],
            'movementType' => ['required', Rule::in(['achat', 'ajustement'])],
        ]);

        $item = EquipmentItem::where('venue_id', Auth::user()->venue_id)->findOrFail($this->itemId);

        try {
            $inventory->adjust($item, (int) $this->quantity, $this->movementType, $this->note ?: null, Auth::user());
        } catch (\DomainException $e) {
            $this->addError('quantity', $e->getMessage());

            return;
        }

        $this->reset('note');
        $this->quantity = '1';
    }

    public function render()
    {
        $rows = EquipmentItem::where('venue_id', Auth::user()->venue_id)
            ->orderBy('category')->orderBy('name')->get()
            ->map(fn (EquipmentItem $item) => ['item' => $item, 'stock' => $item->stock()]);

        return view('livewire.inventory.stock-manager', [
            'rows' => $rows,
            'categories' => self::CATEGORIES,
        ])->layout('layouts.app');
    }
}