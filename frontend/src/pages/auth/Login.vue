<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-50 to-blue-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Connexion</h1>
        <p class="text-gray-600">Connectez-vous à votre compte</p>
      </div>

      <UiCard>
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

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Pas encore de compte ?
            <router-link
              to="/register"
              class="font-medium text-primary-600 hover:text-primary-500 transition-colors"
            >
              S'inscrire
            </router-link>
          </p>
        </div>
      </UiCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../store';
import UiCard from '../../components/ui/UiCard.vue';
import UiInput from '../../components/ui/UiInput.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const router = useRouter();
const authStore = useAuthStore();

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
    router.push('/profile');
  } catch (error) {
    console.error('Erreur de connexion:', error);
  }
};
</script>
