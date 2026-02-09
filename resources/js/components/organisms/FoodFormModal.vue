<template>
    <Modal :show="show" :title="isEditing ? 'Edit Food' : 'Add Food'" max-width="lg" @close="$emit('close')">
        <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-2 gap-6">
                <!-- Left Column - Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Food Image</label>
                    <div 
                        class="aspect-square border-2 border-dashed border-gray-200 rounded-xl overflow-hidden cursor-pointer transition-all hover:border-indigo-400 hover:bg-indigo-50/50"
                        :class="{ 'border-indigo-400 bg-indigo-50/50': isDragging }"
                        @click="triggerFileInput"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop"
                    >
                        <div v-if="imagePreview" class="relative w-full h-full group">
                            <img 
                                :src="imagePreview" 
                                alt="Preview" 
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button 
                                    type="button"
                                    @click.stop="removeImage"
                                    class="p-3 bg-white/90 text-red-500 rounded-full hover:bg-red-50 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 mb-3">
                                <path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V8h-3V5H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8h-3zM5 19l3-4 2 3 3-4 4 5H5z"/>
                            </svg>
                            <span class="text-sm text-center">Click or drag image here</span>
                            <span class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP</span>
                        </div>
                    </div>
                    <input 
                        ref="fileInput"
                        type="file" 
                        accept="image/jpeg,image/jpg,image/png,image/webp"
                        class="hidden"
                        @change="handleFileSelect"
                    />
                </div>

                <!-- Right Column - Form Fields -->
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input 
                            v-model="formData.name"
                            type="text"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                            placeholder="Enter food name"
                            required
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            v-model="formData.description"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all resize-none"
                            placeholder="Enter food description"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input 
                                v-model.number="formData.price"
                                type="number"
                                min="0"
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                                placeholder="0"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select 
                            v-model="formData.category_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all bg-white"
                            required
                        >
                            <option value="" disabled>Select category</option>
                            <option 
                                v-for="category in categories" 
                                :key="category.id" 
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100">
                <button 
                    type="button"
                    @click="$emit('close')"
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    :disabled="isLoading || !isFormValid"
                    class="flex-1 px-4 py-3 bg-indigo-500 text-white font-semibold rounded-xl hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                >
                    <span v-if="isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span>{{ isEditing ? 'Update' : 'Create' }}</span>
                </button>
            </div>
        </form>
    </Modal>
</template>

<script setup>
import { ref, computed, watch, onUnmounted } from 'vue';
import Modal from '@/components/atoms/Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    food: {
        type: Object,
        default: null
    },
    categories: {
        type: Array,
        default: () => []
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['submit', 'close']);

const fileInput = ref(null);
const isDragging = ref(false);
const selectedFile = ref(null);
const imagePreview = ref(null);

const formData = ref({
    name: '',
    description: '',
    price: '',
    category_id: ''
});

const isEditing = computed(() => !!props.food);

const isFormValid = computed(() => {
    return formData.value.name.trim() && 
           formData.value.price > 0 && 
           formData.value.category_id;
});

// Watch for food prop changes to populate form
watch(() => props.food, (food) => {
    if (food) {
        formData.value = {
            name: food.name || '',
            description: food.description || '',
            price: food.price || '',
            category_id: food.category_id || ''
        };
        // Set existing image preview
        if (food.images?.[0]?.url) {
            imagePreview.value = food.images[0].url;
        }
    } else {
        resetForm();
    }
}, { immediate: true });

// Reset form when modal closes
watch(() => props.show, (show) => {
    if (!show) {
        resetForm();
    }
});

function resetForm() {
    formData.value = {
        name: '',
        description: '',
        price: '',
        category_id: ''
    };
    selectedFile.value = null;
    imagePreview.value = null;
}

function triggerFileInput() {
    fileInput.value?.click();
}

function handleFileSelect(event) {
    const file = event.target.files?.[0];
    if (file) {
        processFile(file);
    }
}

function handleDrop(event) {
    isDragging.value = false;
    const file = event.dataTransfer.files?.[0];
    if (file && file.type.startsWith('image/')) {
        processFile(file);
    }
}

function processFile(file) {
    selectedFile.value = file;
    
    // Revoke previous URL if exists
    if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
    
    imagePreview.value = URL.createObjectURL(file);
}

function removeImage() {
    if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
    selectedFile.value = null;
    imagePreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function handleSubmit() {
    const submitData = new FormData();
    submitData.append('name', formData.value.name);
    submitData.append('description', formData.value.description || '');
    submitData.append('price', formData.value.price);
    submitData.append('category_id', formData.value.category_id);
    
    if (selectedFile.value) {
        submitData.append('images[]', selectedFile.value);
    }
    
    emit('submit', submitData);
}

// Cleanup on unmount
onUnmounted(() => {
    if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
});
</script>

<style scoped>
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
