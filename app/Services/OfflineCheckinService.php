<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\ScanLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OfflineCheckinService
{
    /**
     * @param  array<int, array{client_id: string, token: string, scanned_at: string}>  $scans
     * @return array<string, string>  client_id => résultat
     */
    public function apply(Booking $booking, array $scans): array
    {
        $results = [];

        foreach ($scans as $scan) {
            $results[$scan['client_id']] = DB::transaction(function () use ($booking, $scan) {
                $previous = ScanLog::where('client_id', $scan['client_id'])->first();

                if ($previous) {
                    return $previous->result;
                }

                $guest = Guest::where('booking_id', $booking->id)
                    ->where('token', $scan['token'])
                    ->lockForUpdate()
                    ->first();

                // On ne fait jamais confiance à une heure dans le futur
                $scannedAt = min(Carbon::parse($scan['scanned_at']), now());

                if (! $guest) {
                    $result = 'unknown';
                } elseif ($guest->rsvp_status !== 'confirmed') {
                    $result = 'not_confirmed';
                } elseif ($guest->attended_at) {
                    $result = 'already_admitted';
                } else {
                    $guest->update(['attended_at' => $scannedAt]);
                    $result = 'admitted';
                }

                ScanLog::create([
                    'venue_id' => $booking->venue_id,
                    'client_id' => $scan['client_id'],
                    'guest_id' => $guest?->id,
                    'result' => $result,
                    'scanned_at' => $scannedAt,
                ]);

                return $result;
            });
        }

        return $results;
    }
}