<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Contract;
use Illuminate\Support\Str;

class ContractService
{
    public function __construct(private DocumentNumberer $numberer) {}

    /** Génère le contrat à partir du devis. Un contrat déjà envoyé ne peut plus être régénéré. */
    public function issue(Booking $booking): Contract
    {
        $booking->loadMissing(['venue', 'space', 'quote.lines.partner']);

        if (! $booking->quote) {
            throw new \DomainException('Créez le devis avant d\'émettre le contrat.');
        }

        $existing = Contract::where('booking_id', $booking->id)->first();

        if ($existing && $existing->status !== 'draft') {
            throw new \DomainException('Ce contrat a déjà été envoyé : il ne peut plus être régénéré.');
        }

        $body = view('contracts.body', [
            'booking' => $booking,
            'venue' => $booking->venue,
            'quote' => $booking->quote,
            'policy' => config('venue.cancellation_policy'),
        ])->render();

        return Contract::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'venue_id' => $booking->venue_id,
                'number' => $existing?->number ?? $this->numberer->next($booking->venue, 'CTR'),
                'status' => 'draft',
                'body' => $body,
            ],
        );
    }

    public function markSent(Contract $contract): void
    {
        if ($contract->status === 'draft') {
            $contract->update(['status' => 'sent', 'sent_at' => now()]);
        }
    }

    /** Signature électronique simple : nom saisi, acceptation, horodatage, IP et empreinte du contenu. */
    public function sign(Contract $contract, string $name, string $ip, ?string $userAgent): void
    {
        if ($contract->status === 'signed') {
            throw new \DomainException('Ce contrat est déjà signé.');
        }

        $name = Str::squish($name);

        if (mb_strlen($name) < 3) {
            throw new \DomainException('Indiquez votre nom complet pour signer.');
        }

        $contract->update([
            'status' => 'signed',
            'signed_name' => $name,
            'signed_at' => now(),
            'signed_ip' => $ip,
            'signed_user_agent' => $userAgent ? Str::limit($userAgent, 250, '') : null,
            'document_hash' => hash('sha256', $contract->body),
        ]);
    }
}