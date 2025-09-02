<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_format_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_format_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Éviter les doublons
            $table->unique(['event_registration_id', 'event_format_id'], 'efr_registration_format_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_format_registrations');
    }
};