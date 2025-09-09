<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
        ];
    }

    /**
     * Les clubs auxquels l'utilisateur appartient
     */
    public function clubs()
    {
        return $this->belongsToMany(Club::class)
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les clubs dont l'utilisateur est propriétaire
     */
    public function ownedClubs()
    {
        return $this->belongsToMany(Club::class)
                    ->wherePivot('role', 'owner')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les clubs dont l'utilisateur est administrateur
     */
    public function adminClubs()
    {
        return $this->belongsToMany(Club::class)
                    ->wherePivot('role', 'admin')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les groupes auxquels l'utilisateur appartient
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_users')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les groupes dont l'utilisateur est administrateur
     */
    public function adminGroups()
    {
        return $this->belongsToMany(Group::class, 'group_users')
                    ->wherePivot('role', 'admin')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Les groupes créés par l'utilisateur
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    /**
     * Les documents uploadés par l'utilisateur
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Les documents auxquels l'utilisateur a accès individuel
     */
    public function accessibleDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_users');
    }

    /**
     * Vérifier si l'utilisateur appartient à au moins un club
     */
    public function hasClubs(): bool
    {
        return $this->clubs()->exists();
    }

    /**
     * Vérifier si l'utilisateur est propriétaire d'au moins un club
     */
    public function ownsClubs(): bool
    {
        return $this->ownedClubs()->exists();
    }
}
