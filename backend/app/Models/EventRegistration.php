<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    /**
     * L'événement pour lequel l'utilisateur s'est inscrit
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * L'utilisateur qui s'est inscrit à l'événement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les inscriptions aux formats spécifiques de cet événement
     */
    public function formatRegistrations()
    {
        return $this->hasMany(EventFormatRegistration::class);
    }
}