<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'uploaded_by',
        'visibility',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * L'utilisateur qui a uploadé le document
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Les groupes qui ont accès au document
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'document_groups');
    }

    /**
     * Les utilisateurs qui ont accès individuel au document
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'document_users');
    }

    /**
     * Vérifier si le document est public
     */
    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    /**
     * Vérifier si le document est privé
     */
    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }

    /**
     * Vérifier si le document est spécifique à des groupes
     */
    public function isGroupSpecific(): bool
    {
        return $this->visibility === 'group_specific';
    }

    /**
     * Vérifier si un utilisateur a accès au document
     */
    public function hasAccess(User $user): bool
    {
        // Si le document est public, tout le monde y a accès
        if ($this->isPublic()) {
            return true;
        }

        // Si l'utilisateur est l'uploader, il a accès
        if ($this->uploaded_by === $user->id) {
            return true;
        }

        // Si le document est privé, seul l'uploader y a accès
        if ($this->isPrivate()) {
            return false;
        }

        // Si le document est spécifique aux groupes, vérifier l'appartenance
        if ($this->isGroupSpecific()) {
            // Vérifier si l'utilisateur a un accès individuel
            if ($this->users()->where('user_id', $user->id)->exists()) {
                return true;
            }

            // Vérifier si l'utilisateur appartient à un groupe ayant accès
            $userGroups = $user->groups()->pluck('groups.id');
            return $this->groups()->whereIn('groups.id', $userGroups)->exists();
        }

        return false;
    }

    /**
     * Obtenir la taille formatée du fichier
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtenir l'extension du fichier
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }
}
