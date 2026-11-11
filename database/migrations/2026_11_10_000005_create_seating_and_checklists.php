<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seating_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('layout', 20);                  // banquet, u, theatre, cocktail
            $table->string('label', 20);
            $table->string('shape', 15)->default('round'); // round, rectangle
            $table->unsignedSmallInteger('seats');
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->boolean('is_vip')->default(false);
            $table->timestamps();
        });

        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('phase', 10);                   // entry | exit
            $table->json('items');
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['booking_id', 'phase']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklists');
        Schema::dropIfExists('seating_tables');
    }
};
