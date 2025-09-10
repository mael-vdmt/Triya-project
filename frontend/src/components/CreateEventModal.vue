<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-sport-800">Créer un événement</h3>
        <button
          @click="$emit('close')"
          class="text-sport-400 hover:text-sport-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <UiInput
            v-model="eventData.title"
            label="Titre de l'événement"
            placeholder="Ex: Low Triathlon"
            required
          />

          <div>
            <label for="type" class="block text-sm font-semibold text-sport-700 mb-2">
              Type d'événement *
            </label>
            <select
              id="type"
              v-model="eventData.type"
              required
              class="w-full px-4 py-3 border border-sport-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-colors"
            >
              <option value="">Sélectionner un type</option>
              <option value="competition">Compétition</option>
              <option v-if="canCreateClubLifeEvent" value="club_life">Vie du club</option>
            </select>
          </div>
        </div>

        <!-- Champs spécifiques selon le type -->
        <div v-if="eventData.type === 'competition'" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <UiInput
              v-model="eventData.start_date"
              label="Date de début"
              type="datetime-local"
              required
            />

            <UiInput
              v-model="eventData.end_date"
              label="Date de fin"
              type="datetime-local"
              required
            />
          </div>

          <UiInput
            v-model="eventData.location"
            label="Lieu"
            placeholder="Ex: Stade municipal"
            required
          />
        </div>

        <div v-else-if="eventData.type === 'club_life'" class="space-y-6">
          <UiInput
            v-model="eventData.description"
            label="Description"
            placeholder="Décrivez votre événement..."
            type="textarea"
          />

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <UiInput
              v-model="eventData.start_date"
              label="Date de début"
              type="datetime-local"
              required
            />

            <UiInput
              v-model="eventData.end_date"
              label="Date de fin"
              type="datetime-local"
              required
            />
          </div>

          <UiInput
            v-model="eventData.location"
            label="Lieu"
            placeholder="Ex: Siège du club"
            required
          />
        </div>

        <div class="flex space-x-4 pt-4">
          <UiButton
            type="button"
            variant="secondary"
            @click="$emit('close')"
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
            <span v-if="loading">Création...</span>
            <span v-else>Créer l'événement</span>
          </UiButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import UiButton from './ui/UiButton.vue';
import UiInput from './ui/UiInput.vue';
import { useClubStore } from '../store/club';

interface Props {
  show: boolean;
  loading: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  close: [];
  submit: [eventData: any];
}>();

const clubStore = useClubStore();

// Vérifier si l'utilisateur peut créer des événements "vie du club"
const canCreateClubLifeEvent = computed(() => {
  const currentClub = clubStore.currentClub;
  if (!currentClub?.role) return false;
  return ['owner', 'admin'].includes(currentClub.role);
});

const eventData = ref({
  club_id: 0,
  title: '',
  description: '',
  type: '',
  start_date: '',
  end_date: '',
  location: '',
});

// Réinitialiser le formulaire quand le modal s'ouvre
watch(() => props.show, (isOpen) => {
  if (isOpen) {
    eventData.value = {
      club_id: clubStore.currentClub?.id || 0,
      title: '',
      description: '',
      type: '',
      start_date: '',
      end_date: '',
      location: '',
    };
  }
});

// Synchroniser end_date avec start_date
watch(() => eventData.value.start_date, (newStartDate) => {
  if (newStartDate && !eventData.value.end_date) {
    eventData.value.end_date = newStartDate;
  }
});

const handleSubmit = () => {
  emit('submit', eventData.value);
};
</script>
