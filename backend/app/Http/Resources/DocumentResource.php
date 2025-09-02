<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'formatted_file_size' => $this->formatted_file_size,
            'file_extension' => $this->file_extension,
            'visibility' => $this->visibility,
            'uploader' => $this->when($this->relationLoaded('uploader'), [
                'id' => $this->uploader->id,
                'name' => $this->uploader->first_name . ' ' . $this->uploader->last_name,
            ]),
            'groups' => $this->when($this->relationLoaded('groups'), $this->groups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                ];
            })),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
