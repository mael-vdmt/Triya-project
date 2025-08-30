import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8080',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Don't redirect automatically, let the components handle it
      console.log('Unauthorized request - user needs to login');
      
      // Clear any stored user data if we get a 401
      if (error.config.url === '/api/user') {
        // This is a user fetch request, clear the user
        localStorage.removeItem('user');
        localStorage.removeItem('auth_token');
      }
    }
    return Promise.reject(error);
  }
);

export default api;
