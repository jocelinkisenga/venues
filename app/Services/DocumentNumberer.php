<?php

namespace App\Services;

use App\Models\Venue;
use Illuminate\Support\Facades\DB;

/** Produit des numéros séquentiels par salle, type et année : AVO-2026-000001. */
class DocumentNumberer
{
    public function next(Venue $venue, string $type): string
    {
        $year = (int) now()->format('Y');

        return DB::transaction(function () use ($venue, $type, $year) {
            DB::table('document_sequences')->insertOrIgnore([
                'venue_id' => $venue->id, 'type' => $type, 'year' => $year, 'last_number' => 0,
            ]);

            $row = DB::table('document_sequences')
                ->where('venue_id', $venue->id)
                ->where('type', $type)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            $next = $row->last_number + 1;

            DB::table('document_sequences')->where('id', $row->id)->update(['last_number' => $next]);

            return sprintf('%s-%d-%06d', $type, $year, $next);
        });
    }
}