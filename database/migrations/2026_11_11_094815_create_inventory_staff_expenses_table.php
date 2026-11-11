<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('category', 30)->default('autre');
            $table->decimal('replacement_cost', 12, 2)->default(0);
            $table->timestamps();
        });

        // Le stock n'est jamais stocké directement : il est la somme des mouvements (delta).
        Schema::create('equipment_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20);            // achat, ajustement, sortie, retour, casse
            $table->unsignedInteger('quantity');   // quantité absolue
            $table->integer('delta');              // effet sur le stock en magasin
            $table->string('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['equipment_item_id', 'booking_id']);
        });

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('role', 20);            // technique, menage, service, accueil, securite
            $table->string('phone', 30)->nullable();
            $table->decimal('default_rate', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20);
            $table->decimal('pay_amount', 12, 2)->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['booking_id', 'staff_member_id']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category', 30);
            $table->string('label', 150);
            $table->string('supplier', 120)->nullable();
            $table->decimal('amount', 14, 2);
            $table->date('spent_on');
            $table->timestamps();
            $table->index(['venue_id', 'spent_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('staff_members');
        Schema::dropIfExists('equipment_movements');
        Schema::dropIfExists('equipment_items');
    }
};