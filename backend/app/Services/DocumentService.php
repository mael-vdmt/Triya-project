<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

class DocumentService implements ServiceInterface
{
    /**
     * Récupérer tous les documents
     */
    public function getAll()
    {
        return Document::with('uploader', 'groups', 'users')->get();
    }

    /**
     * Récupérer un document par ID
     */
    public function find(int $id)
    {
        return Document::with('uploader', 'groups', 'users')->findOrFail($id);
    }

    /**
     * Créer un nouveau document
     */
    public function create(array $data)
    {
        $document = Document::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $data['file_path'],
            'file_name' => $data['file_name'],
            'file_type' => $data['file_type'],
            'file_size' => $data['file_size'],
            'uploaded_by' => $data['uploaded_by'],
            'visibility' => $data['visibility'] ?? 'public',
        ]);

        return $document->load('uploader', 'groups', 'users');
    }

    /**
     * Mettre à jour un document
     */
    public function update(int $id, array $data)
    {
        $document = $this->find($id);
        
        $updateData = array_filter([
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'visibility' => $data['visibility'] ?? null,
        ], function($value) {
            return $value !== null;
        });

        $document->update($updateData);
        
        return $document->fresh()->load('uploader', 'groups', 'users');
    }

    /**
     * Supprimer un document
     */
    public function delete(int $id)
    {
        $document = $this->find($id);
        $document->delete();
        
        return true;
    }

    /**
     * Rechercher des documents par titre ou description
     */
    public function search(string $query)
    {
        return Document::where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->with('uploader', 'groups', 'users')
                      ->get();
    }

    /**
     * Récupérer les documents accessibles à un utilisateur
     */
    public function getAccessibleDocuments(int $userId)
    {
        return Document::where('visibility', 'public')
                      ->orWhere('uploaded_by', $userId)
                      ->orWhereHas('users', function($query) use ($userId) {
                          $query->where('user_id', $userId);
                      })
                      ->orWhereHas('groups.users', function($query) use ($userId) {
                          $query->where('user_id', $userId);
                      })
                      ->with('uploader', 'groups', 'users')
                      ->get();
    }
}
