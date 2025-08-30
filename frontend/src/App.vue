<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useAuthStore } from './store';

const authStore = useAuthStore();
const isInitializing = ref(false);

onMounted(async () => {
  // Initialize auth only once when app starts
  if (!authStore.isInitialized && !isInitializing.value) {
    isInitializing.value = true;
    try {
      await authStore.initializeAuth();
    } catch (error) {
      console.error('Failed to initialize auth on mount:', error);
    } finally {
      isInitializing.value = false;
    }
  }
});
</script>

<template>
  <router-view />
</template>

<style>
@import './style.css';
</style>
