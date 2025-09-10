<template>
  <header class="bg-white/90 backdrop-blur-sm shadow-sport border-b border-sport-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo/Titre -->
        <div class="flex items-center">
          <h1 class="text-2xl font-bold text-sport-800">Triya</h1>
        </div>

        <!-- Navigation Desktop -->
        <nav class="hidden md:flex space-x-8">
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/dashboard"
            class="text-sport-700 hover:text-accent-500 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300 hover:bg-accent-50"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/dashboard' }"
          >
            Dashboard
          </router-link>
          
          <!-- Menu Clubs -->
          <div class="relative group" v-if="authStore.user?.has_clubs">
            <button class="text-sport-700 hover:text-accent-500 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300 hover:bg-accent-50 flex items-center">
              Clubs
              <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
              <router-link
                to="/clubs/create"
                class="block px-4 py-2 text-sm text-sport-700 hover:bg-accent-50 hover:text-accent-500 transition-colors"
              >
                Créer un club
              </router-link>
              <router-link
                to="/clubs/join"
                class="block px-4 py-2 text-sm text-sport-700 hover:bg-accent-50 hover:text-accent-500 transition-colors"
              >
                Rejoindre un club
              </router-link>
              <div class="border-t border-sport-200 my-1"></div>
              <router-link
                to="/clubs"
                class="block px-4 py-2 text-sm text-sport-700 hover:bg-accent-50 hover:text-accent-500 transition-colors"
              >
                Mes clubs
              </router-link>
            </div>
          </div>
          
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/profile"
            class="text-sport-700 hover:text-accent-500 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300 hover:bg-accent-50"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/profile' }"
          >
            Profil
          </router-link>
        </nav>

        <!-- Bouton déconnexion Desktop -->
        <div class="hidden md:flex items-center">
          <button
            @click="handleLogout"
            class="text-sport-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300 hover:bg-red-50"
          >
            Se déconnecter
          </button>
        </div>

        <!-- Bouton Burger Menu Mobile -->
        <div class="md:hidden">
          <button
            @click="toggleMobileMenu"
            class="text-sport-700 hover:text-accent-500 p-2 rounded-md transition-all duration-300"
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
        class="md:hidden border-t border-sport-200 py-4"
      >
        <nav class="flex flex-col space-y-2">
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/dashboard"
            @click="closeMobileMenu"
            class="text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/dashboard' }"
          >
            Dashboard
          </router-link>
          
          <!-- Menu Clubs Mobile -->
          <div v-if="authStore.user?.has_clubs" class="space-y-1">
            <div class="text-sport-700 px-3 py-2 text-sm font-semibold text-sport-500">
              Clubs
            </div>
            <router-link
              to="/clubs/create"
              @click="closeMobileMenu"
              class="block text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-6 py-2 rounded-md text-sm transition-all duration-300"
              :class="{ 'text-accent-500 bg-accent-50': $route.path === '/clubs/create' }"
            >
              Créer un club
            </router-link>
            <router-link
              to="/clubs/join"
              @click="closeMobileMenu"
              class="block text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-6 py-2 rounded-md text-sm transition-all duration-300"
              :class="{ 'text-accent-500 bg-accent-50': $route.path === '/clubs/join' }"
            >
              Rejoindre un club
            </router-link>
            <router-link
              to="/clubs"
              @click="closeMobileMenu"
              class="block text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-6 py-2 rounded-md text-sm transition-all duration-300"
              :class="{ 'text-accent-500 bg-accent-50': $route.path === '/clubs' }"
            >
              Mes clubs
            </router-link>
          </div>
          
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/profile"
            @click="closeMobileMenu"
            class="text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/profile' }"
          >
            Profil
          </router-link>
          <div class="border-t border-sport-200 pt-2 mt-2">
            <button
              @click="handleLogout"
              class="w-full text-left text-sport-700 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300"
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

const authStore = useAuthStore();

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
