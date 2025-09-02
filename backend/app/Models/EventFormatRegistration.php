<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFormatRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_registration_id',
        'event_format_id',
    ];

    /**
     * L'inscription à l'événement associée
     */
    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }

    /**
     * Le format d'événement choisi
     */
    public function eventFormat()
    {
        return $this->belongsTo(EventFormat::class);
    }
}