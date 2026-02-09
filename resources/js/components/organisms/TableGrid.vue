<template>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="grid grid-cols-6 gap-4 md:grid-cols-4 sm:grid-cols-3">
            <TableCard
                v-for="table in tables"
                :key="table.id"
                :table-number="table.id"
                :status="table.status"
            />
        </div>
    </div>
</template>

<script setup>
import TableCard from '@/components/atoms/TableCard.vue';

defineProps({
    tables: {
        type: Array,
        required: true,
        default: () => []
    }
});
</script>
