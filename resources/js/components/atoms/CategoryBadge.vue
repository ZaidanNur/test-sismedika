<template>
    <span 
        class="inline-flex items-center justify-center px-5 py-2 rounded-full text-sm font-medium cursor-pointer transition-all whitespace-nowrap border"
        :class="[
            active 
                ? 'bg-gray-800 text-white border-gray-800 hover:bg-gray-700 hover:border-gray-700' 
                : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100 hover:border-gray-300'
        ]"
        @click="$emit('click')"
    >
        {{ label }}
    </span>
</template>

<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    active: {
        type: Boolean,
        default: false
    }
});

defineEmits(['click']);
</script>
