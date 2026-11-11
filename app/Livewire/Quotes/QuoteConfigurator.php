<?php

namespace App\Livewire\Quotes;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Quote;
use App\Services\QuoteCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class QuoteConfigurator extends Component
{
    public Booking $booking;

    // Ligne de prestation
    public string $label = '';
    public string $quantity = '1';
    public string $unit_price = '';
    public ?int $partner_id = null;
    public string $manual_commission = '0';

    // Remise commerciale (traçabilité du motif)
    public string $discount_type = 'none';
    public string $discount_value = '0';
    public string $discount_reason = '';

    // Paiement
    public string $payment_amount = '';
    public string $payment_method = 'mobile_money';
    public string $payment_kind = 'deposit';
    public string $payment_reference = '';

    public function mount(Booking $booking, QuoteCalculator $calculator): void
    {
        abort_unless($booking->venue_id === Auth::user()->venue_id, 404);
        $this->booking = $booking;

        $quote = $this->quote();
        $this->discount_type = $quote->discount_type ?? 'none';
        $this->discount_value = (string) $quote->discount_value;
        $this->discount_reason = (string) $quote->discount_reason;
        $this->payment_amount = (string) $this->depositDue($quote);
    }

    public function addLine(QuoteCalculator $calculator): void
    {
        $this->authorizeSales();

        $data = $this->validate([
            'label' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'partner_id' => ['nullable', Rule::exists('partners', 'id')->where('venue_id', $this->booking->venue_id)],
            'manual_commission' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $this->quote()->lines()->create([
            'partner_id' => $data['partner_id'],
            'label' => $data['label'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'commission_percent' => $data['manual_commission'],
        ]);

        $this->recalc($calculator);
        $this->reset('label', 'unit_price', 'partner_id');
        $this->quantity = '1';
        $this->manual_commission = '0';
    }

    public function removeLine(int $lineId, QuoteCalculator $calculator): void
    {
        $this->authorizeSales();
        $this->quote()->lines()->whereKey($lineId)->delete();
        $this->recalc($calculator);
    }

    public function applyDiscount(QuoteCalculator $calculator): void
    {
        $this->authorizeSales();

        $this->validate([
            'discount_type' => ['required', Rule::in(['none', 'percent', 'fixed'])],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'discount_reason' => ['required_unless:discount_type,none', 'string', 'max:255'],
        ]);

        if ($this->discount_type === 'percent' && (float) $this->discount_value > 100) {
            $this->addError('discount_value', 'Une remise en pourcentage ne peut dépasser 100 %.');

            return;
        }

        $isNone = $this->discount_type === 'none';

        $this->quote()->update([
            'discount_type' => $isNone ? null : $this->discount_type,
            'discount_value' => $isNone ? 0 : $this->discount_value,
            'discount_reason' => $isNone ? null : $this->discount_reason,
            'discount_by' => $isNone ? null : Auth::id(),
        ]);

        $this->recalc($calculator);
    }

    public function recordPayment(QuoteCalculator $calculator): void
    {
        $this->authorizeSales();

        $this->validate([
            'payment_amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', Rule::in(array_keys(config('venue.payment_methods')))],
            'payment_kind' => ['required', Rule::in(['deposit', 'balance', 'other'])],
            'payment_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $this->booking->payments()->create([
            'recorded_by' => Auth::id(),
            'amount' => $this->payment_amount,
            'kind' => $this->payment_kind,
            'method' => $this->payment_method,
            'reference' => $this->payment_reference ?: null,
            'paid_at' => now(),
        ]);

        $quote = $this->quote();
        $paid = (float) $this->booking->payments()->sum('amount');

        // Acompte versé : l'option devient une réservation confirmée
        if ($this->booking->status === BookingStatus::Option && $paid >= $this->depositDue($quote)) {
            $this->booking->update(['status' => BookingStatus::Confirmed, 'option_expires_at' => null]);
        }

        $this->reset('payment_reference');
        $this->payment_amount = (string) max(0, round((float) $quote->total - $paid));
    }

    public function render()
    {
        $quote = $this->quote();
        $venue = $this->booking->venue;
        $paid = (float) $this->booking->payments()->sum('amount');
        $deposit = $this->depositDue($quote);

        // Envoi du devis par WhatsApp (canal dominant en Afrique)
$phone = preg_replace('/\D/', '', $this->booking->client_phone);
$portalUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
    'portal.show', now()->addDays(15), ['booking' => $this->booking->id]
);
$message = "Bonjour {$this->booking->client_name}, voici votre devis {$this->booking->reference} : "
    ."total {$venue->money($quote->total)}, acompte {$venue->money($deposit)}. "
    ."Consultez et réglez en ligne : {$portalUrl}";

        return view('livewire.quotes.quote-configurator', [
            'quote' => $quote,
            'paid' => $paid,
            'deposit' => $deposit,
            'balance' => max(0, (float) $quote->total - $paid),
            'partners' => Partner::where('venue_id', $this->booking->venue_id)->orderBy('name')->get(),
            'paymentMethods' => config('venue.payment_methods'),
            'whatsappUrl' => 'https://wa.me/'.$phone.'?text='.urlencode($message),
        ])->layout('layouts.app');
    }

    /** Devis frais avec lignes chargées (évite les relations en cache). */
    private function quote(): Quote
    {
        $quote = app(QuoteCalculator::class)->createForBooking($this->booking);

        return Quote::with(['lines.partner', 'booking.venue'])->findOrFail($quote->id);
    }

    private function recalc(QuoteCalculator $calculator): void
    {
        $calculator->recalculate(Quote::where('booking_id', $this->booking->id)->firstOrFail());
    }

    private function depositDue(Quote $quote): float
    {
        return round((float) $quote->total * $this->booking->venue->deposit_percent / 100);
    }

    private function authorizeSales(): void
    {
        abort_unless(Auth::user()->role->canSell(), 403);
    }
}
