<template>
    <span class="inline-flex items-center gap-1.5 text-xs font-medium">
        <span
            class="w-2.5 h-2.5 rounded-full"
            :class="dotClass"
        ></span>
        <span v-if="showLabel" class="text-gray-600">{{ label }}</span>
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true,
        validator: (value) => ['available', 'occupied', 'reserved', 'inactive'].includes(value)
    },
    showLabel: {
        type: Boolean,
        default: false
    }
});

const statusConfig = {
    available: { label: 'Available', dotClass: 'bg-green-500' },
    occupied: { label: 'Occupied', dotClass: 'bg-gray-500' },
    reserved: { label: 'Reserved', dotClass: 'bg-yellow-500' },
    inactive: { label: 'Inactive', dotClass: 'bg-gray-300' }
};

const dotClass = computed(() => statusConfig[props.status]?.dotClass || '');
const label = computed(() => statusConfig[props.status]?.label || props.status);
</script>
