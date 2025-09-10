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
  variant?: 'default' | 'sport';
}

const props = withDefaults(defineProps<Props>(), {
  padding: 'md',
  shadow: 'soft',
  variant: 'default',
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
    medium: 'shadow-sport',
    strong: 'shadow-energy',
  };
  
  const variantClasses = {
    default: 'bg-white border-sport-100',
    sport: 'card-sport',
  };
  
  return `rounded-2xl ${paddingClasses[props.padding]} ${shadowClasses[props.shadow]} ${variantClasses[props.variant]}`;
});
</script>
