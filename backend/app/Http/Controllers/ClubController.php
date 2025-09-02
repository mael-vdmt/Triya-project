<?php

namespace App\Http\Controllers;

use App\Http\Requests\Club\StoreClubRequest;
use App\Http\Requests\Club\UpdateClubRequest;
use App\Http\Resources\ClubResource;
use App\Services\ClubService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function __construct(
        private ClubService $clubService
    ) {}

    /**
     * Récupérer tous les clubs
     */
    public function index(): JsonResponse
    {
        $clubs = $this->clubService->getAll();
        
        return response()->json([
            'data' => ClubResource::collection($clubs)
        ]);
    }

    /**
     * Créer un nouveau club
     */
    public function store(StoreClubRequest $request): JsonResponse
    {
        $club = $this->clubService->create($request->validated());
        
        // Ajouter l'utilisateur créateur comme propriétaire
        $this->clubService->addUser($club->id, $request->user()->id, 'owner');

        return response()->json([
            'message' => 'Club créé avec succès',
            'data' => new ClubResource($club)
        ], 201);
    }

    /**
     * Récupérer un club par ID
     */
    public function show(int $id): JsonResponse
    {
        $club = $this->clubService->find($id);
        
        return response()->json([
            'data' => new ClubResource($club)
        ]);
    }

    /**
     * Mettre à jour un club
     */
    public function update(UpdateClubRequest $request, int $id): JsonResponse
    {
        $club = $this->clubService->update($id, $request->validated());
        
        return response()->json([
            'message' => 'Club mis à jour avec succès',
            'data' => new ClubResource($club)
        ]);
    }

    /**
     * Supprimer un club
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->clubService->delete($id);
        
        return response()->json([
            'message' => 'Club supprimé avec succès'
        ]);
    }

    /**
     * Rechercher des clubs
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'data' => []
            ]);
        }

        $clubs = $this->clubService->search($query);
        
        return response()->json([
            'data' => ClubResource::collection($clubs)
        ]);
    }

    /**
     * Récupérer les membres d'un club
     */
    public function members(int $id): JsonResponse
    {
        $members = $this->clubService->getClubMembers($id);
        
        return response()->json([
            'data' => $members
        ]);
    }

    /**
     * Ajouter un membre à un club
     */
    public function addMember(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'sometimes|string|in:member,admin'
        ]);

        $this->clubService->addUser($id, $request->user_id, $request->role ?? 'member');
        
        return response()->json([
            'message' => 'Membre ajouté au club avec succès'
        ]);
    }

    /**
     * Retirer un membre d'un club
     */
    public function removeMember(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $this->clubService->removeUser($id, $request->user_id);
        
        return response()->json([
            'message' => 'Membre retiré du club avec succès'
        ]);
    }

}
