<template>
  <div v-if="show" :class="alertClasses" role="alert">
    <div class="flex">
      <div class="flex-shrink-0">
        <component :is="iconComponent" class="h-5 w-5" />
      </div>
      <div class="ml-3">
        <p class="text-sm font-medium">
          <slot />
        </p>
      </div>
      <div v-if="dismissible" class="ml-auto pl-3">
        <button
          type="button"
          class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
          :class="dismissButtonClasses"
          @click="$emit('dismiss')"
        >
          <span class="sr-only">Dismiss</span>
          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  type?: 'success' | 'error' | 'warning' | 'info';
  dismissible?: boolean;
  show?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'info',
  dismissible: false,
  show: true,
});

defineEmits<{
  dismiss: [];
}>();

const alertClasses = computed(() => {
  const baseClasses = 'rounded-2xl p-4 border';
  
  const typeClasses = {
    success: 'bg-green-50 border-green-200 text-green-800',
    error: 'bg-red-50 border-red-200 text-red-800',
    warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
    info: 'bg-blue-50 border-blue-200 text-blue-800',
  };
  
  return `${baseClasses} ${typeClasses[props.type]}`;
});

const dismissButtonClasses = computed(() => {
  const typeClasses = {
    success: 'text-green-400 hover:text-green-500 focus:ring-green-400',
    error: 'text-red-400 hover:text-red-500 focus:ring-red-400',
    warning: 'text-yellow-400 hover:text-yellow-500 focus:ring-yellow-400',
    info: 'text-blue-400 hover:text-blue-500 focus:ring-blue-400',
  };
  
  return typeClasses[props.type];
});

const iconComponent = computed(() => {
  const icons = {
    success: 'CheckCircleIcon',
    error: 'XCircleIcon',
    warning: 'ExclamationTriangleIcon',
    info: 'InformationCircleIcon',
  };
  
  return icons[props.type];
});
</script>
