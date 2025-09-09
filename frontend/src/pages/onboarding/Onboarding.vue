<template>
  <AuthenticatedLayout>
    <div class="flex items-center justify-center bg-gradient-to-br from-primary-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-4xl w-full space-y-6">
        <div class="text-center">
          <h1 class="text-4xl font-bold text-gray-900 mb-2">Bienvenue sur Triya !</h1>
          <p class="text-gray-600">Pour commencer, vous devez rejoindre ou créer un club.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Créer un club -->
          <UiCard>
            <div class="text-center">
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">
                Créer un club
              </h3>
              <p class="text-sm text-gray-500 mb-6">
                Créez votre propre club et invitez des membres à vous rejoindre.
              </p>
              <UiButton
                @click="createClub"
                variant="primary"
                size="lg"
                class="w-full"
              >
                Créer un club
              </UiButton>
            </div>
          </UiCard>

          <!-- Rejoindre un club -->
          <UiCard>
            <div class="text-center">
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">
                Rejoindre un club
              </h3>
              <p class="text-sm text-gray-500 mb-6">
                Rejoignez un club existant en utilisant un code d'invitation ou un lien.
              </p>
              <UiButton
                @click="joinClub"
                variant="secondary"
                size="lg"
                class="w-full"
              >
                Rejoindre un club
              </UiButton>
            </div>
          </UiCard>
        </div>

        <!-- Message d'information -->
        <UiAlert type="info">
          <strong>Note :</strong> Pour rejoindre un club, vous devez recevoir une invitation par email ou un lien d'invitation de la part d'un administrateur du club.
        </UiAlert>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue';
import UiCard from '../../components/ui/UiCard.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const router = useRouter();

const createClub = () => {
  // TODO: Rediriger vers la page de création de club
  console.log('Créer un club');
  // router.push('/clubs/create');
};

const joinClub = () => {
  // TODO: Rediriger vers la page de rejoindre un club
  console.log('Rejoindre un club');
  // router.push('/clubs/join');
};
</script>

