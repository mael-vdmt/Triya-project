<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'is_public' => $this->is_public,
            'members_count' => $this->when($this->relationLoaded('users'), $this->users->count()),
            'creator' => $this->when($this->relationLoaded('creator'), [
                'id' => $this->creator->id,
                'name' => $this->creator->first_name . ' ' . $this->creator->last_name,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
