<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\GatewayIntent;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CinetPayGateway
{
    private const API = 'https://api-checkout.cinetpay.com/v2/payment';

    /** Crée la transaction chez CinetPay et renvoie l'URL de paiement Mobile Money. */
    public function initiate(Booking $booking, float $amount, string $kind, string $label): string
    {
        $venue = $booking->venue;
        $transactionId = 'EV'.now()->format('ymdHis').Str::upper(Str::random(4)); // 18 caractères

        [$first, $last] = array_pad(explode(' ', trim($booking->client_name), 2), 2, '-');

        $intent = GatewayIntent::create([
            'booking_id' => $booking->id,
            'transaction_id' => $transactionId,
            'provider' => 'cinetpay',
            'amount' => $amount,
            'currency' => $venue->currency,
            'kind' => $kind,
            'status' => 'pending',
        ]);

        $city = $venue->city ?? 'Kinshasa';

        $response = Http::acceptJson()->timeout(15)->post(self::API, [
            'apikey' => config('services.cinetpay.apikey'),
            'site_id' => config('services.cinetpay.site_id'),
            'transaction_id' => $transactionId,
            'amount' => $this->amountUnits($amount, $venue->currency),
            'currency' => $venue->currency,
            'description' => Str::limit("{$label} · {$booking->reference}", 100),
            'notify_url' => route('cinetpay.notify'),
            'return_url' => route('portal.thanks'),
            'channels' => 'MOBILE_MONEY',
            'lang' => 'fr',
            'customer_name' => $first,
            'customer_surname' => $last,
            'customer_phone_number' => self::normalizePhone($booking->client_phone),
            'customer_email' => config('mail.from.address'),
            'customer_address' => $city,
            'customer_city' => $city,
            'customer_country' => 'CD',
            'metadata' => (string) $intent->id,
        ]);

        if (! $response->successful() || (string) $response->json('code') !== '201') {
            $intent->update(['status' => 'failed']);

            throw new \RuntimeException('Initiation CinetPay refusée : '.$response->body());
        }

        $url = $response->json('data.payment_url');
        $intent->update(['payment_url' => $url]);

        return $url;
    }

    /**
     * Confirme une transaction en interrogeant CinetPay directement.
     * Appelé par le webhook et par la commande de rattrapage : le contenu reçu n'est jamais cru tel quel.
     */
    public function sync(string $transactionId): void
    {
        $intent = GatewayIntent::with('booking.venue')->where('transaction_id', $transactionId)->first();

        if (! $intent || $intent->status !== 'pending') {
            return;
        }

        $data = $this->check($transactionId);
        $status = $data['status'] ?? null;

        if ($status === 'REFUSED') {
            $intent->update(['status' => 'failed']);

            return;
        }

        if ($status !== 'ACCEPTED') {
            return; // encore en attente côté opérateur
        }

        if ((float) ($data['amount'] ?? 0) < (float) $intent->amount) {
            $intent->update(['status' => 'mismatch']);
            logger()->warning('CinetPay : montant confirmé inférieur au montant attendu', [
                'transaction_id' => $transactionId,
                'expected' => (float) $intent->amount,
                'received' => $data['amount'] ?? null,
            ]);

            return;
        }

        DB::transaction(function () use ($intent) {
            // reference unique : un même paiement ne peut être enregistré deux fois
            Payment::firstOrCreate(
                ['reference' => $intent->transaction_id],
                [
                    'booking_id' => $intent->booking_id,
                    'amount' => $intent->amount,
                    'kind' => $intent->kind,
                    'method' => 'mobile_money',
                    'provider' => 'cinetpay',
                    'status' => 'succeeded',
                    'paid_at' => now(),
                ],
            );

            $intent->update(['status' => 'succeeded']);

            app(PaymentReconciler::class)->reconcile($intent->booking);
        });
    }

    /** @return array<string, mixed> */
    public function check(string $transactionId): array
    {
        $response = Http::acceptJson()->timeout(15)->post(self::API.'/check', [
            'apikey' => config('services.cinetpay.apikey'),
            'site_id' => config('services.cinetpay.site_id'),
            'transaction_id' => $transactionId,
        ]);

        return $response->successful() ? (array) $response->json('data', []) : [];
    }

    /** Ramène le numéro au format international sans symboles : 0812345678 → 243812345678. */
    public static function normalizePhone(string $raw): string
    {
        $digits = preg_replace('/\D/', '', $raw);

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = '243'.substr($digits, 1);
        }

        return $digits;
    }

    private function amountUnits(float $amount, string $currency): int|float
    {
        $decimals = config("venue.currencies.{$currency}.decimals", 0);

        return $decimals === 0 ? (int) round($amount) : round($amount, $decimals);
    }
}