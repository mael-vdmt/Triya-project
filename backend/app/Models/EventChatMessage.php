<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_chat_id',
        'user_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Le chat auquel appartient le message
     */
    public function eventChat()
    {
        return $this->belongsTo(EventChat::class);
    }

    /**
     * L'utilisateur qui a envoyé le message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * L'événement associé au message (via le chat)
     */
    public function event()
    {
        return $this->hasOneThrough(Event::class, EventChat::class, 'id', 'id', 'event_chat_id', 'event_id');
    }

    /**
     * Vérifier si le message a été lu
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Marquer le message comme lu
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Marquer le message comme non lu
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Scope pour les messages non lus
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope pour les messages lus
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
