<template>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Quick Stats</h3>
        <div class="flex flex-col gap-3">
            <StatItem
                :value="statistics.available"
                label="Available Tables"
                status="available"
            />
            <StatItem
                :value="statistics.occupied"
                label="Occupied Tables"
                status="occupied"
            />
            <StatItem
                :value="statistics.reserved"
                label="Reserved Tables"
                status="reserved"
            />
            <StatItem
                :value="statistics.inactive"
                label="Inactive Tables"
                status="inactive"
            />
        </div>
    </div>
</template>

<script setup>
import StatItem from '@/components/atoms/StatItem.vue';

defineProps({
    statistics: {
        type: Object,
        required: true,
        default: () => ({
            available: 0,
            occupied: 0,
            reserved: 0,
            inactive: 0
        })
    }
});
</script>
