<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Récupérer tous les utilisateurs
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();
        return response()->json([
            'data' => UserResource::collection($users)
        ]);
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->find($id);
        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->userService->create($request->all());
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->update($id, $request->all());
        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);
        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    /**
     * Récupérer l'utilisateur authentifié
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'data' => new UserResource($user),
            'has_clubs' => $user->hasClubs(),
            'owns_clubs' => $user->ownsClubs()
        ]);
    }
}