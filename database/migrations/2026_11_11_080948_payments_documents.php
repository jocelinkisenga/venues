<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider', 20)->nullable()->after('method');   // stripe | null (manuel)
            $table->string('status', 20)->default('succeeded')->after('provider'); // succeeded | pending
            $table->unique('reference'); // idempotence des webhooks
        });

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('status', 20)->default('held'); // held | released | retained
            $table->decimal('retained_amount', 14, 2)->default(0);
            $table->string('reason')->nullable();
            $table->foreignId('settled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason');
            $table->unsignedTinyInteger('refund_percent');
            $table->decimal('paid_amount', 14, 2);
            $table->decimal('refund_amount', 14, 2);
            $table->string('mode', 20); // refund | credit_note | none
            $table->timestamps();
        });

        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('number', 30)->unique();
            $table->decimal('amount', 14, 2);
            $table->string('reason');
            $table->timestamps();
        });

        Schema::create('document_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('type', 10);
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_number')->default(0);
            $table->unique(['venue_id', 'type', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_sequences');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('cancellations');
        Schema::dropIfExists('deposits');

        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['reference']);
            $table->dropColumn(['provider', 'status']);
        });
    }
};