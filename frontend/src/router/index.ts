import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store';

const routes = [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../pages/auth/Login.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../pages/auth/Register.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../pages/dashboard/Dashboard.vue'),
    meta: { requiresAuth: true, requiresClubs: true },
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('../pages/profile/Profile.vue'),
    meta: { requiresAuth: true, requiresClubs: true },
  },
  {
    path: '/onboarding',
    name: 'Onboarding',
    component: () => import('../pages/onboarding/Onboarding.vue'),
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  
  // Initialize auth state if not already done
  if (!authStore.user && !authStore.loading) {
    try {
      await authStore.initializeAuth();
    } catch (error) {
      console.error('Failed to initialize auth:', error);
    }
  }

  // Check if route requires authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // User is not authenticated, redirect to login
    next('/login');
    return;
  }

  // Check if route requires clubs
  if (to.meta.requiresClubs && authStore.isAuthenticated && !authStore.user?.has_clubs) {
    // User is authenticated but has no clubs, redirect to onboarding
    next('/onboarding');
    return;
  }

  // Check if route requires guest (not authenticated)
  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    // User is already authenticated, redirect to home page
    next('/');
    return;
  }

  // Allow navigation to continue
  next();
});

export default router;

