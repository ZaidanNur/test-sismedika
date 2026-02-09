import { createRouter, createWebHistory } from 'vue-router';
import TableAvailability from '@/pages/TableAvailability.vue';
import Login from '@/pages/Login.vue';
import { useAuthStore } from '@/stores/auth';

const routes = [
    {
        path: '/',
        name: 'home',
        component: TableAvailability
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guestOnly: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guard
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // If route is for guests only and user is logged in, redirect to home
    if (to.meta.guestOnly && authStore.isAuthenticated) {
        next({ name: 'home' });
    } else {
        next();
    }
});

export default router;
