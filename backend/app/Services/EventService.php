<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventFormat;
use App\Models\EventRegistration;
use App\Models\EventCarpooling;
use App\Models\EventAccommodation;
use Illuminate\Database\Eloquent\Collection;

class EventService implements ServiceInterface
{
    public function getAll()
    {
        return Event::with(['club', 'creator', 'formats', 'users'])->get();
    }

    public function find(int $id)
    {
        return Event::with(['club', 'creator', 'formats', 'users', 'registrations.user', 'carpooling.user', 'accommodation.user'])
                    ->findOrFail($id);
    }

    public function create(array $data)
    {
        $event = Event::create($data);
        return $event->load(['club', 'creator', 'formats', 'users']);
    }

    public function update(int $id, array $data)
    {
        $event = $this->find($id);
        $event->update($data);
        return $event->fresh()->load(['club', 'creator', 'formats', 'users']);
    }

    public function delete(int $id)
    {
        $event = $this->find($id);
        return $event->delete();
    }

    /**
     * Récupérer les événements d'un club
     */
    public function getClubEvents(int $clubId): Collection
    {
        return Event::where('club_id', $clubId)
                    ->with(['club', 'creator', 'formats', 'users'])
                    ->get();
    }

    /**
     * Rechercher des événements
     */
    public function search(string $query): Collection
    {
        return Event::where('title', 'like', "%{$query}%")
                   ->orWhere('description', 'like', "%{$query}%")
                   ->orWhere('location', 'like', "%{$query}%")
                   ->with(['club', 'creator', 'formats', 'users'])
                   ->get();
    }

    /**
     * Approuver un événement
     */
    public function approve(int $id): Event
    {
        $event = $this->find($id);
        $event->update(['status' => 'approved']);
        return $event->fresh();
    }

    /**
     * Rejeter un événement
     */
    public function reject(int $id): Event
    {
        $event = $this->find($id);
        $event->update(['status' => 'rejected']);
        return $event->fresh();
    }

    /**
     * Ajouter un format à un événement
     */
    public function addFormat(int $eventId, array $formatData): EventFormat
    {
        $formatData['event_id'] = $eventId;
        return EventFormat::create($formatData);
    }

    /**
     * Supprimer un format d'un événement
     */
    public function removeFormat(int $formatId): bool
    {
        $format = EventFormat::findOrFail($formatId);
        return $format->delete();
    }

    /**
     * Inscrire un utilisateur à un événement
     */
    public function registerUser(int $eventId, int $userId, string $status = 'registered'): EventRegistration
    {
        return EventRegistration::create([
            'event_id' => $eventId,
            'user_id' => $userId,
            'status' => $status,
            'registered_at' => now(),
        ]);
    }

    /**
     * Désinscrire un utilisateur d'un événement
     */
    public function unregisterUser(int $eventId, int $userId): bool
    {
        return EventRegistration::where('event_id', $eventId)
                               ->where('user_id', $userId)
                               ->delete() > 0;
    }

    /**
     * Ajouter du covoiturage
     */
    public function addCarpooling(int $eventId, int $userId, int $availableSeats): EventCarpooling
    {
        return EventCarpooling::create([
            'event_id' => $eventId,
            'user_id' => $userId,
            'available_seats' => $availableSeats,
        ]);
    }

    /**
     * Ajouter de l'hébergement
     */
    public function addAccommodation(int $eventId, int $userId, int $availablePlaces): EventAccommodation
    {
        return EventAccommodation::create([
            'event_id' => $eventId,
            'user_id' => $userId,
            'available_places' => $availablePlaces,
        ]);
    }
}
