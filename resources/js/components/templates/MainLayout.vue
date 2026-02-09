<template>
    <div class="flex min-h-screen bg-gray-50">
        <SideNavigation />
        
        <div class="flex-1 ml-60 flex flex-col transition-all duration-300 max-md:ml-[72px]">
            <!-- Header -->
            <header class="flex items-center justify-between px-8 py-4 bg-white border-b border-gray-100 sticky top-0 z-40 max-md:px-4">
                <h1 class="text-xl font-semibold text-gray-800 m-0">{{ restaurantName }}</h1>
                <div class="flex items-center gap-4">
                    <slot name="header-actions"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8 overflow-y-auto max-md:p-4">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script setup>
import SideNavigation from '@/components/organisms/SideNavigation.vue';

defineProps({
    restaurantName: {
        type: String,
        default: 'Restaurant POS'
    }
});
</script>
