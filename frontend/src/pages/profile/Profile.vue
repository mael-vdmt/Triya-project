<template>
  <AuthenticatedLayout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-sport-800 mb-4">Mon Profil</h1>
        <p class="text-xl text-sport-600">Gérez vos informations personnelles</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
          <div class="sport-card">
            <div class="mb-8">
              <h2 class="text-2xl font-bold text-sport-800 mb-2">Informations personnelles</h2>
              <p class="text-sport-600">Vos données de profil et coordonnées</p>
            </div>

            <div class="space-y-4">
              <!-- Informations principales -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-white/60 rounded-lg border border-sport-200/50">
                  <label class="block text-xs font-semibold text-sport-600 mb-1">Nom complet</label>
                  <p class="text-sport-800 break-words">
                    {{ authStore.user?.first_name }} {{ authStore.user?.last_name }}
                  </p>
                </div>
                
                <div class="p-4 bg-white/60 rounded-lg border border-sport-200/50">
                  <label class="block text-xs font-semibold text-sport-600 mb-1">Email</label>
                  <p class="text-sport-800 break-all text-sm">
                    {{ authStore.user?.email }}
                  </p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-white/60 rounded-lg border border-sport-200/50">
                  <label class="block text-xs font-semibold text-sport-600 mb-1">Téléphone</label>
                  <p class="text-sport-800">
                    {{ authStore.user?.phone || 'Non renseigné' }}
                  </p>
                </div>
                
                <div class="p-4 bg-white/60 rounded-lg border border-sport-200/50">
                  <label class="block text-xs font-semibold text-sport-600 mb-1">Date de naissance</label>
                  <p class="text-sport-800">
                    {{ formatDate(authStore.user?.date_of_birth) }}
                  </p>
                </div>
              </div>

              <!-- Informations de compte -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-sport-200/50">
                <div class="p-3 bg-sport-50/50 rounded-lg">
                  <label class="block text-xs font-semibold text-sport-600 mb-1">Membre depuis</label>
                  <p class="text-sport-800 text-sm">
                    {{ formatDate(authStore.user?.created_at) }}
                  </p>
                </div>
                <div class="p-3 bg-sport-50/50 rounded-lg">
                  <label class="block text-xs font-semibold text-sport-600 mb-1">Dernière mise à jour</label>
                  <p class="text-sport-800 text-sm">
                    {{ formatDate(authStore.user?.updated_at) }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions Card -->
        <div class="lg:col-span-1">
          <div class="sport-card">
            <div class="mb-6">
              <h3 class="text-xl font-bold text-sport-800 mb-2">Actions</h3>
              <p class="text-sport-600">Gérez votre compte</p>
            </div>

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

            <div class="mt-8 pt-6 border-t border-sport-200/50">
              <div class="text-center">
                <p class="text-sm text-sport-600">
                  Besoin d'aide ? Contactez le support
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useAuthStore } from '../../store';
import UiCard from '../../components/ui/UiCard.vue';
import UiButton from '../../components/ui/UiButton.vue';
import AuthenticatedLayout from '../../layouts/AuthenticatedLayout.vue';

const authStore = useAuthStore();

const formatDate = (dateString?: string) => {
  if (!dateString) return 'Non renseigné';
  try {
    return new Date(dateString).toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  } catch (error) {
    return 'Date invalide';
  }
};

const handleEditProfile = () => {
  // TODO: Implémenter la fonctionnalité de modification du profil
  console.log('Modification du profil cliquée');
};

const handleLogout = async () => {
  await authStore.logout();
};
</script>
