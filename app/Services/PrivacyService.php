<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Lead;
use App\Models\Payer;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrivacyService
{
    public function summary(Venue $venue, string $phone): array
    {
        $suffix = $this->suffix($phone);

        return [
            'bookings' => $this->bookings($venue, $suffix)->count(),
            'guests' => $this->guests($venue, $suffix)->count(),
            'leads' => $this->leads($venue, $suffix)->count(),
            'payers' => $this->payers($venue, $suffix)->count(),
        ];
    }

    /** Export portable de toutes les données liées à un numéro. */
    public function export(Venue $venue, User $actor, string $phone): array
    {
        $suffix = $this->suffix($phone);

        $bookings = $this->bookings($venue, $suffix)->load([
            'space:id,name', 'payments', 'quote:id,booking_id,total',
            'contract:id,booking_id,number,status,signed_name,signed_at',
        ]);

        $data = [
            'exporte_le' => now()->toIso8601String(),
            'salle' => $venue->name,
            'reservations' => $bookings->map(fn (Booking $b) => [
                'reference' => $b->reference,
                'evenement' => $b->event_type,
                'espace' => $b->space?->name,
                'debut' => $b->starts_at->toIso8601String(),
                'fin' => $b->ends_at->toIso8601String(),
                'statut' => $b->status->value,
                'client' => $b->client_name,
                'telephone' => $b->client_phone,
                'montant_total' => $b->quote?->total,
                'paiements' => $b->payments->map(fn (Payment $p) => [
                    'montant' => $p->amount,
                    'mode' => $p->method,
                    'date' => $p->paid_at?->toIso8601String(),
                ])->values(),
                'contrat' => $b->contract ? [
                    'numero' => $b->contract->number,
                    'signe_par' => $b->contract->signed_name,
                    'signe_le' => $b->contract->signed_at?->toIso8601String(),
                ] : null,
            ])->values(),
            'invites' => $this->guests($venue, $suffix)->map(fn (Guest $g) => [
                'nom' => $g->full_name,
                'telephone' => $g->phone,
                'reponse' => $g->rsvp_status,
                'entree' => $g->attended_at?->toIso8601String(),
            ])->values(),
            'prospects' => $this->leads($venue, $suffix)->map(fn (Lead $l) => [
                'nom' => $l->full_name,
                'telephone' => $l->phone,
                'email' => $l->email,
                'statut' => $l->status->value,
            ])->values(),
            'clients_carte' => $this->payers($venue, $suffix)->map(fn (Payer $p) => [
                'nom' => $p->name,
                'telephone' => $p->phone,
                'email' => $p->email,
            ])->values(),
        ];

        // Le journal ne garde ni le numéro ni les données : seulement l'opération
        $this->log($venue, $actor, 'exported', ['scope' => 'export_donnees_personnelles']);

        return $data;
    }

    /**
     * Anonymise les données identifiantes. Montants, paiements et contrats sont conservés
     * (obligations comptables) ; seuls les champs identifiants sont remplacés.
     */
    public function anonymize(Venue $venue, User $actor, string $phone): int
    {
        $suffix = $this->suffix($phone);
        $count = 0;

        DB::transaction(function () use ($venue, $actor, $suffix, &$count) {
            foreach ($this->bookings($venue, $suffix) as $booking) {
                $booking->update(['client_name' => 'Client anonymisé', 'client_phone' => 'anonyme', 'notes' => null]);
                $count++;
            }

            foreach ($this->guests($venue, $suffix) as $guest) {
                // Nouveau jeton : le QR code déjà imprimé ne fonctionne plus
                $guest->update(['full_name' => 'Invité anonymisé', 'phone' => null, 'token' => Str::random(32)]);
                $count++;
            }

            foreach ($this->leads($venue, $suffix) as $lead) {
                $lead->update(['full_name' => 'Prospect anonymisé', 'phone' => 'anonyme', 'email' => null, 'notes' => null]);
                $count++;
            }

            foreach ($this->payers($venue, $suffix) as $payer) {
                $payer->update([
                    'name' => 'Client anonymisé',
                    'phone' => 'anonyme-'.Str::lower(Str::random(8)),
                    'email' => null,
                ]);
                $count++;
            }

            $this->log($venue, $actor, 'anonymized', ['enregistrements' => $count]);
        });

        return $count;
    }

    private function bookings(Venue $venue, string $suffix)
    {
        return Booking::where('venue_id', $venue->id)->get()
            ->filter(fn ($b) => $this->matches($b->client_phone, $suffix))->values();
    }

    private function guests(Venue $venue, string $suffix)
    {
        return Guest::where('venue_id', $venue->id)->get()
            ->filter(fn ($g) => $this->matches($g->phone, $suffix))->values();
    }

    private function leads(Venue $venue, string $suffix)
    {
        return Lead::where('venue_id', $venue->id)->get()
            ->filter(fn ($l) => $this->matches($l->phone, $suffix))->values();
    }

    private function payers(Venue $venue, string $suffix)
    {
        return Payer::where('venue_id', $venue->id)->get()
            ->filter(fn ($p) => $this->matches($p->phone, $suffix))->values();
    }

    private function suffix(string $phone): string
    {
        $digits = $this->digits($phone);

        if (strlen($digits) < 8) {
            throw new \DomainException('Saisissez au moins 8 chiffres.');
        }

        return substr($digits, -8);
    }

    private function matches(?string $stored, string $suffix): bool
    {
        $digits = $this->digits($stored);

        return $digits !== '' && str_ends_with($digits, $suffix);
    }

    private function digits(?string $value): string
    {
        return preg_replace('/\D/', '', (string) $value);
    }

    private function log(Venue $venue, User $actor, string $action, array $changes): void
    {
        AuditLog::create([
            'venue_id' => $venue->id,
            'user_id' => $actor->id,
            'action' => $action,
            'auditable_type' => 'privacy',
            'auditable_id' => 0,
            'changes' => $changes,
            'ip_address' => request()?->ip(),
        ]);
    }
}