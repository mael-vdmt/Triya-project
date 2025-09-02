<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAccommodation extends Model
{
    use HasFactory;

    protected $table = 'event_accommodation';

    protected $fillable = [
        'event_id',
        'user_id',
        'available_places',
    ];

    /**
     * L'événement pour lequel l'hébergement est proposé
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * L'utilisateur qui propose l'hébergement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}