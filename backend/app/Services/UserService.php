<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements ServiceInterface
{
    /**
     * Récupérer tous les utilisateurs
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(int $id, array $data)
    {
        $user = $this->find($id);
        
        $updateData = array_filter([
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ], function($value) {
            return $value !== null;
        });

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        
        return $user->fresh();
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id)
    {
        $user = $this->find($id);
        $user->delete();
        
        return true;
    }

    /**
     * Récupérer les clubs d'un utilisateur
     */
    public function getUserClubs(int $userId)
    {
        $user = $this->find($userId);
        return $user->clubs()->withPivot('role', 'joined_at')->get();
    }




}
