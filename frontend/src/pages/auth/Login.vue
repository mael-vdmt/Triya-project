<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-50 to-blue-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Welcome back</h1>
        <p class="text-gray-600">Sign in to your account</p>
      </div>

      <UiCard>
        <form @submit.prevent="handleLogin" class="space-y-6">
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
            placeholder="Enter your password"
            required
            :error="errors.password"
          />

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
              />
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
          </div>

          <UiButton
            type="submit"
            :loading="authStore.loading"
            loading-text="Signing in..."
            class="w-full"
          >
            Sign in
          </UiButton>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <router-link
              to="/register"
              class="font-medium text-primary-600 hover:text-primary-500 transition-colors"
            >
              Sign up
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
  email: '',
  password: '',
  remember: false,
});

const errors = ref<Record<string, string>>({});

const handleLogin = async () => {
  errors.value = {};
  
  // Basic validation
  if (!form.email) {
    errors.value.email = 'Email is required';
  }
  if (!form.password) {
    errors.value.password = 'Password is required';
  }
  
  if (Object.keys(errors.value).length > 0) {
    return;
  }

  try {
    await authStore.login({
      email: form.email,
      password: form.password,
      remember: form.remember,
    });
  } catch (error) {
    // Error is handled by the store
  }
};
</script>
