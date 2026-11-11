<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('full_name', 120);
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('event_type', 60)->nullable();
            $table->unsignedInteger('expected_guests')->nullable();
            $table->date('event_date')->nullable();
            $table->string('source', 60)->nullable();      // WhatsApp, bouche-à-oreille, Facebook...
            $table->string('status', 20)->default('new');
            $table->string('lost_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['venue_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
