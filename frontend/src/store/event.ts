import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { eventApi, type Event, type CreateEventData, type UpdateEventData } from '../api/event';

export const useEventStore = defineStore('event', () => {
  const events = ref<Event[]>([]);
  const currentEvent = ref<Event | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);

  const setError = (errorMessage: string | null) => {
    error.value = errorMessage;
  };

  const clearError = () => {
    error.value = null;
  };

  const setLoading = (isLoading: boolean) => {
    loading.value = isLoading;
  };

  // Événements triés par date (plus proche en premier)
  const upcomingEvents = computed(() => {
    const now = new Date();
    return events.value
      .filter(event => new Date(event.start_date) > now)
      .sort((a, b) => new Date(a.start_date).getTime() - new Date(b.start_date).getTime());
  });

  // Événements passés
  const pastEvents = computed(() => {
    const now = new Date();
    return events.value
      .filter(event => new Date(event.start_date) <= now)
      .sort((a, b) => new Date(b.start_date).getTime() - new Date(a.start_date).getTime());
  });

  const getAllEvents = async () => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.getAll();
      events.value = response.data;
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la récupération des événements';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const getClubEvents = async (clubId: number) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.getClubEvents(clubId);
      events.value = response.data;
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la récupération des événements du club';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const createEvent = async (eventData: CreateEventData) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.create(eventData);
      const newEvent = response.data;
      
      // Ajouter le nouvel événement à la liste
      events.value.push(newEvent);
      
      return newEvent;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la création de l\'événement';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const getEventById = async (id: number, setLoadingState: boolean = true) => {
    try {
      if (setLoadingState) {
        setLoading(true);
      }
      clearError();
      
      const response = await eventApi.getById(id);
      currentEvent.value = response.data;
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la récupération de l\'événement';
      setError(errorMessage);
      throw err;
    } finally {
      if (setLoadingState) {
        setLoading(false);
      }
    }
  };

  const updateEvent = async (id: number, eventData: UpdateEventData) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.update(id, eventData);
      const updatedEvent = response.data;
      
      // Mettre à jour l'événement dans la liste
      const index = events.value.findIndex(event => event.id === id);
      if (index !== -1) {
        events.value[index] = updatedEvent;
      }
      
      // Mettre à jour l'événement actuel si c'est le même
      if (currentEvent.value?.id === id) {
        currentEvent.value = updatedEvent;
      }
      
      return updatedEvent;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la mise à jour de l\'événement';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const deleteEvent = async (id: number) => {
    try {
      setLoading(true);
      clearError();
      
      await eventApi.delete(id);
      
      // Supprimer l'événement de la liste
      events.value = events.value.filter(event => event.id !== id);
      
      // Vider l'événement actuel si c'est le même
      if (currentEvent.value?.id === id) {
        currentEvent.value = null;
      }
      
      return true;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la suppression de l\'événement';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const searchEvents = async (query: string) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.search(query);
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la recherche d\'événements';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const registerToEvent = async (eventId: number, status: 'registered' | 'interested' = 'registered') => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.register(eventId, status);
      
      // Recharger l'événement pour avoir les données à jour
      const updatedEvent = await getEventById(eventId, false);
      
      // Mettre à jour l'événement dans la liste
      const index = events.value.findIndex(e => e.id === eventId);
      if (index !== -1) {
        events.value[index] = updatedEvent;
      }
      
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de l\'inscription à l\'événement';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const unregisterFromEvent = async (eventId: number) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await eventApi.unregister(eventId);
      
      // Recharger l'événement pour avoir les données à jour
      const updatedEvent = await getEventById(eventId, false);
      
      // Mettre à jour l'événement dans la liste
      const index = events.value.findIndex(e => e.id === eventId);
      if (index !== -1) {
        events.value[index] = updatedEvent;
      }
      
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la désinscription de l\'événement';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  // Méthodes spécifiques pour les statuts
  const markAsInterested = async (eventId: number) => {
    return await registerToEvent(eventId, 'interested');
  };

  const markAsRegistered = async (eventId: number) => {
    return await registerToEvent(eventId, 'registered');
  };

  const reset = () => {
    events.value = [];
    currentEvent.value = null;
    loading.value = false;
    error.value = null;
  };

  return {
    events,
    currentEvent,
    loading,
    error,
    upcomingEvents,
    pastEvents,
    setError,
    clearError,
    setLoading,
    getAllEvents,
    getClubEvents,
    createEvent,
    getEventById,
    updateEvent,
    deleteEvent,
    searchEvents,
    registerToEvent,
    unregisterFromEvent,
    markAsInterested,
    markAsRegistered,
    reset,
  };
});
