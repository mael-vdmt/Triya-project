<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCarpooling extends Model
{
    use HasFactory;

    protected $table = 'event_carpooling';

    protected $fillable = [
        'event_id',
        'user_id',
        'available_seats',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}