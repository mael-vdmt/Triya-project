<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'created_by',
        'title',
        'description',
        'type',
        'status',
        'start_date',
        'end_date',
        'location',
        'max_participants',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Le club organisateur de l'événement
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * L'utilisateur qui a créé l'événement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Les formats de l'événement
     */
    public function formats()
    {
        return $this->hasMany(EventFormat::class);
    }

    /**
     * Les inscriptions à l'événement
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Les utilisateurs inscrits à l'événement
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->withPivot('status', 'registered_at')
                    ->withTimestamps();
    }

    /**
     * Les offres de covoiturage pour l'événement
     */
    public function carpooling()
    {
        return $this->hasMany(EventCarpooling::class);
    }

    /**
     * Les offres d'hébergement pour l'événement
     */
    public function accommodation()
    {
        return $this->hasMany(EventAccommodation::class);
    }

    /**
     * Le chat de l'événement
     */
    public function chat()
    {
        return $this->hasOne(EventChat::class);
    }

    /**
     * Vérifier si l'événement est approuvé
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Vérifier si l'événement est en attente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si l'événement est rejeté
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}