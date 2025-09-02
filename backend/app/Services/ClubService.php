<?php

namespace App\Services;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ClubService implements ServiceInterface
{
    /**
     * Récupérer tous les clubs
     */
    public function getAll()
    {
        return Club::with('users')->get();
    }

    /**
     * Récupérer un club par ID
     */
    public function find(int $id)
    {
        return Club::with('users')->findOrFail($id);
    }

    /**
     * Créer un nouveau club
     */
    public function create(array $data)
    {
        $club = Club::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'location' => $data['location'],
        ]);

        return $club->load('users');
    }

    /**
     * Mettre à jour un club
     */
    public function update(int $id, array $data)
    {
        $club = $this->find($id);
        
        $updateData = array_filter([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'location' => $data['location'] ?? null,
        ], function($value) {
            return $value !== null;
        });

        $club->update($updateData);
        
        return $club->fresh()->load('users');
    }

    /**
     * Supprimer un club
     */
    public function delete(int $id)
    {
        $club = $this->find($id);
        $club->delete();
        
        return true;
    }

    /**
     * Récupérer les membres d'un club
     */
    public function getClubMembers(int $clubId)
    {
        $club = $this->find($clubId);
        return $club->users()->withPivot('role', 'joined_at')->get();
    }

    /**
     * Ajouter un utilisateur à un club
     */
    public function addUser(int $clubId, int $userId, string $role = 'member')
    {
        $club = $this->find($clubId);
        $club->users()->attach($userId, [
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    /**
     * Retirer un utilisateur d'un club
     */
    public function removeUser(int $clubId, int $userId)
    {
        $club = $this->find($clubId);
        $club->users()->detach($userId);
    }

    /**
     * Rechercher des clubs par nom ou localisation
     */
    public function search(string $query)
    {
        return Club::where('name', 'like', "%{$query}%")
                   ->orWhere('location', 'like', "%{$query}%")
                   ->orWhere('description', 'like', "%{$query}%")
                   ->with('users')
                   ->get();
    }

}
