<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
    ];

    /**
     * Les utilisateurs membres du club
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les propriétaires du club
     */
    public function owners()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'owner')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les administrateurs du club
     */
    public function admins()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'admin')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les membres simples du club
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'member')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Vérifier si un utilisateur est membre du club
     */
    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Vérifier si un utilisateur est propriétaire du club
     */
    public function isOwner(User $user): bool
    {
        return $this->users()
                    ->where('user_id', $user->id)
                    ->wherePivot('role', 'owner')
                    ->exists();
    }

    /**
     * Vérifier si un utilisateur est administrateur du club (admin ou owner)
     */
    public function isAdmin(User $user): bool
    {
        return $this->users()
                    ->where('user_id', $user->id)
                    ->whereIn('role', ['admin', 'owner'])
                    ->exists();
    }
}
