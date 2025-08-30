<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo/Titre -->
        <div class="flex items-center">
          <h1 class="text-2xl font-bold text-primary-600">Triya</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex space-x-8">
          <router-link
            to="/dashboard"
            class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            :class="{ 'text-primary-600 bg-primary-50': $route.path === '/dashboard' }"
          >
            Dashboard
          </router-link>
          <router-link
            to="/profile"
            class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            :class="{ 'text-primary-600 bg-primary-50': $route.path === '/profile' }"
          >
            Profil
          </router-link>
        </nav>

        <!-- Bouton déconnexion -->
        <div class="flex items-center">
          <button
            @click="handleLogout"
            class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
          >
            Se déconnecter
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '../store';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  try {
    await authStore.logout();
    // La redirection est gérée dans le store
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error);
  }
};
</script>
