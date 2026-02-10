<template>
    <aside 
        class="flex flex-col h-screen fixed left-0 top-0 z-50 p-4 bg-white border-r border-gray-100 transition-all duration-300"
        :class="isCollapsed ? 'w-[72px]' : 'w-60'"
    >
        <!-- Logo -->
        <div class="flex items-center gap-3 px-2 mb-8">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl text-white shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
            </div>
            <span v-show="!isCollapsed" class="text-lg font-bold text-gray-800 whitespace-nowrap">Restaurant</span>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-1 flex-1">
            <router-link 
                v-for="item in menuItems" 
                :key="item.path"
                :to="item.path"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-gray-500 no-underline transition-all hover:bg-gray-100 hover:text-gray-800"
                :class="{ 'bg-indigo-50 text-indigo-500': isActive(item.path) }"
            >
                <component :is="item.icon" class="w-6 h-6 shrink-0" />
                <span v-show="!isCollapsed" class="whitespace-nowrap overflow-hidden text-sm">{{ item.label }}</span>
            </router-link>
        </nav>

        <!-- Bottom Actions -->
        <div class="flex flex-col gap-2 pt-4 border-t border-gray-100">
            <button 
                @click="toggleCollapse" 
                class="flex items-center justify-center p-3 rounded-xl text-gray-500 bg-transparent border-none cursor-pointer transition-all hover:bg-gray-100 hover:text-gray-800"
                :title="isCollapsed ? 'Expand' : 'Collapse'"
            >
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 24 24" 
                    fill="currentColor" 
                    class="w-6 h-6 transition-transform duration-300"
                    :class="{ 'rotate-180': isCollapsed }"
                >
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
            </button>

            <button 
                @click="handleLogout" 
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-red-500 bg-transparent border-none cursor-pointer transition-all hover:bg-red-50 w-full text-sm"
                :title="isCollapsed ? 'Logout' : ''"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 shrink-0">
                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                </svg>
                <span v-show="!isCollapsed" class="whitespace-nowrap overflow-hidden">Logout</span>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { ref, h, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

const isCollapsed = ref(false);

const HomeIcon = {
    render() {
        return h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'currentColor' }, [
            h('path', { d: 'M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z' })
        ]);
    }
};

const POSIcon = {
    render() {
        return h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'currentColor' }, [
            h('path', { d: 'M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z' })
        ]);
    }
};

const OrdersIcon = {
    render() {
        return h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'currentColor' }, [
            h('path', { d: 'M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z' })
        ]);
    }
};

const ChatIcon = {
    render() {
        return h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'currentColor' }, [
            h('path', { d: 'M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z' })
        ]);
    }
};

const FoodIcon = {
    render() {
        return h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'currentColor' }, [
            h('path', { d: 'M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z' })
        ]);
    }
};

const FoodCategoryIcon = {
    render() {
        return h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
            h('path', { d: 'M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z' }),
            h('line', { x1: '7', y1: '7', x2: '7.01', y2: '7' })
        ]);
    }
};

const allMenuItems = [
    { path: '/', label: 'Home', icon: HomeIcon, roles: ['Pelayan'] },
    { path: '/pos', label: 'POS', icon: POSIcon, roles: ['Kasir', 'Pelayan'] },
    { path: '/orders', label: 'Orders', icon: OrdersIcon, roles: ['Kasir', 'Pelayan'] },
    { path: '/food-categories', label: 'Food Categories', icon: FoodCategoryIcon, roles: ['Pelayan'] },
    { path: '/foods', label: 'Food', icon: FoodIcon, roles: ['Pelayan'] }
];

const menuItems = computed(() => {
    const role = authStore.userRole;
    return allMenuItems.filter(item => !item.roles || item.roles.includes(role));
});

function isActive(path) {
    if (path === '/') {
        return route.path === '/';
    }
    return route.path.startsWith(path);
}

function toggleCollapse() {
    isCollapsed.value = !isCollapsed.value;
}

async function handleLogout() {
    authStore.logout();
    router.push('/login');
}
</script>
