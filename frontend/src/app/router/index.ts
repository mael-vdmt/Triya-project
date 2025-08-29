import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/app/store';

const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/pages/auth/Login.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('@/pages/auth/Register.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('@/pages/profile/Profile.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/app',
    name: 'App',
    component: () => import('@/pages/profile/Profile.vue'),
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
    await authStore.initializeAuth();
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next('/profile');
  } else {
    next();
  }
});

export default router;
