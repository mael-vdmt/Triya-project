<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:competition,training,club_life',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'club_id' => 'required|exists:clubs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est requis',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères',
            'description.required' => 'La description est requise',
            'type.required' => 'Le type d\'événement est requis',
            'type.in' => 'Le type doit être compétition, entraînement ou vie du club',
            'start_date.required' => 'La date de début est requise',
            'start_date.date' => 'La date de début doit être une date valide',
            'start_date.after' => 'La date de début doit être dans le futur',
            'end_date.required' => 'La date de fin est requise',
            'end_date.date' => 'La date de fin doit être une date valide',
            'end_date.after' => 'La date de fin doit être après la date de début',
            'location.required' => 'La localisation est requise',
            'location.max' => 'La localisation ne peut pas dépasser 255 caractères',
            'max_participants.integer' => 'Le nombre maximum de participants doit être un nombre entier',
            'max_participants.min' => 'Le nombre maximum de participants doit être au moins 1',
        ];
    }
}