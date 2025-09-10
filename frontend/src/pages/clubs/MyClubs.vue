<template>
  <AuthenticatedLayout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-sport-800 mb-2">Mes clubs</h1>
        </div>

        <!-- Actions rapides -->
        <div class="mb-8 flex flex-col sm:flex-row gap-4">
          <UiButton
            @click="createClub"
            variant="primary"
            class="flex items-center justify-center"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Créer un nouveau club
          </UiButton>
          <UiButton
            @click="joinClub"
            variant="accent"
            class="flex items-center justify-center"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Rejoindre un club
          </UiButton>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue';
import UiButton from '../../components/ui/UiButton.vue';

const router = useRouter();

const createClub = () => {
  router.push('/clubs/create');
};

const joinClub = () => {
  router.push('/clubs/join');
};
</script>
