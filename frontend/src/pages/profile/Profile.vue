<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-50 to-blue-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Bon retour !</h1>
        <p class="text-xl text-gray-600">Voici vos informations de profil</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
          <UiCard>
            <template #header>
              <h2 class="text-2xl font-semibold text-gray-900">Informations du profil</h2>
            </template>

            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                  <p class="text-lg text-gray-900">{{ authStore.user?.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                  <p class="text-lg text-gray-900">{{ authStore.user?.email }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Membre depuis</label>
                  <p class="text-lg text-gray-900">{{ formatDate(authStore.user?.created_at) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Dernière mise à jour</label>
                  <p class="text-lg text-gray-900">{{ formatDate(authStore.user?.updated_at) }}</p>
                </div>
              </div>
            </div>
          </UiCard>
        </div>

        <!-- Actions Card -->
        <div class="lg:col-span-1">
          <UiCard>
            <template #header>
              <h3 class="text-xl font-semibold text-gray-900">Actions</h3>
            </template>

            <div class="space-y-4">
              <UiButton
                variant="secondary"
                class="w-full"
                @click="handleEditProfile"
              >
                Modifier le profil
              </UiButton>

              <UiButton
                variant="danger"
                class="w-full"
                @click="handleLogout"
              >
                Se déconnecter
              </UiButton>
            </div>

            <template #footer>
              <div class="text-center">
                <p class="text-sm text-gray-500">
                  Besoin d'aide ? Contactez le support
                </p>
              </div>
            </template>
          </UiCard>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '../../store';
import UiCard from '../../components/ui/UiCard.vue';
import UiButton from '../../components/ui/UiButton.vue';

const authStore = useAuthStore();

const formatDate = (dateString?: string) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const handleEditProfile = () => {
  // TODO: Implémenter la fonctionnalité de modification du profil
  console.log('Modification du profil cliquée');
};

const handleLogout = async () => {
  await authStore.logout();
};
</script>
