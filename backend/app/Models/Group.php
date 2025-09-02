<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_public',
        'created_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * L'utilisateur qui a créé le groupe
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Les utilisateurs membres du groupe
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les administrateurs du groupe
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'group_users')
                    ->wherePivot('role', 'admin')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les membres simples du groupe
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_users')
                    ->wherePivot('role', 'member')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les documents accessibles au groupe
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_groups');
    }

    /**
     * Vérifier si un utilisateur est membre du groupe
     */
    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Vérifier si un utilisateur est administrateur du groupe
     */
    public function isAdmin(User $user): bool
    {
        return $this->users()
                    ->where('user_id', $user->id)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    /**
     * Vérifier si le groupe est public
     */
    public function isPublic(): bool
    {
        return $this->is_public;
    }

    /**
     * Vérifier si le groupe est privé
     */
    public function isPrivate(): bool
    {
        return !$this->is_public;
    }


}
