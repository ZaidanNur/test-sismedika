import { createRouter, createWebHistory } from 'vue-router';
import TableAvailability from '@/pages/TableAvailability.vue';
import Login from '@/pages/Login.vue';
import POSTableSelect from '@/pages/pos/POSTableSelect.vue';
import POSOrder from '@/pages/pos/POSOrder.vue';
import FoodCategories from '@/pages/FoodCategories.vue';
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
    },
    {
        path: '/pos',
        name: 'pos',
        component: POSTableSelect,
        meta: { requiresAuth: true }
    },
    {
        path: '/food-categories',
        name: 'food-categories',
        component: FoodCategories,
        meta: { requiresAuth: true }
    },
    {
        path: '/pos/order/:tableId',
        name: 'pos-order',
        component: POSOrder,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    if (to.meta.guestOnly && authStore.isAuthenticated) {
        next({ name: 'pos' });
    } else if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login' });
    } else {
        next();
    }
});

export default router;
