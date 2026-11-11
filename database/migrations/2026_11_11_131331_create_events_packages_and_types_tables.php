<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->timestamps();
            $table->unique(['venue_id', 'name']);
        });

        // Un forfait sans lien vers un espace est proposé pour tous les espaces
        Schema::create('package_space', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('space_id')->constrained()->cascadeOnDelete();
            $table->unique(['package_id', 'space_id']);
        });

        // Reprise : chaque salle existante reçoit les types d'événements par défaut
        $now = now();

        foreach (DB::table('venues')->pluck('id') as $venueId) {
            foreach (config('venue.event_types', []) as $name) {
                DB::table('event_types')->insertOrIgnore([
                    'venue_id' => $venueId,
                    'name' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('package_space');
        Schema::dropIfExists('event_types');
    }
};