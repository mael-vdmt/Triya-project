<template>
  <div class="min-h-screen flex flex-col sport-bg">
    <!-- Header -->
    <Header />
    
    <!-- Contenu principal -->
    <main class="flex-1 py-4">
      <slot />
    </main>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup lang="ts">
import Header from '../components/Header.vue';
import Footer from '../components/Footer.vue';
</script>
