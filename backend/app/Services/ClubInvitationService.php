<?php

namespace App\Services;

use App\Models\ClubInvitation;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ClubInvitationService implements ServiceInterface
{
    /**
     * Récupérer toutes les invitations
     */
    public function getAll()
    {
        return ClubInvitation::with(['club', 'inviter', 'invitedUser'])->get();
    }

    /**
     * Récupérer une invitation par ID
     */
    public function find(int $id)
    {
        return ClubInvitation::with(['club', 'inviter', 'invitedUser'])->findOrFail($id);
    }

    /**
     * Créer une invitation par email
     */
    public function create(array $data)
    {
        $club = Club::findOrFail($data['club_id']);
        
        // Vérifier que l'utilisateur qui invite est admin du club
        $inviter = User::findOrFail($data['invited_by']);
        if (!$club->isAdmin($inviter)) {
            throw new \Exception('Vous n\'avez pas les droits pour inviter des membres à ce club.');
        }

        // Vérifier si l'utilisateur n'est pas déjà membre
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser && $club->hasUser($existingUser)) {
            throw new \Exception('Cet utilisateur est déjà membre du club.');
        }

        // Vérifier s'il n'y a pas déjà une invitation en cours
        $existingInvitation = ClubInvitation::where('club_id', $data['club_id'])
            ->where('email', $data['email'])
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation && !$existingInvitation->isExpired()) {
            throw new \Exception('Une invitation est déjà en cours pour cet email.');
        }

        $invitation = ClubInvitation::create([
            'club_id' => $data['club_id'],
            'email' => $data['email'],
            'token' => ClubInvitation::generateToken(),
            'invited_by' => $data['invited_by'],
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        return $invitation->load(['club', 'inviter', 'invitedUser']);
    }

    /**
     * Créer un lien d'invitation
     */
    public function createInviteLink(int $clubId, int $invitedBy)
    {
        $club = Club::findOrFail($clubId);
        
        // Vérifier que l'utilisateur qui invite est admin du club
        $inviter = User::findOrFail($invitedBy);
        if (!$club->isAdmin($inviter)) {
            throw new \Exception('Vous n\'avez pas les droits pour créer un lien d\'invitation pour ce club.');
        }

        $invitation = ClubInvitation::create([
            'club_id' => $clubId,
            'email' => null,
            'token' => ClubInvitation::generateToken(),
            'invited_by' => $invitedBy,
            'status' => 'pending',
            'expires_at' => now()->addDays(30),
        ]);

        return $invitation->load(['club', 'inviter']);
    }

    /**
     * Accepter une invitation
     */
    public function acceptInvitation(string $token, ?User $user = null)
    {
        $invitation = ClubInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($invitation->isExpired()) {
            $invitation->markAsExpired();
            throw new \Exception('Cette invitation a expiré.');
        }

        if (!$user) {
            throw new \Exception('Vous devez créer un compte pour accepter cette invitation.');
        }

        // Vérifier que l'email correspond (si l'invitation était par email)
        if ($invitation->email && $invitation->email !== $user->email) {
            throw new \Exception('Cette invitation n\'est pas pour votre adresse email.');
        }

        // Vérifier que l'utilisateur n'est pas déjà membre
        if ($invitation->club->hasUser($user)) {
            throw new \Exception('Vous êtes déjà membre de ce club.');
        }

        // Ajouter l'utilisateur au club
        $invitation->club->users()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        // Marquer l'invitation comme acceptée
        $invitation->accept();

        return $invitation->load(['club', 'inviter']);
    }

    /**
     * Récupérer les invitations d'un club
     */
    public function getClubInvitations(int $clubId)
    {
        return ClubInvitation::where('club_id', $clubId)
            ->with(['inviter', 'invitedUser'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Supprimer une invitation
     */
    public function deleteInvitation(int $id)
    {
        $invitation = $this->find($id);
        $invitation->delete();
        
        return true;
    }

    /**
     * Récupérer une invitation par token
     */
    public function findByToken(string $token)
    {
        return ClubInvitation::where('token', $token)
            ->with(['club', 'inviter'])
            ->firstOrFail();
    }
}
