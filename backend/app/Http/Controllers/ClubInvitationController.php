<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClubInvitation\StoreClubInvitationRequest;
use App\Http\Resources\ClubInvitationResource;
use App\Services\ClubInvitationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubInvitationController extends Controller
{
    public function __construct(
        private ClubInvitationService $invitationService
    ) {}

    /**
     * Récupérer les invitations d'un club
     */
    public function index(Request $request, int $clubId): JsonResponse
    {
        $invitations = $this->invitationService->getClubInvitations($clubId);
        
        return response()->json([
            'data' => ClubInvitationResource::collection($invitations)
        ]);
    }

    /**
     * Créer une invitation par email
     */
    public function store(StoreClubInvitationRequest $request, int $clubId): JsonResponse
    {
        $data = $request->validated();
        $data['club_id'] = $clubId;
        $data['invited_by'] = $request->user()->id;

        $invitation = $this->invitationService->create($data);
        
        return response()->json([
            'message' => 'Invitation envoyée avec succès',
            'data' => new ClubInvitationResource($invitation)
        ], 201);
    }

    /**
     * Créer un lien d'invitation
     */
    public function createLink(Request $request, int $clubId): JsonResponse
    {
        $invitation = $this->invitationService->createInviteLink($clubId, $request->user()->id);
        
        $link = url("/invitation/{$invitation->token}");
        
        return response()->json([
            'message' => 'Lien d\'invitation créé avec succès',
            'data' => [
                'invitation_link' => $link,
                'invitation' => new ClubInvitationResource($invitation)
            ]
        ]);
    }

    /**
     * Afficher les détails d'une invitation (pour les liens)
     */
    public function show(string $token): JsonResponse
    {
        $invitation = $this->invitationService->findByToken($token);

        if ($invitation->isExpired()) {
            return response()->json([
                'error' => 'Cette invitation a expiré'
            ], 410);
        }

        return response()->json([
            'data' => new ClubInvitationResource($invitation)
        ]);
    }

    /**
     * Accepter une invitation
     */
    public function accept(Request $request, string $token): JsonResponse
    {
        $user = $request->user();
        $invitation = $this->invitationService->acceptInvitation($token, $user);

        return response()->json([
            'message' => 'Invitation acceptée avec succès',
            'data' => new ClubInvitationResource($invitation)
        ]);
    }

    /**
     * Supprimer une invitation
     */
    public function destroy(Request $request, int $invitationId): JsonResponse
    {
        $this->invitationService->deleteInvitation($invitationId, $request->user()->id);

        return response()->json([
            'message' => 'Invitation supprimée avec succès'
        ]);
    }
}
