<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-50 to-blue-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Créer un compte</h1>
        <p class="text-gray-600">Rejoignez-nous aujourd'hui</p>
      </div>

      <UiCard>
        <form @submit.prevent="handleRegister" class="space-y-6">
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
            v-model="form.name"
            label="Nom complet"
            type="text"
            placeholder="Entrez votre nom complet"
            required
            :error="errors.name"
          />

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
            placeholder="Créez un mot de passe"
            required
            :error="errors.password"
          />

          <UiInput
            v-model="form.password_confirmation"
            label="Confirmer le mot de passe"
            type="password"
            placeholder="Confirmez votre mot de passe"
            required
            :error="errors.password_confirmation"
          />

          <UiButton
            type="submit"
            :loading="authStore.loading"
            loading-text="Création du compte..."
            class="w-full"
          >
            Créer le compte
          </UiButton>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Vous avez déjà un compte ?
            <router-link
              to="/login"
              class="font-medium text-primary-600 hover:text-primary-500 transition-colors"
            >
              Se connecter
            </router-link>
          </p>
        </div>
      </UiCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useAuthStore } from '../../store';
import UiCard from '../../components/ui/UiCard.vue';
import UiInput from '../../components/ui/UiInput.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const authStore = useAuthStore();

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const errors = ref<Record<string, string>>({});

const handleRegister = async () => {
  errors.value = {};
  
  // Basic validation
  if (!form.name) {
    errors.value.name = 'Le nom est requis';
  }
  if (!form.email) {
    errors.value.email = 'L\'email est requis';
  }
  if (!form.password) {
    errors.value.password = 'Le mot de passe est requis';
  }
  if (form.password !== form.password_confirmation) {
    errors.value.password_confirmation = 'Les mots de passe ne correspondent pas';
  }
  
  if (Object.keys(errors.value).length > 0) {
    return;
  }

  try {
    await authStore.register({
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    });
  } catch (error) {
    // Error is handled by the store
  }
};
</script>
