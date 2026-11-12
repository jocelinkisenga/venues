<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floor_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->unsignedInteger('width')->default(1200);
            $table->unsignedInteger('height')->default(800);
            $table->unsignedInteger('revision')->default(1);   // incrémenté à chaque modification
            $table->timestamp('published_at')->nullable();      // plan validé par l'équipe
            $table->timestamps();
        });

        Schema::create('floor_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_plan_id')->constrained()->cascadeOnDelete();
            $table->string('name', 40);
            $table->string('shape', 15)->default('round');
            $table->string('category', 20)->default('standard');
            $table->unsignedSmallInteger('seats')->default(8);
            $table->unsignedInteger('x')->default(0);
            $table->unsignedInteger('y')->default(0);
            $table->unsignedSmallInteger('width')->default(120);
            $table->unsignedSmallInteger('height')->default(120);
            $table->timestamps();
            $table->unique(['floor_plan_id', 'name']);
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->foreignId('floor_table_id')->nullable()->after('booking_id')
                ->constrained('floor_tables')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('floor_table_id');
        });

        Schema::dropIfExists('floor_tables');
        Schema::dropIfExists('floor_plans');
    }
};