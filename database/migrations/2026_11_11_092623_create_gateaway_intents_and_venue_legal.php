<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Transactions initiées chez un agrégateur, en attente de confirmation.
        // Elles ne sont pas des paiements tant que l'API ne confirme pas.
        Schema::create('gateway_intents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id', 40)->unique();
            $table->string('provider', 20);
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3);
            $table->string('kind', 10);                       // deposit | balance
            $table->string('status', 20)->default('pending'); // pending | succeeded | failed | mismatch
            $table->text('payment_url')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        // Mentions légales RDC à afficher sur les devis et reçus
        Schema::table('venues', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->string('address')->nullable()->after('email');
            $table->string('nif', 30)->nullable();      // Numéro d'identification fiscale
            $table->string('rccm', 40)->nullable();     // Registre de commerce
            $table->string('id_nat', 30)->nullable();   // Identifiant national
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn(['email', 'address', 'nif', 'rccm', 'id_nat']);
        });
        Schema::dropIfExists('gateway_intents');
    }
};