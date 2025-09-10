<template>
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header avec bouton créer événement -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
          <h1 class="text-4xl font-bold text-sport-800 mb-2">Dashboard</h1>
          <p class="text-sport-600">Gérez vos événements et activités</p>
        </div>
        <UiButton
          v-if="eventStore.upcomingEvents.length > 0"
          @click="showCreateEventModal = true"
          variant="primary"
          class="mt-4 sm:mt-0"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Créer un événement
        </UiButton>
      </div>

      <!-- Liste des événements -->
      <EventList
        :events="eventStore.upcomingEvents"
        :loading="eventStore.loading"
        :error="eventStore.error"
        @create-event="showCreateEventModal = true"
      />

      <!-- Modal de création d'événement -->
      <CreateEventModal
        :show="showCreateEventModal"
        :loading="eventStore.loading"
        @close="showCreateEventModal = false"
        @submit="createEvent"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useEventStore } from '../../store/event';
import { useClubStore } from '../../store/club';
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue';
import UiButton from '../../components/ui/UiButton.vue';
import EventList from '../../components/EventList.vue';
import CreateEventModal from '../../components/CreateEventModal.vue';

const eventStore = useEventStore();
const clubStore = useClubStore();

const showCreateEventModal = ref(false);

// Fonction pour charger les événements du club sélectionné
const loadClubEvents = async () => {
  if (clubStore.currentClub) {
    try {
      await eventStore.getClubEvents(clubStore.currentClub.id);
    } catch (error) {
      console.error('Erreur lors du chargement des événements:', error);
    }
  }
};

// Charger les événements au montage
onMounted(async () => {
  // Les clubs sont déjà chargés par le Header
  if (clubStore.currentClub) {
    await loadClubEvents();
  } else if (clubStore.clubs.length > 0) {
    // Si pas de club sélectionné mais des clubs disponibles, sélectionner le premier
    clubStore.selectClub(clubStore.clubs[0].id);
    await loadClubEvents();
  }
});

// Watcher pour recharger les événements quand le club change
watch(() => clubStore.selectedClubId, async (newClubId) => {
  if (newClubId) {
    await loadClubEvents();
  }
});

const createEvent = async (eventData: any) => {
  try {
    // Utiliser le club sélectionné
    if (!clubStore.currentClub) {
      throw new Error('Aucun club sélectionné');
    }
    
    eventData.club_id = clubStore.currentClub.id;
    
    await eventStore.createEvent(eventData);
    
    showCreateEventModal.value = false;
  } catch (error) {
    console.error('Erreur lors de la création de l\'événement:', error);
  }
};
</script>
  