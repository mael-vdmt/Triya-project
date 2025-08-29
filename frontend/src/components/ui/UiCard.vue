<template>
  <div class="card" :class="cardClasses">
    <div v-if="$slots.header" class="mb-6">
      <slot name="header" />
    </div>
    
    <div class="space-y-4">
      <slot />
    </div>
    
    <div v-if="$slots.footer" class="mt-6 pt-6 border-t border-gray-100">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  padding?: 'sm' | 'md' | 'lg';
  shadow?: 'none' | 'soft' | 'medium' | 'strong';
}

const props = withDefaults(defineProps<Props>(), {
  padding: 'md',
  shadow: 'soft',
});

const cardClasses = computed(() => {
  const paddingClasses = {
    sm: 'p-4',
    md: 'p-8',
    lg: 'p-12',
  };
  
  const shadowClasses = {
    none: 'shadow-none',
    soft: 'shadow-soft',
    medium: 'shadow-lg',
    strong: 'shadow-2xl',
  };
  
  return `bg-white rounded-2xl border border-gray-100 ${paddingClasses[props.padding]} ${shadowClasses[props.shadow]}`;
});
</script>
