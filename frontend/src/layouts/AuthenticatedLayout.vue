<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-primary-50 to-blue-50">
    <!-- Header -->
    <Header />
    
    <!-- Contenu principal -->
    <main class="flex-1 py-8">
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
