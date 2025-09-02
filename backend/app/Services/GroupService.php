<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GroupService implements ServiceInterface
{
    /**
     * Récupérer tous les groupes
     */
    public function getAll()
    {
        return Group::with('users', 'creator')->get();
    }

    /**
     * Récupérer un groupe par ID
     */
    public function find(int $id)
    {
        return Group::with('users', 'creator')->findOrFail($id);
    }

    /**
     * Créer un nouveau groupe
     */
    public function create(array $data)
    {
        $group = Group::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'color' => $data['color'] ?? '#3B82F6',
            'is_public' => $data['is_public'] ?? false,
            'created_by' => $data['created_by'],
        ]);

        return $group->load('users', 'creator');
    }

    /**
     * Mettre à jour un groupe
     */
    public function update(int $id, array $data)
    {
        $group = $this->find($id);
        
        $updateData = array_filter([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'color' => $data['color'] ?? null,
            'is_public' => $data['is_public'] ?? null,
        ], function($value) {
            return $value !== null;
        });

        $group->update($updateData);
        
        return $group->fresh()->load('users', 'creator');
    }

    /**
     * Supprimer un groupe
     */
    public function delete(int $id)
    {
        $group = $this->find($id);
        $group->delete();
        
        return true;
    }

    /**
     * Récupérer les membres d'un groupe
     */
    public function getGroupMembers(int $groupId)
    {
        $group = $this->find($groupId);
        return $group->users()->withPivot('role', 'joined_at')->get();
    }

    /**
     * Ajouter un utilisateur à un groupe
     */
    public function addUser(int $groupId, int $userId, string $role = 'member')
    {
        $group = $this->find($groupId);
        $group->users()->attach($userId, [
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    /**
     * Retirer un utilisateur d'un groupe
     */
    public function removeUser(int $groupId, int $userId)
    {
        $group = $this->find($groupId);
        $group->users()->detach($userId);
    }

    /**
     * Rechercher des groupes par nom ou description
     */
    public function search(string $query)
    {
        return Group::where('name', 'like', "%{$query}%")
                   ->orWhere('description', 'like', "%{$query}%")
                   ->with('users', 'creator')
                   ->get();
    }
}
