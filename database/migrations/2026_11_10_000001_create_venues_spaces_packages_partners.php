<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city', 80)->nullable();
            $table->string('country', 2)->default('CI');
            $table->string('currency', 3)->default('XOF');
            $table->string('timezone', 50)->default('Africa/Abidjan');
            $table->string('phone', 30)->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->unsignedTinyInteger('deposit_percent')->default(50);
            $table->timestamps();
        });

        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('zone', 30)->default('grande_salle'); // grande_salle, terrasse, vip, bar
            $table->unsignedInteger('capacity');
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type', 20)->default('seche');          // seche | cle_en_main
            $table->string('time_type', 20)->default('soiree');    // soiree | multi_jours
            $table->unsignedTinyInteger('days')->default(1);
            $table->unsignedTinyInteger('included_hours')->default(6);
            $table->decimal('base_price', 14, 2);
            $table->decimal('overtime_rate', 12, 2)->default(0);   // par heure supplémentaire
            $table->json('includes')->nullable();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category', 30); // traiteur, decoration, boissons, autre
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->string('phone', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('spaces');
        Schema::dropIfExists('venues');
    }
};
