<template>
  <div class="min-h-screen sport-bg flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
      <div class="text-center">
        <h1 class="text-3xl font-bold text-sport-800 mb-2">Connexion</h1>
        <p class="text-sport-600">Connectez-vous à votre compte</p>
      </div>

      <div class="sport-card">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <UiAlert
            v-if="authStore.error"
            type="error"
            :show="!!authStore.error"
            dismissible
            @dismiss="authStore.clearError"
          >
            {{ authStore.error }}
          </UiAlert>

          <UiInput
            v-model="form.email"
            label="Adresse email"
            type="email"
            placeholder="Entrez votre email"
            required
            :error="errors.email"
          />

          <UiInput
            v-model="form.password"
            label="Mot de passe"
            type="password"
            placeholder="Entrez votre mot de passe"
            required
            :error="errors.password"
          />

          <UiButton
            type="submit"
            :loading="authStore.loading"
            loading-text="Connexion..."
            class="w-full"
          >
            Se connecter
          </UiButton>
        </form>

        <div class="mt-8 text-center">
          <p class="text-sport-600">
            Pas encore de compte ?
            <router-link
              to="/register"
              class="font-semibold text-accent-600 hover:text-accent-700 transition-colors"
            >
              S'inscrire
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../store';
import UiCard from '../../components/ui/UiCard.vue';
import UiInput from '../../components/ui/UiInput.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const router = useRouter();
const authStore = useAuthStore();

// Effacer les erreurs au montage du composant
onMounted(() => {
  authStore.clearError();
});

const form = reactive({
  email: '',
  password: '',
});

const errors = ref<Record<string, string>>({});

const handleLogin = async () => {
  errors.value = {};
  
  // Basic validation
  if (!form.email) {
    errors.value.email = 'L\'email est requis';
  }
  if (!form.password) {
    errors.value.password = 'Le mot de passe est requis';
  }
  
  if (Object.keys(errors.value).length > 0) {
    return;
  }

  try {
    await authStore.login(form);
  } catch (error) {
    console.error('Erreur de connexion:', error);
  }
};
</script>
