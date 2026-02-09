<template>
    <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-b-0">
        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 shrink-0">
            <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1z"/>
                </svg>
            </div>
        </div>

        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-medium text-gray-800 mb-1 truncate">{{ item.name }}</h4>
            <span class="text-xs text-gray-500">Rp {{ formatPrice(item.price) }}</span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Delete button -->
            <button 
                class="flex items-center justify-center w-6 h-6 rounded-md bg-gray-100 text-gray-500 border-none cursor-pointer transition-all hover:bg-red-500 hover:text-white mr-1"
                @click="$emit('remove', item.food_id)"
                title="Hapus item"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
            </button>

            <button 
                class="flex items-center justify-center w-6 h-6 rounded-md bg-red-50 text-red-500 border-none cursor-pointer transition-all hover:bg-red-100"
                @click="$emit('decrement', item.food_id)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M19 13H5v-2h14v2z"/>
                </svg>
            </button>
            
            <span class="text-sm font-semibold text-gray-800 min-w-[20px] text-center">{{ item.quantity }}</span>
            
            <button 
                class="flex items-center justify-center w-6 h-6 rounded-md bg-indigo-50 text-indigo-500 border-none cursor-pointer transition-all hover:bg-indigo-100"
                @click="$emit('increment', item.food_id)"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    item: {
        type: Object,
        required: true
    }
});

defineEmits(['increment', 'decrement', 'remove']);

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}
</script>
