<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('slug', 160)->nullable()->unique()->after('name');
            $table->text('description')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->json('amenities')->nullable();
            $table->boolean('is_listed')->default(false)->index();
        });

        Schema::create('venue_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
            $table->index(['venue_id', 'position']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('source', 20)->default('internal')->index(); // internal | online
            $table->string('client_email')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['source', 'client_email']);
        });

        Schema::dropIfExists('venue_photos');

        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'contact_phone', 'latitude', 'longitude', 'amenities', 'is_listed']);
        });
    }
};