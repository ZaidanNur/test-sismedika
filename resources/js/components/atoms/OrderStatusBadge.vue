<template>
    <span 
        class="inline-flex items-center text-xs font-medium px-3 py-1 rounded-full capitalize"
        :class="statusClasses"
    >
        {{ status }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true
    }
});

const statusClasses = computed(() => {
    switch (props.status) {
        case 'on progress':
            return 'bg-indigo-100 text-indigo-800';
        case 'closed':
            return 'bg-emerald-100 text-emerald-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
});
</script>
