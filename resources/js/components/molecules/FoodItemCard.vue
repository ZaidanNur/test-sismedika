<template>
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-black/8 group">
        <div class="aspect-square overflow-hidden bg-gray-100 relative">
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
            
            <!-- Action buttons overlay -->
            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button 
                    @click.stop="$emit('edit', food)"
                    class="p-2 bg-white/90 text-amber-500 hover:bg-amber-50 rounded-lg shadow-sm transition-colors"
                    title="Edit"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                </button>
                <button 
                    @click.stop="$emit('delete', food)"
                    class="p-2 bg-white/90 text-red-500 hover:bg-red-50 rounded-lg shadow-sm transition-colors"
                    title="Delete"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-4">
            <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-1">{{ food.name }}</h3>
            <p class="text-xs text-gray-500 mb-3 line-clamp-2 min-h-[2rem]">{{ food.description || 'No description' }}</p>
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-indigo-500">Rp {{ formatPrice(food.price) }}</span>
                <span v-if="food.category" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
                    {{ food.category.name }}
                </span>
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

defineEmits(['edit', 'delete']);

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}
</script>
