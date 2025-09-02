<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function formatRegistrations()
    {
        return $this->hasMany(EventFormatRegistration::class);
    }
}