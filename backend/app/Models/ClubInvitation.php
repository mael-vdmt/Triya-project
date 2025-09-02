<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ClubInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'email',
        'token',
        'invited_by',
        'status',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Le club concerné par l'invitation
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * L'utilisateur qui a envoyé l'invitation
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * L'utilisateur invité (si il existe déjà)
     */
    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Générer un token unique pour l'invitation
     */
    public static function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Vérifier si l'invitation est expirée
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Vérifier si l'invitation est valide
     */
    public function isValid(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Accepter l'invitation
     */
    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    /**
     * Marquer l'invitation comme expirée
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }
}
