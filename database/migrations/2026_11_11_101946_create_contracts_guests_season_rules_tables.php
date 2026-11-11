<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('number', 30)->unique();
            $table->string('status', 20)->default('draft');   // draft | sent | signed
            $table->longText('body');                          // HTML figé à l'émission
            $table->timestamp('sent_at')->nullable();
            $table->string('signed_name', 120)->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_ip', 45)->nullable();
            $table->string('signed_user_agent', 255)->nullable();
            $table->string('document_hash', 64)->nullable();   // SHA-256 du contenu signé
            $table->timestamps();
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('full_name', 120);
            $table->string('phone', 30)->nullable();
            $table->string('token', 40)->unique();              // secret : lien RSVP et QR code
            $table->string('rsvp_status', 20)->default('invited'); // invited | confirmed | declined
            $table->unsignedTinyInteger('companions')->default(0);
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();
            $table->index(['booking_id', 'rsvp_status']);
        });

        Schema::create('season_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);                         // ex : Tabaski 2027
            $table->date('starts_on');
            $table->date('ends_on');
            $table->decimal('percent', 5, 2);                   // positif = majoration, négatif = réduction
            $table->timestamps();
            $table->index(['venue_id', 'starts_on', 'ends_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('season_rules');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('contracts');
    }
};