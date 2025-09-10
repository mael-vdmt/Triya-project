<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'max_participants' => $this->max_participants,
            'club' => [
                'id' => $this->club->id,
                'name' => $this->club->name,
            ],
            'creator' => [
                'id' => $this->creator->id,
                'first_name' => $this->creator->first_name,
                'last_name' => $this->creator->last_name,
            ],
            'formats' => EventFormatResource::collection($this->whenLoaded('formats')),
            'registrations_count' => $this->when(isset($this->registrations_count), $this->registrations_count),
            'registered_users' => $this->when($this->relationLoaded('users'), function () {
                return $this->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'status' => $user->pivot->status,
                        'registered_at' => $user->pivot->registered_at,
                    ];
                });
            }),
            'carpooling_count' => $this->when(isset($this->carpooling_count), $this->carpooling_count),
            'accommodation_count' => $this->when(isset($this->accommodation_count), $this->accommodation_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}