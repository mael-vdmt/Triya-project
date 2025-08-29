<script setup lang="ts">
import { onMounted } from 'vue';
import { useAuthStore } from './app/store';
import router from './app/router';

const authStore = useAuthStore();

onMounted(async () => {
  await authStore.initializeAuth();
});
</script>

<template>
  <router-view />
</template>

<style>
@import './style.css';
</style>
