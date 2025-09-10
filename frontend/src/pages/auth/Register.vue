<template>
  <div class="min-h-screen sport-bg flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
      <div class="text-center">
        <h1 class="text-3xl font-bold text-sport-800 mb-2">Créer un compte</h1>
        <p class="text-sport-600">Rejoignez-nous aujourd'hui</p>
      </div>

      <div class="sport-card">
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

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <UiInput
              v-model="form.first_name"
              label="Prénom"
              type="text"
              placeholder="Entrez votre prénom"
              required
              :error="errors.first_name"
            />

            <UiInput
              v-model="form.last_name"
              label="Nom de famille"
              type="text"
              placeholder="Entrez votre nom de famille"
              required
              :error="errors.last_name"
            />
          </div>

          <UiInput
            v-model="form.date_of_birth"
            label="Date de naissance"
            type="date"
            placeholder="Sélectionnez votre date de naissance"
            required
            :error="errors.date_of_birth"
          />

          <UiInput
            v-model="form.phone"
            label="Numéro de téléphone"
            type="tel"
            placeholder="Entrez votre numéro de téléphone (optionnel)"
            :error="errors.phone"
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

        <div class="mt-8 text-center">
          <p class="text-sport-600">
            Vous avez déjà un compte ?
            <router-link
              to="/login"
              class="font-semibold text-accent-600 hover:text-accent-700 transition-colors"
            >
              Se connecter
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import { useAuthStore } from '../../store';
import UiCard from '../../components/ui/UiCard.vue';
import UiInput from '../../components/ui/UiInput.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const authStore = useAuthStore();

// Effacer les erreurs au montage du composant
onMounted(() => {
  authStore.clearError();
});

const form = reactive({
  first_name: '',
  last_name: '',
  date_of_birth: '',
  phone: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const errors = ref<Record<string, string>>({});

const handleRegister = async () => {
  errors.value = {};
  
  // Basic validation
  if (!form.first_name) {
    errors.value.first_name = 'Le prénom est requis';
  }
  if (!form.last_name) {
    errors.value.last_name = 'Le nom de famille est requis';
  }
  if (!form.date_of_birth) {
    errors.value.date_of_birth = 'La date de naissance est requise';
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
      first_name: form.first_name,
      last_name: form.last_name,
      date_of_birth: form.date_of_birth,
      phone: form.phone || undefined,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    });
  } catch (error) {
    // Error is handled by the store
  }
};
</script>
