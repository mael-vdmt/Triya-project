import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { clubApi, type Club, type CreateClubData, type UpdateClubData } from '../api/club';
import { useRouter } from 'vue-router';
import { useAuthStore } from './auth';

export const useClubStore = defineStore('club', () => {
  const clubs = ref<Club[]>([]);
  const currentClub = ref<Club | null>(null);
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

  const getAllClubs = async () => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.getAll();
      clubs.value = response.data;
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la récupération des clubs';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const createClub = async (clubData: CreateClubData) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.create(clubData);
      const newClub = response.data;
      
      // Ajouter le nouveau club à la liste
      clubs.value.push(newClub);
      
      // Mettre à jour manuellement le statut has_clubs de l'utilisateur
      const authStore = useAuthStore();
      if (authStore.user) {
        authStore.user.has_clubs = true;
        authStore.user.owns_clubs = true;
      }
      
      return newClub;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la création du club';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const getClubById = async (id: number) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.getById(id);
      currentClub.value = response.data;
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la récupération du club';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const updateClub = async (id: number, clubData: UpdateClubData) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.update(id, clubData);
      const updatedClub = response.data;
      
      // Mettre à jour le club dans la liste
      const index = clubs.value.findIndex(club => club.id === id);
      if (index !== -1) {
        clubs.value[index] = updatedClub;
      }
      
      // Mettre à jour le club actuel si c'est le même
      if (currentClub.value?.id === id) {
        currentClub.value = updatedClub;
      }
      
      return updatedClub;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la mise à jour du club';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const deleteClub = async (id: number) => {
    try {
      setLoading(true);
      clearError();
      
      await clubApi.delete(id);
      
      // Supprimer le club de la liste
      clubs.value = clubs.value.filter(club => club.id !== id);
      
      // Vider le club actuel si c'est le même
      if (currentClub.value?.id === id) {
        currentClub.value = null;
      }
      
      return true;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la suppression du club';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const getClubMembers = async (id: number) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.getMembers(id);
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la récupération des membres';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const addMember = async (id: number, userId: number, role: string = 'member') => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.addMember(id, userId, role);
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de l\'ajout du membre';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const removeMember = async (id: number, userId: number) => {
    try {
      setLoading(true);
      clearError();
      
      const response = await clubApi.removeMember(id, userId);
      return response.data;
    } catch (err: any) {
      const errorMessage = err.response?.data?.message || 'Erreur lors de la suppression du membre';
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  return {
    clubs,
    currentClub,
    loading,
    error,
    setError,
    clearError,
    setLoading,
    getAllClubs,
    createClub,
    getClubById,
    updateClub,
    deleteClub,
    getClubMembers,
    addMember,
    removeMember,
  };
});
