<template>
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h1 class="text-2xl font-bold text-gray-800 m-0">Table Management</h1>
            <router-link 
                to="/pos" 
                class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white text-sm font-semibold rounded-xl no-underline shadow-md shadow-indigo-200 hover:shadow-lg hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all duration-200"
            >
                Login
            </router-link>
        </div>
        <StatusLegend />
    </div>
</template>

<script setup>
import StatusLegend from '@/components/molecules/StatusLegend.vue';
</script>
