<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService
    ) {}

    /**
     * Récupérer tous les événements
     */
    public function index(): JsonResponse
    {
        $events = $this->eventService->getAll();
        
        return response()->json([
            'data' => EventResource::collection($events)
        ]);
    }

    /**
     * Créer un nouvel événement
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        
        $event = $this->eventService->create($data);

        return response()->json([
            'message' => 'Événement créé avec succès',
            'data' => new EventResource($event)
        ], 201);
    }

    /**
     * Récupérer un événement par ID
     */
    public function show(int $id): JsonResponse
    {
        $event = $this->eventService->find($id);
        
        return response()->json([
            'data' => new EventResource($event)
        ]);
    }

    /**
     * Mettre à jour un événement
     */
    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->update($id, $request->validated());
        
        return response()->json([
            'message' => 'Événement mis à jour avec succès',
            'data' => new EventResource($event)
        ]);
    }

    /**
     * Supprimer un événement
     */
    public function destroy(int $id): JsonResponse
    {
        $this->eventService->delete($id);
        
        return response()->json([
            'message' => 'Événement supprimé avec succès'
        ]);
    }

    /**
     * Rechercher des événements
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'data' => []
            ]);
        }

        $events = $this->eventService->search($query);
        
        return response()->json([
            'data' => EventResource::collection($events)
        ]);
    }

    /**
     * Récupérer les événements d'un club
     */
    public function clubEvents(int $clubId): JsonResponse
    {
        $events = $this->eventService->getClubEvents($clubId);
        
        return response()->json([
            'data' => EventResource::collection($events)
        ]);
    }

    /**
     * Approuver un événement
     */
    public function approve(int $id): JsonResponse
    {
        $event = $this->eventService->approve($id);
        
        return response()->json([
            'message' => 'Événement approuvé avec succès',
            'data' => new EventResource($event)
        ]);
    }

    /**
     * Rejeter un événement
     */
    public function reject(int $id): JsonResponse
    {
        $event = $this->eventService->reject($id);
        
        return response()->json([
            'message' => 'Événement rejeté avec succès',
            'data' => new EventResource($event)
        ]);
    }

    /**
     * Ajouter un format à un événement
     */
    public function addFormat(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $format = $this->eventService->addFormat($id, $request->all());
        
        return response()->json([
            'message' => 'Format ajouté avec succès',
            'data' => $format
        ], 201);
    }

    /**
     * Supprimer un format d'un événement
     */
    public function removeFormat(int $formatId): JsonResponse
    {
        $this->eventService->removeFormat($formatId);
        
        return response()->json([
            'message' => 'Format supprimé avec succès'
        ]);
    }

    /**
     * Inscrire un utilisateur à un événement
     */
    public function register(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'sometimes|in:registered,interested'
        ]);

        $this->eventService->registerUser($id, $request->user()->id, $request->get('status', 'registered'));
        
        return response()->json([
            'message' => 'Inscription réussie'
        ]);
    }

    /**
     * Désinscrire un utilisateur d'un événement
     */
    public function unregister(Request $request, int $id): JsonResponse
    {
        $this->eventService->unregisterUser($id, $request->user()->id);
        
        return response()->json([
            'message' => 'Désinscription réussie'
        ]);
    }

    /**
     * Ajouter du covoiturage
     */
    public function addCarpooling(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'available_seats' => 'required|integer|min:1'
        ]);

        $carpooling = $this->eventService->addCarpooling($id, $request->user()->id, $request->available_seats);
        
        return response()->json([
            'message' => 'Covoiturage ajouté avec succès',
            'data' => $carpooling
        ], 201);
    }

    /**
     * Ajouter de l'hébergement
     */
    public function addAccommodation(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'available_places' => 'required|integer|min:1'
        ]);

        $accommodation = $this->eventService->addAccommodation($id, $request->user()->id, $request->available_places);
        
        return response()->json([
            'message' => 'Hébergement ajouté avec succès',
            'data' => $accommodation
        ], 201);
    }
}