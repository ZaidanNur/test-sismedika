<template>
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-black/8">
        <div class="aspect-square overflow-hidden bg-gray-100">
            <img 
                v-if="food.images?.[0]?.url" 
                :src="food.images[0].url" 
                :alt="food.name"
                class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12">
                    <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zM21 18.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
                </svg>
            </div>
        </div>
        
        <div class="p-4">
            <h3 class="text-sm font-medium text-gray-800 mb-3 line-clamp-2">{{ food.name }}</h3>
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-800">Rp {{ formatPrice(food.price) }}</span>
                <button 
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-500 text-white border-none cursor-pointer transition-all hover:bg-indigo-600 hover:scale-105"
                    @click="$emit('add', food)"
                    title="Add to order"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    food: {
        type: Object,
        required: true
    }
});

defineEmits(['add']);

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}
</script>
