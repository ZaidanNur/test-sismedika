import { createRouter, createWebHistory } from 'vue-router';
import TableAvailability from '@/pages/TableAvailability.vue';
import Login from '@/pages/Login.vue';
import POSTableSelect from '@/pages/pos/POSTableSelect.vue';
import POSOrder from '@/pages/pos/POSOrder.vue';
import FoodCategories from '@/pages/FoodCategories.vue';
import Foods from '@/pages/Foods.vue';
import Orders from '@/pages/Orders.vue';
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
        meta: { requiresAuth: true, roles: ['Kasir', 'Pelayan'] }
    },
    {
        path: '/food-categories',
        name: 'food-categories',
        component: FoodCategories,
        meta: { requiresAuth: true, roles: ['Pelayan'] }
    },
    {
        path: '/foods',
        name: 'foods',
        component: Foods,
        meta: { requiresAuth: true, roles: ['Pelayan'] }
    },
    {
        path: '/orders',
        name: 'orders',
        component: Orders,
        meta: { requiresAuth: true, roles: ['Kasir', 'Pelayan'] }
    },
    {
        path: '/pos/order/:tableId',
        name: 'pos-order',
        component: POSOrder,
        meta: { requiresAuth: true, roles: ['Kasir', 'Pelayan'] }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const defaultRoute = authStore.userRole === 'Kasir' ? 'orders' : 'home';

    if (to.meta.guestOnly && authStore.isAuthenticated) {
        next({ name: defaultRoute });
    } else if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login' });
    } else if (to.meta.roles && !to.meta.roles.includes(authStore.userRole)) {
        next({ name: defaultRoute });
    } else {
        next();
    }
});

export default router;
