<template>
    <div class="flex flex-col p-4 bg-gray-50 rounded-lg border border-gray-200">
        <span class="text-2xl font-bold leading-none" :class="valueClass">{{ value }}</span>
        <span class="text-xs text-gray-500 mt-1">{{ label }}</span>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: {
        type: [Number, String],
        required: true
    },
    label: {
        type: String,
        required: true
    },
    status: {
        type: String,
        default: null,
        validator: (value) => value === null || ['available', 'occupied', 'reserved', 'inactive'].includes(value)
    }
});

const colorMap = {
    available: 'text-green-500',
    occupied: 'text-gray-500',
    reserved: 'text-yellow-500',
    inactive: 'text-gray-300'
};

const valueClass = computed(() => props.status ? colorMap[props.status] : 'text-gray-800');
</script>
