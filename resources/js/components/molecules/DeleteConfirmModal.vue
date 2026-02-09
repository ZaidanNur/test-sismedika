<template>
    <Modal :show="show" title="Delete Confirmation" max-width="sm" @close="$emit('cancel')">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-400">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ title }}</h3>
            <p class="text-gray-500 mb-6">
                Are you sure you want to delete "<span class="text-gray-800 font-medium">{{ itemName }}</span>"? This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button 
                    @click="$emit('cancel')"
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    @click="$emit('confirm')"
                    :disabled="isLoading"
                    class="flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                >
                    <span v-if="isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/components/atoms/Modal.vue';

defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Delete Item'
    },
    itemName: {
        type: String,
        default: ''
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});

defineEmits(['confirm', 'cancel']);
</script>

<style scoped>
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
