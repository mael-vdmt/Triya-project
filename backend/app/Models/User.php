<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
}
