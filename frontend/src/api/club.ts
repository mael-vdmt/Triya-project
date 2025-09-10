import api from './index';

export interface Club {
  id: number;
  name: string;
  description?: string;
  location: string;
  members_count?: number;
  role?: string;
  joined_at?: string;
  created_at: string;
  updated_at: string;
}

export interface CreateClubData {
  name: string;
  description?: string;
  location: string;
}

export interface UpdateClubData {
  name?: string;
  description?: string;
  location?: string;
}

export const clubApi = {
  async getAll() {
    const response = await api.get('/api/clubs');
    return response.data;
  },

  async getUserClubs() {
    const response = await api.get('/api/user/clubs');
    return response.data;
  },

  async create(data: CreateClubData) {
    const response = await api.post('/api/clubs', data);
    return response.data;
  },

  async getById(id: number) {
    const response = await api.get(`/api/clubs/${id}`);
    return response.data;
  },

  async update(id: number, data: UpdateClubData) {
    const response = await api.put(`/api/clubs/${id}`, data);
    return response.data;
  },

  async delete(id: number) {
    const response = await api.delete(`/api/clubs/${id}`);
    return response.data;
  },

  async getMembers(id: number) {
    const response = await api.get(`/api/clubs/${id}/members`);
    return response.data;
  },

  async addMember(id: number, userId: number, role: string = 'member') {
    const response = await api.post(`/api/clubs/${id}/members`, {
      user_id: userId,
      role: role
    });
    return response.data;
  },

  async removeMember(id: number, userId: number) {
    const response = await api.delete(`/api/clubs/${id}/members`, {
      data: { user_id: userId }
    });
    return response.data;
  },
};
