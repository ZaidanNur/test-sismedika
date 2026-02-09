<template>
    <div class="min-h-screen bg-gray-100 py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <PageHeader />

            <div class="mt-4">
                <!-- Loading State -->
                <div v-if="tableStore.isLoading" class="flex flex-col items-center justify-center py-16 text-gray-500">
                    <div class="w-10 h-10 border-3 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
                    <p class="mt-4">Loading tables...</p>
                </div>

                <!-- Error State -->
                <div v-else-if="tableStore.error" class="flex flex-col items-center justify-center py-16 text-red-600 bg-white rounded-xl border border-red-200">
                    <p>{{ tableStore.error }}</p>
                    <button 
                        @click="tableStore.fetchTables()" 
                        class="mt-4 px-6 py-2 bg-blue-500 text-white border-none rounded-lg font-medium cursor-pointer transition-colors hover:bg-blue-600"
                    >
                        Try Again
                    </button>
                </div>

                <!-- Content -->
                <div v-else class="grid grid-cols-[1fr_280px] gap-6 items-start max-[900px]:grid-cols-1">
                    <TableGrid :tables="tableStore.tables" class="min-w-0" />
                    <QuickStats :statistics="tableStore.statistics" class="sticky top-8 max-[900px]:static" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useTableStore } from '@/stores/table';
import PageHeader from '@/components/organisms/PageHeader.vue';
import TableGrid from '@/components/organisms/TableGrid.vue';
import QuickStats from '@/components/molecules/QuickStats.vue';

const tableStore = useTableStore();

onMounted(() => {
    tableStore.fetchTables();
});
</script>

<style>
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
