import api from './index';

export interface LoginData {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterData {
  first_name: string;
  last_name: string;
  date_of_birth: string;
  phone?: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface User {
  id: number;
  first_name: string;
  last_name: string;
  date_of_birth: string;
  phone?: string;
  email: string;
  created_at: string;
  updated_at: string;
  has_clubs: boolean;
  owns_clubs: boolean;
}

export const authApi = {
  async login(data: LoginData) {
    const response = await api.post('/api/login', data);
    return response.data;
  },

  async register(data: RegisterData) {
    const response = await api.post('/api/register', data);
    return response.data;
  },

  async logout() {
    const response = await api.post('/api/logout');
    return response.data;
  },

  async getUser() {
    const response = await api.get('/api/user');
    return response.data;
  },
};
