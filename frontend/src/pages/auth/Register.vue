<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-50 to-blue-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Create account</h1>
        <p class="text-gray-600">Join us today</p>
      </div>

      <UiCard>
        <form @submit.prevent="handleRegister" class="space-y-6">
          <UiAlert
            v-if="authStore.error"
            type="error"
            :show="!!authStore.error"
            dismissible
            @dismiss="authStore.clearError"
          >
            {{ authStore.error }}
          </UiAlert>

          <UiInput
            v-model="form.name"
            label="Full name"
            type="text"
            placeholder="Enter your full name"
            required
            :error="errors.name"
          />

          <UiInput
            v-model="form.email"
            label="Email address"
            type="email"
            placeholder="Enter your email"
            required
            :error="errors.email"
          />

          <UiInput
            v-model="form.password"
            label="Password"
            type="password"
            placeholder="Create a password"
            required
            :error="errors.password"
          />

          <UiInput
            v-model="form.password_confirmation"
            label="Confirm password"
            type="password"
            placeholder="Confirm your password"
            required
            :error="errors.password_confirmation"
          />

          <UiButton
            type="submit"
            :loading="authStore.loading"
            loading-text="Creating account..."
            class="w-full"
          >
            Create account
          </UiButton>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <router-link
              to="/login"
              class="font-medium text-primary-600 hover:text-primary-500 transition-colors"
            >
              Sign in
            </router-link>
          </p>
        </div>
      </UiCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useAuthStore } from '../../app/store';
import UiCard from '../../components/ui/UiCard.vue';
import UiInput from '../../components/ui/UiInput.vue';
import UiButton from '../../components/ui/UiButton.vue';
import UiAlert from '../../components/ui/UiAlert.vue';

const authStore = useAuthStore();

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const errors = ref<Record<string, string>>({});

const handleRegister = async () => {
  errors.value = {};
  
  // Basic validation
  if (!form.name) {
    errors.value.name = 'Name is required';
  }
  if (!form.email) {
    errors.value.email = 'Email is required';
  }
  if (!form.password) {
    errors.value.password = 'Password is required';
  }
  if (form.password !== form.password_confirmation) {
    errors.value.password_confirmation = 'Passwords do not match';
  }
  
  if (Object.keys(errors.value).length > 0) {
    return;
  }

  try {
    await authStore.register({
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    });
  } catch (error) {
    // Error is handled by the store
  }
};
</script>
