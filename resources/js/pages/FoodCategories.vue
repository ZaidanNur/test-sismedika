<template>
    <MainLayout restaurant-name="Restaurant POS">
        <div class="h-[calc(100vh-100px)] flex flex-col">
            <!-- Header with back button -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <button 
                        @click="goBack" 
                        class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 text-sm cursor-pointer transition-all hover:bg-gray-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                        </svg>
                        Back
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 m-0">Food Categories</h2>
                </div>
                <button 
                    @click="openCreateModal"
                    class="flex items-center gap-2 px-4 py-2.5 bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-600 transition-all"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    Add Category
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 bg-white rounded-2xl p-6 overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-indigo-500 m-0">Categories List</h3>
                    
                    <!-- Search -->
                    <div class="relative w-[300px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search categories..."
                            class="w-full py-2.5 pl-11 pr-4 border border-gray-200 rounded-full text-sm bg-gray-50 focus:outline-none focus:border-indigo-500 focus:bg-white"
                        />
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="categoryStore.isLoading && !categories.length" class="flex-1 flex flex-col items-center justify-center text-gray-500 py-20">
                    <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
                    <p>Loading categories...</p>
                </div>

                <!-- Error State -->
                <div v-else-if="categoryStore.error" class="text-center py-12">
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-400">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                    </div>
                    <p class="text-red-500 mb-4">{{ categoryStore.error }}</p>
                    <button @click="loadCategories" class="px-4 py-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors">
                        Try Again
                    </button>
                </div>

                <!-- Empty State -->
                <div v-else-if="!filteredCategories.length && !searchQuery" class="text-center py-12">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 text-indigo-300">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z M7 7h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No categories yet</h3>
                    <p class="text-gray-500 mb-6">Create your first food category to get started</p>
                    <button 
                        @click="openCreateModal"
                        class="px-6 py-3 bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-600 transition-all"
                    >
                        Create Category
                    </button>
                </div>

                <!-- No Search Results -->
                <div v-else-if="!filteredCategories.length && searchQuery" class="text-center py-12">
                    <p class="text-gray-500">No categories found for "{{ searchQuery }}"</p>
                </div>

                <!-- Categories Grid -->
                <div v-else class="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-4 overflow-y-auto pr-2 scrollbar-thin max-h-[calc(100vh-320px)]">
                    <div 
                        v-for="category in filteredCategories" 
                        :key="category.id"
                        class="group bg-gray-50 border border-gray-100 rounded-xl p-5 hover:bg-indigo-50 hover:border-indigo-200 transition-all duration-300"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z M7 7h.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800 m-0">{{ category.name }}</h4>
                                    <p class="text-sm text-gray-400 m-0">{{ category.foods?.length || 0 }} items</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button 
                                    @click="openEditModal(category)"
                                    class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors"
                                    title="Edit"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                </button>
                                <button 
                                    @click="confirmDelete(category)"
                                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Delete"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Teleport to="body">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40" @click="closeModal"></div>
                <div class="relative bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">
                            {{ isEditing ? 'Edit Category' : 'Add Category' }}
                        </h2>
                        <button @click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                            <input 
                                v-model="formData.name"
                                type="text"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                                placeholder="Enter category name"
                                required
                            />
                        </div>

                        <div class="flex gap-3">
                            <button 
                                type="button"
                                @click="closeModal"
                                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                :disabled="categoryStore.isLoading || !formData.name.trim()"
                                class="flex-1 px-4 py-3 bg-indigo-500 text-white font-semibold rounded-xl hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                            >
                                <span v-if="categoryStore.isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span>{{ isEditing ? 'Update' : 'Create' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <!-- Delete Confirmation Modal -->
        <Teleport to="body">
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40" @click="closeDeleteModal"></div>
                <div class="relative bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-400">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Category</h3>
                        <p class="text-gray-500 mb-6">
                            Are you sure you want to delete "<span class="text-gray-800 font-medium">{{ categoryToDelete?.name }}</span>"? This action cannot be undone.
                        </p>
                        <div class="flex gap-3">
                            <button 
                                @click="closeDeleteModal"
                                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                @click="handleDelete"
                                :disabled="categoryStore.isLoading"
                                class="flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
                            >
                                <span v-if="categoryStore.isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Toast Notification -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-4"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-4"
            >
                <div 
                    v-if="toast.show" 
                    class="fixed bottom-6 right-6 z-50 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3"
                    :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
                >
                    <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                    <span class="text-white font-medium">{{ toast.message }}</span>
                </div>
            </Transition>
        </Teleport>
    </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useFoodCategoryStore } from '@/stores/foodCategory';
import MainLayout from '@/components/templates/MainLayout.vue';

const router = useRouter();
const categoryStore = useFoodCategoryStore();

// Search
const searchQuery = ref('');

// Computed
const categories = computed(() => categoryStore.categories);
const filteredCategories = computed(() => {
    if (!searchQuery.value) return categories.value;
    const query = searchQuery.value.toLowerCase();
    return categories.value.filter(cat => cat.name.toLowerCase().includes(query));
});

// Modal states
const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const categoryToDelete = ref(null);
const formData = ref({
    name: ''
});

// Toast state
const toast = ref({
    show: false,
    message: '',
    type: 'success'
});

// Methods
const loadCategories = async () => {
    await categoryStore.fetchCategories();
};

const goBack = () => {
    router.push('/pos');
};

const openCreateModal = () => {
    isEditing.value = false;
    formData.value = { name: '' };
    showModal.value = true;
};

const openEditModal = (category) => {
    isEditing.value = true;
    formData.value = { name: category.name };
    categoryStore.currentCategory = category;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    formData.value = { name: '' };
    categoryStore.clearCurrentCategory();
};

const confirmDelete = (category) => {
    categoryToDelete.value = category;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    categoryToDelete.value = null;
};

const showToast = (message, type = 'success') => {
    toast.value = { show: true, message, type };
    setTimeout(() => {
        toast.value.show = false;
    }, 3000);
};

const handleSubmit = async () => {
    if (!formData.value.name.trim()) return;

    let result;
    if (isEditing.value) {
        result = await categoryStore.updateCategory(categoryStore.currentCategory.id, formData.value);
        if (result) {
            showToast('Category updated successfully');
            closeModal();
        } else {
            showToast(categoryStore.error || 'Failed to update category', 'error');
        }
    } else {
        result = await categoryStore.createCategory(formData.value);
        if (result) {
            showToast('Category created successfully');
            closeModal();
        } else {
            showToast(categoryStore.error || 'Failed to create category', 'error');
        }
    }
};

const handleDelete = async () => {
    if (!categoryToDelete.value) return;

    const result = await categoryStore.deleteCategory(categoryToDelete.value.id);
    if (result) {
        showToast('Category deleted successfully');
        closeDeleteModal();
    } else {
        showToast(categoryStore.error || 'Failed to delete category', 'error');
    }
};

onMounted(() => {
    loadCategories();
});
</script>

<style scoped>
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
.scrollbar-thin::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}
</style>
