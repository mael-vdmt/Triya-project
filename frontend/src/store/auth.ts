import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { authApi, type User, type LoginData, type RegisterData } from '../api/auth';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const isInitialized = ref(false);
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

  const redirectAfterAuth = async () => {
    if (!user.value) return;
    
    // Vérifier si l'utilisateur a des clubs
    if (user.value.has_clubs) {
      // L'utilisateur a des clubs, rediriger vers le dashboard
      await router.push('/dashboard');
    } else {
      // L'utilisateur n'a pas de clubs, rediriger vers l'onboarding
      await router.push('/onboarding');
    }
  };

  const login = async (credentials: LoginData) => {
    try {
      loading.value = true;
      clearError();
      
      const response = await authApi.login(credentials);
      setUser(response.user);
      
      // Store the token
      if (response.token) {
        localStorage.setItem('auth_token', response.token);
        localStorage.setItem('user', JSON.stringify(response.user));
      }
      
      // Rediriger selon le statut des clubs
      await redirectAfterAuth();
      return response;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Échec de la connexion';
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
      
      // Store the token
      if (response.token) {
        localStorage.setItem('auth_token', response.token);
        localStorage.setItem('user', JSON.stringify(response.user));
      }
      
      // Rediriger selon le statut des clubs
      await redirectAfterAuth();
      return response;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Échec de l\'inscription';
      setError(errorMessage);
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const logout = async () => {
    try {
      await authApi.logout();
    } catch (err) {
      console.error('Logout error:', err);
    } finally {
      // Clear local data regardless of API response
      setUser(null);
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      await router.push('/login');
    }
  };

  const fetchUser = async () => {
    try {
      const response = await authApi.getUser();
      setUser(response.user);
      return response.user;
    } catch (err: any) {
      // Ne déconnecter l'utilisateur que si c'est vraiment une erreur d'authentification
      if (err.response?.status === 401) {
        setUser(null);
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
      }
      throw err;
    }
  };

  const initializeAuth = async () => {
    // Prevent multiple initializations
    if (isInitialized.value || loading.value) {
      return;
    }

    try {
      loading.value = true;
      isInitialized.value = true;
      
      // Check if we have a stored token and user
      const token = localStorage.getItem('auth_token');
      const storedUser = localStorage.getItem('user');
      
      if (token && storedUser) {
        try {
          const userData = JSON.parse(storedUser);
          setUser(userData);
          // Verify the token is still valid by fetching user data
          await fetchUser();
        } catch (err) {
          // Token is invalid, clear stored data
          localStorage.removeItem('auth_token');
          localStorage.removeItem('user');
          setUser(null);
        }
      }
    } catch (err) {
      // User is not authenticated, this is normal
      console.log('User not authenticated');
    } finally {
      loading.value = false;
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
