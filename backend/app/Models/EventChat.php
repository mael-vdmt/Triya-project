<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'is_active',
    ];

    /**
     * L'événement associé au chat
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Les messages du chat
     */
    public function messages()
    {
        return $this->hasMany(EventChatMessage::class)->orderBy('created_at', 'desc');
    }

    /**
     * Les messages récents du chat (derniers 50)
     */
    public function recentMessages()
    {
        return $this->hasMany(EventChatMessage::class)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(50);
    }

    /**
     * Vérifier si le chat est actif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activer le chat
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver le chat
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Obtenir le dernier message du chat
     */
    public function lastMessage()
    {
        return $this->hasOne(EventChatMessage::class)->latest();
    }
}
