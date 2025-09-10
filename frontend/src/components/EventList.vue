<template>
  <div>
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-accent-500"></div>
    </div>

    <!-- Error state -->
    <UiAlert v-if="error" type="error" class="mb-6">
      {{ error }}
    </UiAlert>

    <!-- Événements à venir -->
    <div v-if="events.length > 0" class="mb-12">
      <h2 class="text-2xl font-bold text-sport-800 mb-6">Événements à venir</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <UiCard
          v-for="event in events"
          :key="event.id"
          class="hover:shadow-lg transition-shadow duration-300"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-xl font-bold text-sport-800 mb-2">{{ event.title }}</h3>
              <p class="text-sport-600 text-sm mb-2">{{ event.club.name }}</p>
              <p class="text-sport-500 text-sm">{{ event.location }}</p>
            </div>
            <span class="px-2 py-1 bg-accent-100 text-accent-800 text-xs font-semibold rounded-full">
              {{ event.type }}
            </span>
          </div>
          
          <p class="text-sport-600 text-sm mb-4 line-clamp-2">{{ event.description }}</p>
          
          <div class="flex items-center justify-between text-sm text-sport-500 mb-4">
            <div class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              {{ formatDate(event.start_date) }}
            </div>
            <div v-if="event.max_participants" class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              {{ event.registrations_count || 0 }}/{{ event.max_participants }}
            </div>
          </div>
          
          <div class="flex space-x-2">
            <button class="flex-1 flex items-center justify-center px-3 py-1.5 border-2 border-sport-300 text-sport-700 bg-white hover:border-sport-500 hover:bg-sport-50 hover:text-sport-800 rounded-lg transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
              <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              Ça m'intéresse
            </button>
            <button class="flex-1 flex items-center justify-center px-3 py-1.5 border-2 border-accent-300 text-accent-700 bg-white hover:border-accent-500 hover:bg-accent-50 hover:text-accent-800 rounded-lg transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
              <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Je suis inscrit
            </button>
            <div 
              class="flex items-center justify-center p-1.5 text-sport-400 hover:text-sport-600 hover:bg-sport-50 rounded-lg transition-all duration-200 group cursor-pointer"
              title="Ouvrir le chat"
            >
              <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
          </div>
        </UiCard>
      </div>
    </div>

    <!-- Message si aucun événement -->
    <div v-else-if="!loading" class="text-center py-12">
      <div class="sport-icon sport-icon-primary mx-auto mb-4">
        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-sport-800 mb-2">Aucun événement à venir</h3>
      <p class="text-sport-600 mb-6">Créez votre premier événement pour commencer !</p>
      <UiButton @click="$emit('create-event')" variant="primary">
        Créer un événement
      </UiButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import UiButton from './ui/UiButton.vue';
import UiAlert from './ui/UiAlert.vue';
import UiCard from './ui/UiCard.vue';
import type { Event } from '../api/event';

interface Props {
  events: Event[];
  loading: boolean;
  error: string | null;
}

const props = defineProps<Props>();

defineEmits<{
  'create-event': [];
}>();

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
