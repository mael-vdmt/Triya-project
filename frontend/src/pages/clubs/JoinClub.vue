<template>
  <AuthenticatedLayout>
    <div class="flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl w-full space-y-6">
        <div class="text-center">
          <h1 class="text-4xl font-bold text-sport-800 mb-2">Rejoindre un club</h1>
          <p class="text-sport-600">Entrez le code d'invitation ou le lien pour rejoindre un club.</p>
        </div>

        <div class="sport-card">
          <form @submit.prevent="joinClub" class="space-y-6">
            <div>
              <label for="invitationCode" class="block text-sm font-semibold text-sport-700 mb-2">
                Code d'invitation
              </label>
              <input
                id="invitationCode"
                v-model="invitationCode"
                type="text"
                placeholder="Entrez le code d'invitation"
                class="w-full px-4 py-3 border border-sport-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-colors"
                required
              />
              <p class="mt-2 text-sm text-sport-500">
                Le code d'invitation vous a été envoyé par email ou partagé par un administrateur du club.
              </p>
            </div>

            <div class="flex space-x-4">
              <UiButton
                type="button"
                variant="secondary"
                @click="goBack"
                class="flex-1"
              >
                Annuler
              </UiButton>
              <UiButton
                type="submit"
                variant="primary"
                :disabled="loading"
                class="flex-1"
              >
                <span v-if="loading">Rejoindre...</span>
                <span v-else>Rejoindre le club</span>
              </UiButton>
            </div>
          </form>
        </div>

        <!-- Informations supplémentaires -->
        <UiAlert type="info">
          <div>
            <strong>Comment obtenir un code d'invitation ?</strong>
            <ul class="mt-2 list-disc list-inside space-y-1 text-sm">
              <li>Demandez à un administrateur du club de vous envoyer une invitation</li>
              <li>Vérifiez vos emails pour un lien d'invitation</li>
              <li>Le code peut être partagé directement par un membre du club</li>
            </ul>
          </div>
        </UiAlert>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const router = useRouter();
const invitationCode = ref('');
const loading = ref(false);

const joinClub = async () => {
  try {
    loading.value = true;
    // TODO: Implémenter l'API pour rejoindre un club
    console.log('Rejoindre le club avec le code:', invitationCode.value);
    
    // Simulation d'un délai
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // Rediriger vers le dashboard après succès
    router.push('/dashboard');
  } catch (error) {
    console.error('Erreur lors de la réunion au club:', error);
  } finally {
    loading.value = false;
  }
};

const goBack = () => {
  router.back();
};
</script>
