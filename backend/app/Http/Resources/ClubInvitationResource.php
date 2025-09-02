<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubInvitationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'club_id' => $this->club_id,
            'email' => $this->email,
            'token' => $this->token,
            'status' => $this->status,
            'expires_at' => $this->expires_at,
            'accepted_at' => $this->accepted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relations
            'club' => new ClubResource($this->whenLoaded('club')),
            'inviter' => new UserResource($this->whenLoaded('inviter')),
            'invited_user' => new UserResource($this->whenLoaded('invitedUser')),
            
            // Métadonnées
            'is_expired' => $this->isExpired(),
            'is_valid' => $this->isValid(),
        ];
    }
}
