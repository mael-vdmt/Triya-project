<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Authentifier un utilisateur
     */
    public function authenticate(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        return $user;
    }

    /**
     * Créer un token pour un utilisateur
     */
    public function createToken(User $user, string $name = 'auth-token'): string
    {
        return $user->createToken($name)->plainTextToken;
    }

    /**
     * Révoker tous les tokens d'un utilisateur
     */
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Révoker le token actuel
     */
    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Inscrire un nouvel utilisateur
     */
    public function register(array $data): array
    {
        // Créer l'utilisateur via UserService
        $user = $this->userService->create($data);
        
        // Créer le token
        $token = $this->createToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Connecter un utilisateur
     */
    public function login(string $email, string $password): array
    {
        // Authentifier l'utilisateur
        $user = $this->authenticate($email, $password);
        
        // Créer le token
        $token = $this->createToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnecter un utilisateur
     */
    public function logout(User $user): void
    {
        $this->revokeAllTokens($user);
    }



}
