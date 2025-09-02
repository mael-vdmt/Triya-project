<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:10240', // 10MB max
            'visibility' => 'required|string|in:public,private,group_specific',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'integer|exists:groups,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du document est requis',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères',
            'file.required' => 'Le fichier est requis',
            'file.max' => 'Le fichier ne peut pas dépasser 10MB',
            'visibility.required' => 'La visibilité est requise',
            'visibility.in' => 'La visibilité doit être public, private ou group_specific',
        ];
    }
}
