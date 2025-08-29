import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { authApi, type User, type LoginData, type RegisterData } from '@/api/auth';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const router = useRouter();

  const isAuthenticated = computed(() => !!user.value);

  const setUser = (userData: User | null) => {
    user.value = userData;
  };

  const setError = (errorMessage: string | null) => {
    error.value = errorMessage;
  };

  const clearError = () => {
    error.value = null;
  };

  const login = async (credentials: LoginData) => {
    try {
      loading.value = true;
      clearError();
      
      const response = await authApi.login(credentials);
      setUser(response.user);
      
      await router.push('/profile');
      return response;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Login failed';
      setError(errorMessage);
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const register = async (userData: RegisterData) => {
    try {
      loading.value = true;
      clearError();
      
      const response = await authApi.register(userData);
      setUser(response.user);
      
      await router.push('/profile');
      return response;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Registration failed';
      setError(errorMessage);
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const logout = async () => {
    try {
      await authApi.logout();
      setUser(null);
      await router.push('/login');
    } catch (err) {
      console.error('Logout error:', err);
    }
  };

  const fetchUser = async () => {
    try {
      const response = await authApi.getUser();
      setUser(response.user);
      return response.user;
    } catch (err) {
      setUser(null);
      throw err;
    }
  };

  const initializeAuth = async () => {
    try {
      await fetchUser();
    } catch (err) {
      // User is not authenticated
    }
  };

  return {
    user,
    loading,
    error,
    isAuthenticated,
    setUser,
    setError,
    clearError,
    login,
    register,
    logout,
    fetchUser,
    initializeAuth,
  };
});
