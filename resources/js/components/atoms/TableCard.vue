<template>
    <div
        class="flex items-center justify-center w-16 h-16 rounded-lg cursor-pointer transition-all duration-200 shadow-sm hover:-translate-y-0.5 hover:shadow-md"
        :class="cardClass"
    >
        <span class="text-base font-semibold" :class="textClass">{{ tableNumber }}</span>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    tableNumber: {
        type: [Number, String],
        required: true
    },
    status: {
        type: String,
        required: true,
        validator: (value) => ['available', 'occupied', 'reserved', 'inactive'].includes(value)
    }
});

const statusConfig = {
    available: { 
        cardClass: 'bg-green-500 hover:bg-green-600', 
        textClass: 'text-white' 
    },
    occupied: { 
        cardClass: 'bg-gray-500 hover:bg-gray-600', 
        textClass: 'text-white' 
    },
    reserved: { 
        cardClass: 'bg-yellow-500 hover:bg-yellow-600', 
        textClass: 'text-white' 
    },
    inactive: { 
        cardClass: 'bg-gray-300 hover:bg-gray-400', 
        textClass: 'text-gray-600' 
    }
};

const cardClass = computed(() => statusConfig[props.status]?.cardClass || '');
const textClass = computed(() => statusConfig[props.status]?.textClass || 'text-white');
</script>
