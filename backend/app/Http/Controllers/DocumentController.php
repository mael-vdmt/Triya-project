<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct(
        private DocumentService $documentService
    ) {}

    /**
     * Récupérer tous les documents
     */
    public function index(): JsonResponse
    {
        $documents = $this->documentService->getAll();
        
        return response()->json([
            'data' => DocumentResource::collection($documents)
        ]);
    }

    /**
     * Créer un nouveau document
     */
    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');
        
        $data = $request->validated();
        $data['uploaded_by'] = $request->user()->id;
        $data['file_path'] = $filePath;
        $data['file_name'] = $file->getClientOriginalName();
        $data['file_type'] = $file->getMimeType();
        $data['file_size'] = $file->getSize();
        
        $document = $this->documentService->create($data);
        
        // Gérer les permissions si spécifiées
        if ($request->has('group_ids')) {
            $document->groups()->attach($request->group_ids);
        }
        
        if ($request->has('user_ids')) {
            $document->users()->attach($request->user_ids);
        }

        return response()->json([
            'message' => 'Document uploadé avec succès',
            'data' => new DocumentResource($document)
        ], 201);
    }

    /**
     * Récupérer un document par ID
     */
    public function show(int $id): JsonResponse
    {
        $document = $this->documentService->find($id);
        
        return response()->json([
            'data' => new DocumentResource($document)
        ]);
    }

    /**
     * Mettre à jour un document
     */
    public function update(UpdateDocumentRequest $request, int $id): JsonResponse
    {
        $document = $this->documentService->update($id, $request->validated());
        
        // Gérer les permissions si spécifiées
        if ($request->has('group_ids')) {
            $document->groups()->sync($request->group_ids);
        }
        
        if ($request->has('user_ids')) {
            $document->users()->sync($request->user_ids);
        }
        
        return response()->json([
            'message' => 'Document mis à jour avec succès',
            'data' => new DocumentResource($document)
        ]);
    }

    /**
     * Supprimer un document
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $document = $this->documentService->find($id);
        
        // Supprimer le fichier du stockage
        Storage::disk('public')->delete($document->file_path);
        
        $this->documentService->delete($id);
        
        return response()->json([
            'message' => 'Document supprimé avec succès'
        ]);
    }

    /**
     * Rechercher des documents
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'data' => []
            ]);
        }

        $documents = $this->documentService->search($query);
        
        return response()->json([
            'data' => DocumentResource::collection($documents)
        ]);
    }

    /**
     * Récupérer les documents accessibles à l'utilisateur
     */
    public function accessible(Request $request): JsonResponse
    {
        $documents = $this->documentService->getAccessibleDocuments($request->user()->id);
        
        return response()->json([
            'data' => DocumentResource::collection($documents)
        ]);
    }

    /**
     * Télécharger un document
     */
    public function download(int $id): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $document = $this->documentService->find($id);
        
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
