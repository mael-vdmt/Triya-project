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

          <router-link
            v-if="authStore.user?.has_clubs"
            to="/profile"
            class="text-sport-700 hover:text-accent-500 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300 hover:bg-accent-50"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/profile' }"
          >
            Profil
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
          
        </nav>

        <!-- Bouton déconnexion et sélecteur de club Desktop -->
        <div class="hidden md:flex items-center space-x-4">
          <!-- Affichage du club -->
          <div v-if="authStore.user?.has_clubs && clubStore.currentClub" class="flex items-center">
            <!-- Un seul club : affichage simple -->
            <div v-if="clubStore.clubs.length === 1" class="flex items-center text-sport-700 px-3 py-2 text-sm font-semibold">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <div class="flex flex-col">
                <span>{{ clubStore.currentClub.name }}</span>
                <span v-if="clubStore.currentClub.role" class="text-xs font-normal" :class="getRoleColor(clubStore.currentClub.role).split(' ')[0]">
                  {{ getRoleLabel(clubStore.currentClub.role) }}
                </span>
              </div>
            </div>
            
            <!-- Plusieurs clubs : dropdown -->
            <div v-else class="relative group">
              <button class="flex items-center text-sport-700 hover:text-accent-500 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300 hover:bg-accent-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div class="flex flex-col">
                  <span>{{ clubStore.currentClub.name }}</span>
                  <span v-if="clubStore.currentClub.role" class="text-xs font-normal" :class="getRoleColor(clubStore.currentClub.role).split(' ')[0]">
                    {{ getRoleLabel(clubStore.currentClub.role) }}
                  </span>
                </div>
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <div class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                <button
                  v-for="club in clubStore.clubs"
                  :key="club.id"
                  @click="selectClub(club.id)"
                  class="w-full text-left px-4 py-2 text-sm text-sport-700 hover:bg-accent-50 hover:text-accent-500 transition-colors flex items-center"
                  :class="{ 'bg-accent-50 text-accent-500': club.id === clubStore.selectedClubId }"
                >
                  <div class="flex-1">
                    <div class="font-medium">{{ club.name }}</div>
                    <div class="text-xs text-sport-500">{{ club.location }}</div>
                    <div v-if="club.role" class="text-xs font-medium" :class="getRoleColor(club.role).split(' ')[0]">
                      {{ getRoleLabel(club.role) }}
                    </div>
                  </div>
                  <svg v-if="club.id === clubStore.selectedClubId" class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          
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
          <!-- Affichage du club Mobile -->
          <div v-if="authStore.user?.has_clubs && clubStore.currentClub" class="px-3 py-2">
            <!-- Un seul club : affichage simple -->
            <div v-if="clubStore.clubs.length === 1" class="flex items-center text-sport-700 px-3 py-2 text-sm font-semibold">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <div class="flex flex-col">
                <span>{{ clubStore.currentClub.name }}</span>
                <span v-if="clubStore.currentClub.role" class="text-xs font-normal" :class="getRoleColor(clubStore.currentClub.role).split(' ')[0]">
                  {{ getRoleLabel(clubStore.currentClub.role) }}
                </span>
              </div>
            </div>
            
            <!-- Plusieurs clubs : sélecteur -->
            <div v-else>
              <div class="text-sport-700 text-sm font-semibold text-sport-500 mb-2">
                Club actuel
              </div>
              <div class="space-y-1">
                <button
                  v-for="club in clubStore.clubs"
                  :key="club.id"
                  @click="selectClubMobile(club.id)"
                  class="w-full text-left px-3 py-2 text-sm text-sport-700 hover:bg-accent-50 hover:text-accent-500 transition-colors rounded-md flex items-center"
                  :class="{ 'bg-accent-50 text-accent-500': club.id === clubStore.selectedClubId }"
                >
                  <div class="flex-1">
                    <div class="font-medium">{{ club.name }}</div>
                    <div class="text-xs text-sport-500">{{ club.location }}</div>
                    <div v-if="club.role" class="text-xs font-medium" :class="getRoleColor(club.role).split(' ')[0]">
                      {{ getRoleLabel(club.role) }}
                    </div>
                  </div>
                  <svg v-if="club.id === clubStore.selectedClubId" class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          
          <router-link
            v-if="authStore.user?.has_clubs"
            to="/dashboard"
            @click="closeMobileMenu"
            class="text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/dashboard' }"
          >
            Dashboard
          </router-link>

          <router-link
            v-if="authStore.user?.has_clubs"
            to="/profile"
            @click="closeMobileMenu"
            class="text-sport-700 hover:text-accent-500 hover:bg-accent-50 px-3 py-2 rounded-md text-sm font-semibold transition-all duration-300"
            :class="{ 'text-accent-500 bg-accent-50': $route.path === '/profile' }"
          >
            Profil
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
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../store';
import { useClubStore } from '../store';

const authStore = useAuthStore();
const clubStore = useClubStore();

// État du menu mobile
const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
};

// Charger les clubs au montage
onMounted(async () => {
  if (authStore.isAuthenticated) {
    try {
      await clubStore.getUserClubs();
    } catch (error) {
      console.error('Erreur lors du chargement des clubs:', error);
    }
  }
});

// Changer de club (desktop)
const selectClub = async (clubId: number) => {
  if (clubId !== clubStore.selectedClubId) {
    clubStore.selectClub(clubId);
    // Le Dashboard va automatiquement réagir au changement via le watcher
  }
};

// Changer de club (mobile)
const selectClubMobile = async (clubId: number) => {
  await selectClub(clubId);
  closeMobileMenu(); // Fermer le menu mobile après sélection
};

// Fonction pour afficher le rôle de manière lisible
const getRoleLabel = (role: string) => {
  const roleLabels: { [key: string]: string } = {
    'owner': 'Propriétaire',
    'admin': 'Administrateur',
    'member': 'Membre'
  };
  return roleLabels[role] || role;
};

// Fonction pour obtenir la couleur du rôle
const getRoleColor = (role: string) => {
  const roleColors: { [key: string]: string } = {
    'owner': 'text-yellow-600 bg-yellow-100',
    'admin': 'text-blue-600 bg-blue-100',
    'member': 'text-gray-600 bg-gray-100'
  };
  return roleColors[role] || 'text-gray-600 bg-gray-100';
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
