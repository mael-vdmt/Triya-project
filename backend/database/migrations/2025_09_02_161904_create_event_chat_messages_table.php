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
        Schema::create('event_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_chat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->timestamp('read_at')->nullable(); // Pour marquer comme lu
            $table->timestamps();
            
            // Index pour optimiser les requêtes par chat et date
            $table->index(['event_chat_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_chat_messages');
    }
};
