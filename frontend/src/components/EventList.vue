<template>
  <div>

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
              <p class="text-sport-500 text-sm mb-2">{{ event.location }}</p>
            </div>
            <span class="px-2 py-1 bg-accent-100 text-accent-800 text-xs font-semibold rounded-full">
              {{ event.type === 'competition' ? 'Compétition' : 'Vie du club' }}
            </span>
          </div>
          
          <p v-if="event.description" class="text-sport-600 text-sm mb-4 line-clamp-2">{{ event.description }}</p>
          
          <div class="flex items-center justify-between text-sm text-sport-500 mb-4">
            <div class="flex items-center bg-sport-100 px-3 py-2 rounded-lg">
              <svg class="w-5 h-5 mr-2 text-sport-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="font-semibold text-sport-800">{{ formatDate(event.start_date) }}</span>
            </div>
            <div v-if="event.max_participants" class="flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              {{ event.registrations_count || 0 }}/{{ event.max_participants }}
            </div>
          </div>
          
          <div class="flex space-x-2">
            <button 
              @click="handleInterested(event)"
              :disabled="loadingStates[event.id] || getUserRegistrationStatus(event) === 'registered'"
              :class="[
                'flex-1 flex items-center justify-center px-3 py-1.5 rounded-lg transition-all duration-200 text-sm font-medium border-2',
                getUserRegistrationStatus(event) === 'interested' 
                  ? 'bg-sport-500 text-white border-sport-500' 
                  : getUserRegistrationStatus(event) === 'registered'
                  ? 'border-gray-300 text-gray-400 bg-gray-100 cursor-not-allowed'
                  : 'border-sport-300 text-sport-700 bg-white hover:border-sport-500 hover:bg-sport-50'
              ]"
            >
              <svg v-if="!loadingStates[event.id]" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <svg v-else class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Intéressé
            </button>
            <button 
              @click="handleRegistered(event)"
              :disabled="loadingStates[event.id] || getUserRegistrationStatus(event) === 'interested'"
              :class="[
                'flex-1 flex items-center justify-center px-3 py-1.5 rounded-lg transition-all duration-200 text-sm font-medium border-2',
                getUserRegistrationStatus(event) === 'registered' 
                  ? 'bg-accent-500 text-white border-accent-500' 
                  : getUserRegistrationStatus(event) === 'interested'
                  ? 'border-gray-300 text-gray-400 bg-gray-100 cursor-not-allowed'
                  : 'border-accent-300 text-accent-700 bg-white hover:border-accent-500 hover:bg-accent-50'
              ]"
            >
              <svg v-if="!loadingStates[event.id]" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <svg v-else class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Inscrit
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
import { ref } from 'vue';
import { useEventStore } from '../store/event';
import { useAuthStore } from '../store/auth';
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

const emit = defineEmits<{
  'create-event': [];
}>();

const eventStore = useEventStore();
const authStore = useAuthStore();

// Loading local pour chaque événement
const loadingStates = ref<Record<number, boolean>>({});

// Vérifier le statut d'inscription de l'utilisateur pour un événement
const getUserRegistrationStatus = (event: Event): 'interested' | 'registered' | null => {
  if (!authStore.user || !event.registered_users) return null;
  
  const userRegistration = event.registered_users.find(
    user => user.id === authStore.user!.id
  );
  
  return userRegistration ? userRegistration.status as 'interested' | 'registered' : null;
};

const handleInterested = async (event: Event) => {
  try {
    loadingStates.value[event.id] = true;
    
    const currentStatus = getUserRegistrationStatus(event);
    
    if (currentStatus === 'interested') {
      await eventStore.unregisterFromEvent(event.id);
    } else {
      await eventStore.markAsInterested(event.id);
    }
  } catch (error) {
    console.error('Erreur:', error);
  } finally {
    loadingStates.value[event.id] = false;
  }
};

const handleRegistered = async (event: Event) => {
  try {
    loadingStates.value[event.id] = true;
    
    const currentStatus = getUserRegistrationStatus(event);
    
    if (currentStatus === 'registered') {
      await eventStore.unregisterFromEvent(event.id);
    } else {
      await eventStore.markAsRegistered(event.id);
    }
  } catch (error) {
    console.error('Erreur:', error);
  } finally {
    loadingStates.value[event.id] = false;
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
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
