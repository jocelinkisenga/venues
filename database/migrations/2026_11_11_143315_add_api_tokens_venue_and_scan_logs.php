<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un jeton est lié à une salle précise, et non à la salle active de l'utilisateur
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->foreignId('venue_id')->nullable()->after('tokenable_id')->constrained()->cascadeOnDelete();
        });

        // Journal des scans hors ligne : client_id unique rend l'envoi idempotent
        Schema::create('scan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('client_id', 36)->unique();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->string('result', 20);
            $table->dateTime('scanned_at');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_logs');

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropConstrainedForeignId('venue_id');
        });
    }
};