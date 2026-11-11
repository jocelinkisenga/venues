<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 150)->unique();
            $table->foreignId('owner_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Un utilisateur peut appartenir à plusieurs salles, avec un rôle par salle
        Schema::create('venue_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20);
            $table->timestamps();
            $table->unique(['venue_id', 'user_id']);
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->timestamp('suspended_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false);
        });

        // Reprise des données existantes : une organisation par propriétaire, une adhésion par utilisateur
        foreach (DB::table('venues')->get() as $venue) {
            $owner = DB::table('users')->where('venue_id', $venue->id)->where('role', 'proprietaire')->first()
                ?? DB::table('users')->where('venue_id', $venue->id)->first();

            $organizationId = null;

            if ($owner) {
                $organizationId = DB::table('organizations')->where('owner_id', $owner->id)->value('id')
                    ?? DB::table('organizations')->insertGetId([
                        'name' => $venue->name,
                        'slug' => Str::slug($venue->name).'-'.$owner->id,
                        'owner_id' => $owner->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            DB::table('venues')->where('id', $venue->id)->update(['organization_id' => $organizationId]);

            foreach (DB::table('users')->where('venue_id', $venue->id)->get() as $user) {
                DB::table('venue_user')->insertOrIgnore([
                    'venue_id' => $venue->id,
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_super_admin'));

        Schema::table('venues', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
            $table->dropColumn('suspended_at');
        });

        Schema::dropIfExists('venue_user');
        Schema::dropIfExists('organizations');
    }
};