<template>
    <div class="flex flex-col gap-2">
        <span class="text-sm font-medium text-gray-700">Table Status</span>
        <div class="flex flex-wrap gap-4">
            <StatusBadge
                v-for="status in statuses"
                :key="status"
                :status="status"
                :show-label="true"
            />
        </div>
    </div>
</template>

<script setup>
import StatusBadge from '@/components/atoms/StatusBadge.vue';

const statuses = ['available', 'occupied', 'reserved', 'inactive'];
</script>
