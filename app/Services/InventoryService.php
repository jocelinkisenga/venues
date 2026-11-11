<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\EquipmentItem;
use App\Models\EquipmentMovement;
use App\Models\User;

class InventoryService
{
    /** Quantité sortie pour cette réservation et pas encore revenue ni déclarée cassée. */
    public function outstanding(Booking $booking, EquipmentItem $item): int
    {
        $base = EquipmentMovement::where('booking_id', $booking->id)->where('equipment_item_id', $item->id);

        $out = (int) (clone $base)->where('type', 'sortie')->sum('quantity');
        $back = (int) (clone $base)->whereIn('type', ['retour', 'casse'])->sum('quantity');

        return $out - $back;
    }

    public function checkout(Booking $booking, EquipmentItem $item, int $quantity, User $actor): void
    {
        if ($quantity < 1) {
            throw new \DomainException('Quantité invalide.');
        }

        $stock = $item->stock();

        if ($stock < $quantity) {
            throw new \DomainException("Stock insuffisant : {$stock} disponible(s) pour {$item->name}.");
        }

        $this->record($booking, $item, $actor, 'sortie', $quantity, -$quantity);
    }

    /**
     * Retour : les pièces bonnes reviennent en stock. Les pièces cassées ne reviennent pas :
     * elles sont sorties du stock au moment de la sortie, donc on les trace avec delta = 0.
     */
    public function checkin(Booking $booking, EquipmentItem $item, int $returned, int $broken, User $actor): void
    {
        if ($returned < 0 || $broken < 0 || $returned + $broken < 1) {
            throw new \DomainException('Quantités invalides.');
        }

        $outstanding = $this->outstanding($booking, $item);

        if ($returned + $broken > $outstanding) {
            throw new \DomainException("Seulement {$outstanding} pièce(s) sortie(s) pour cette réservation.");
        }

        if ($returned > 0) {
            $this->record($booking, $item, $actor, 'retour', $returned, $returned);
        }

        if ($broken > 0) {
            $this->record($booking, $item, $actor, 'casse', $broken, 0, 'Pièce(s) cassée(s) au retour');
        }
    }

    /** Entrée (achat) ou correction (ajustement) de stock, hors réservation. */
    public function adjust(EquipmentItem $item, int $delta, string $type, ?string $note, User $actor): void
    {
        if ($delta === 0) {
            throw new \DomainException('La quantité doit être différente de zéro.');
        }

        if ($type === 'achat' && $delta < 0) {
            throw new \DomainException('Un achat ajoute du stock.');
        }

        if ($item->stock() + $delta < 0) {
            throw new \DomainException('Le stock ne peut pas devenir négatif.');
        }

        $this->record(null, $item, $actor, $type, abs($delta), $delta, $note);
    }

    private function record(
        ?Booking $booking,
        EquipmentItem $item,
        User $actor,
        string $type,
        int $quantity,
        int $delta,
        ?string $note = null,
    ): void {
        EquipmentMovement::create([
            'venue_id' => $item->venue_id,
            'equipment_item_id' => $item->id,
            'booking_id' => $booking?->id,
            'user_id' => $actor->id,
            'type' => $type,
            'quantity' => $quantity,
            'delta' => $delta,
            'note' => $note,
        ]);
    }
}