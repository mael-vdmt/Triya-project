<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    protected $table = 'group_users';

    protected $fillable = [
        'group_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * Le groupe associé
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * L'utilisateur associé
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si l'utilisateur est administrateur du groupe
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est membre simple du groupe
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}
