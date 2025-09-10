import api from './index';

export interface Event {
  id: number;
  title: string;
  description?: string;
  type: string;
  status: string;
  start_date: string;
  end_date: string;
  location: string;
  max_participants?: number;
  club: {
    id: number;
    name: string;
  };
  creator: {
    id: number;
    first_name: string;
    last_name: string;
  };
  formats?: EventFormat[];
  registrations_count?: number;
  registered_users?: RegisteredUser[];
  carpooling_count?: number;
  accommodation_count?: number;
  created_at: string;
  updated_at: string;
}

export interface EventFormat {
  id: number;
  event_id: number;
  name: string;
  description?: string;
  price: number;
  max_participants?: number;
  created_at: string;
  updated_at: string;
}

export interface RegisteredUser {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  status: string;
  registered_at: string;
}

export interface CreateEventData {
  club_id: number;
  title: string;
  description?: string;
  type: string;
  start_date: string;
  end_date: string;
  location: string;
  max_participants?: number;
}

export interface UpdateEventData {
  title?: string;
  description?: string;
  type?: string;
  start_date?: string;
  end_date?: string;
  location?: string;
  max_participants?: number;
}

export const eventApi = {
  async getAll() {
    const response = await api.get('/api/events');
    return response.data;
  },

  async create(data: CreateEventData) {
    const response = await api.post('/api/events', data);
    return response.data;
  },

  async getById(id: number) {
    const response = await api.get(`/api/events/${id}`);
    return response.data;
  },

  async update(id: number, data: UpdateEventData) {
    const response = await api.put(`/api/events/${id}`, data);
    return response.data;
  },

  async delete(id: number) {
    const response = await api.delete(`/api/events/${id}`);
    return response.data;
  },

  async getClubEvents(clubId: number) {
    const response = await api.get(`/api/clubs/${clubId}/events`);
    return response.data;
  },

  async search(query: string) {
    const response = await api.get(`/api/events/search?q=${encodeURIComponent(query)}`);
    return response.data;
  },

  async register(eventId: number, status: 'registered' | 'interested' = 'registered') {
    const response = await api.post(`/api/events/${eventId}/register`, { status });
    return response.data;
  },

  async unregister(eventId: number) {
    const response = await api.delete(`/api/events/${eventId}/unregister`);
    return response.data;
  },

  async approve(eventId: number) {
    const response = await api.post(`/api/events/${eventId}/approve`);
    return response.data;
  },

  async reject(eventId: number) {
    const response = await api.post(`/api/events/${eventId}/reject`);
    return response.data;
  },
};
