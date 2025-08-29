<template>
  <div class="space-y-2">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      @input="handleInput"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      class="input-field"
      :class="{
        'border-red-300 focus:ring-red-500 focus:border-red-500': error,
        'opacity-50 cursor-not-allowed': disabled
      }"
    />
    <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
    <p v-if="help" class="text-sm text-gray-500">{{ help }}</p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: string;
  label?: string;
  type?: string;
  placeholder?: string;
  required?: boolean;
  disabled?: boolean;
  error?: string;
  help?: string;
  id?: string;
}

withDefaults(defineProps<Props>(), {
  type: 'text',
  required: false,
  disabled: false,
  id: undefined,
});

defineEmits<{
  'update:modelValue': [value: string];
}>();

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
};
</script>
