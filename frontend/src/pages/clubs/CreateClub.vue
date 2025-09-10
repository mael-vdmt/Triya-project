<template>
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-sport-800 mb-2">Créer un club</h1>
        <p class="text-xl text-sport-600">Créez votre propre club sportif</p>
      </div>

      <div class="sport-card">
        <form @submit.prevent="handleCreateClub" class="space-y-6">
          <UiAlert
            v-if="clubStore.error"
            type="error"
            :show="!!clubStore.error"
            dismissible
            @dismiss="clubStore.clearError"
          >
            {{ clubStore.error }}
          </UiAlert>

          <UiInput
            v-model="form.name"
            label="Nom du club"
            type="text"
            placeholder="Entrez le nom de votre club"
            required
            :error="errors.name"
          />

          <UiInput
            v-model="form.location"
            label="Localisation"
            type="text"
            placeholder="Ville"
            required
            :error="errors.location"
          />

          <div>
            <label class="block text-sm font-semibold text-sport-700 mb-2">
              Description (optionnel)
            </label>
            <textarea
              v-model="form.description"
              class="w-full px-4 py-3 border border-sport-200 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 bg-white shadow-sm hover:shadow-md resize-none"
              rows="4"
              placeholder="Décrivez votre club, ses activités, ses valeurs..."
              maxlength="1000"
            ></textarea>
            <p class="text-xs text-sport-500 mt-1">
              {{ form.description.length }}/1000 caractères
            </p>
            <p v-if="errors.description" class="text-red-500 text-sm mt-1">
              {{ errors.description }}
            </p>
          </div>

          <div class="flex gap-4">
            <UiButton
              type="button"
              variant="secondary"
              class="flex-1"
              @click="goBack"
            >
              Annuler
            </UiButton>
            
            <UiButton
              type="submit"
              :loading="clubStore.loading"
              loading-text="Création..."
              class="flex-1"
            >
              Créer le club
            </UiButton>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useClubStore } from '../../store/club';
import { useAuthStore } from '../../store/auth';
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue';
import UiInput from '../../components/ui/UiInput.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const router = useRouter();
const clubStore = useClubStore();
const authStore = useAuthStore();

// Effacer les erreurs au montage du composant
onMounted(() => {
  clubStore.clearError();
});

const form = reactive({
  name: '',
  description: '',
  location: '',
});

const errors = ref<Record<string, string>>({});

const validateForm = () => {
  errors.value = {};
  
  if (!form.name.trim()) {
    errors.value.name = 'Le nom du club est requis';
  } else if (form.name.length > 255) {
    errors.value.name = 'Le nom ne peut pas dépasser 255 caractères';
  }
  
  if (!form.location.trim()) {
    errors.value.location = 'La localisation est requise';
  } else if (form.location.length > 255) {
    errors.value.location = 'La localisation ne peut pas dépasser 255 caractères';
  }
  
  if (form.description && form.description.length > 1000) {
    errors.value.description = 'La description ne peut pas dépasser 1000 caractères';
  }
  
  return Object.keys(errors.value).length === 0;
};

const handleCreateClub = async () => {
  if (!validateForm()) {
    return;
  }

    try {
      const clubData = {
        name: form.name.trim(),
        location: form.location.trim(),
        description: form.description.trim() || undefined,
      };

      const newClub = await clubStore.createClub(clubData);
      
      // Rediriger vers le dashboard après création
      await router.push('/dashboard');
    } catch (error) {
      console.error('Erreur lors de la création du club:', error);
    }
};

const goBack = () => {
  router.push('/onboarding');
};
</script>
