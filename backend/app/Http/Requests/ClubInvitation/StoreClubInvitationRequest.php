<?php

namespace App\Http\Requests\ClubInvitation;

use Illuminate\Foundation\Http\FormRequest;

class StoreClubInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => 'required|integer|exists:clubs,id',
            'email' => 'required|email|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'club_id.required' => 'L\'ID du club est requis.',
            'club_id.exists' => 'Le club spécifié n\'existe pas.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
        ];
    }
}
