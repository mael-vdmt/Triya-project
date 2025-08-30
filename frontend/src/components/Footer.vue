<template>
  <footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <p class="text-gray-600 text-sm">
          © {{ currentYear }} Triya. Tous droits réservés.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>
