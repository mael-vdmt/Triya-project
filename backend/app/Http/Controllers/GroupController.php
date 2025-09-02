<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(
        private GroupService $groupService
    ) {}

    /**
     * Récupérer tous les groupes
     */
    public function index(): JsonResponse
    {
        $groups = $this->groupService->getAll();
        
        return response()->json([
            'data' => GroupResource::collection($groups)
        ]);
    }

    /**
     * Créer un nouveau groupe
     */
    public function store(StoreGroupRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        
        $group = $this->groupService->create($data);
        
        // Ajouter l'utilisateur créateur comme administrateur
        $this->groupService->addUser($group->id, $request->user()->id, 'admin');

        return response()->json([
            'message' => 'Groupe créé avec succès',
            'data' => new GroupResource($group)
        ], 201);
    }

    /**
     * Récupérer un groupe par ID
     */
    public function show(int $id): JsonResponse
    {
        $group = $this->groupService->find($id);
        
        return response()->json([
            'data' => new GroupResource($group)
        ]);
    }

    /**
     * Mettre à jour un groupe
     */
    public function update(UpdateGroupRequest $request, int $id): JsonResponse
    {
        $group = $this->groupService->update($id, $request->validated());
        
        return response()->json([
            'message' => 'Groupe mis à jour avec succès',
            'data' => new GroupResource($group)
        ]);
    }

    /**
     * Supprimer un groupe
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->groupService->delete($id);
        
        return response()->json([
            'message' => 'Groupe supprimé avec succès'
        ]);
    }

    /**
     * Rechercher des groupes
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'data' => []
            ]);
        }

        $groups = $this->groupService->search($query);
        
        return response()->json([
            'data' => GroupResource::collection($groups)
        ]);
    }

    /**
     * Récupérer les membres d'un groupe
     */
    public function members(int $id): JsonResponse
    {
        $members = $this->groupService->getGroupMembers($id);
        
        return response()->json([
            'data' => $members
        ]);
    }

    /**
     * Ajouter un membre à un groupe
     */
    public function addMember(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'sometimes|string|in:member,admin'
        ]);

        $this->groupService->addUser($id, $request->user_id, $request->role ?? 'member');
        
        return response()->json([
            'message' => 'Membre ajouté au groupe avec succès'
        ]);
    }

    /**
     * Retirer un membre d'un groupe
     */
    public function removeMember(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $this->groupService->removeUser($id, $request->user_id);
        
        return response()->json([
            'message' => 'Membre retiré du groupe avec succès'
        ]);
    }
}
