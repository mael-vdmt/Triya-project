<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo/Titre -->
        <div class="flex items-center">
          <h1 class="text-2xl font-bold text-primary-600">Triya</h1>
        </div>

        <!-- Navigation Desktop -->
        <nav class="hidden md:flex space-x-8">
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/dashboard"
            class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            :class="{ 'text-primary-600 bg-primary-50': $route.path === '/dashboard' }"
          >
            Dashboard
          </router-link>
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/profile"
            class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            :class="{ 'text-primary-600 bg-primary-50': $route.path === '/profile' }"
          >
            Profil
          </router-link>
        </nav>

        <!-- Bouton déconnexion Desktop -->
        <div class="hidden md:flex items-center">
          <button
            @click="handleLogout"
            class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
          >
            Se déconnecter
          </button>
        </div>

        <!-- Bouton Burger Menu Mobile -->
        <div class="md:hidden">
          <button
            @click="toggleMobileMenu"
            class="text-gray-700 hover:text-primary-600 p-2 rounded-md transition-colors duration-200"
            aria-label="Menu"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                v-if="!isMobileMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Menu Mobile -->
      <div
        v-show="isMobileMenuOpen"
        class="md:hidden border-t border-gray-200 py-4"
      >
        <nav class="flex flex-col space-y-2">
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/dashboard"
            @click="closeMobileMenu"
            class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            :class="{ 'text-primary-600 bg-primary-50': $route.path === '/dashboard' }"
          >
            Dashboard
          </router-link>
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/profile"
            @click="closeMobileMenu"
            class="text-gray-700 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            :class="{ 'text-primary-600 bg-primary-50': $route.path === '/profile' }"
          >
            Profil
          </router-link>
          <div class="border-t border-gray-200 pt-2 mt-2">
            <button
              @click="handleLogout"
              class="w-full text-left text-gray-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            >
              Se déconnecter
            </button>
          </div>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '../store';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

// État du menu mobile
const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
};

const handleLogout = async () => {
  try {
    closeMobileMenu(); // Fermer le menu mobile avant déconnexion
    await authStore.logout();
    // La redirection est gérée dans le store
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error);
  }
};
</script>
