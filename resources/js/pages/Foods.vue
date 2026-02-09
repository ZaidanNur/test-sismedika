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
                    <h2 class="text-xl font-semibold text-gray-800 m-0">Food Menu</h2>
                </div>
                <button 
                    @click="openCreateModal"
                    class="flex items-center gap-2 px-4 py-2.5 bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-600 transition-all"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    Add Food
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 bg-white rounded-2xl p-6 overflow-hidden flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-indigo-500 m-0">Food Items</h3>
                    
                    <!-- Search -->
                    <div class="relative w-[300px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search foods..."
                            class="w-full py-2.5 pl-11 pr-4 border border-gray-200 rounded-full text-sm bg-gray-50 focus:outline-none focus:border-indigo-500 focus:bg-white"
                        />
                    </div>
                </div>

                <!-- Categories -->
                <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-thin">
                    <CategoryBadge 
                        label="All"
                        :active="!selectedCategory"
                        @click="selectedCategory = null"
                    />
                    <CategoryBadge 
                        v-for="category in foodStore.categories"
                        :key="category.id"
                        :label="category.name"
                        :active="selectedCategory === category.id"
                        @click="selectedCategory = category.id"
                    />
                </div>

                <!-- Loading State -->
                <div v-if="foodStore.isLoading && !foods.length" class="flex-1 flex flex-col items-center justify-center text-gray-500">
                    <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
                    <p>Loading foods...</p>
                </div>

                <!-- Error State -->
                <div v-else-if="foodStore.error && !foods.length" class="text-center py-12">
                    <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-red-400">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                    </div>
                    <p class="text-red-500 mb-4">{{ foodStore.error }}</p>
                    <button @click="loadFoods" class="px-4 py-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors">
                        Try Again
                    </button>
                </div>

                <!-- Empty State -->
                <div v-else-if="!filteredFoods.length && !searchQuery" class="text-center py-12">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 text-indigo-300">
                            <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No food items yet</h3>
                    <p class="text-gray-500 mb-6">Create your first food item to get started</p>
                    <button 
                        @click="openCreateModal"
                        class="px-6 py-3 bg-indigo-500 text-white font-medium rounded-lg hover:bg-indigo-600 transition-all"
                    >
                        Create Food
                    </button>
                </div>

                <!-- No Search Results -->
                <div v-else-if="!filteredFoods.length && searchQuery" class="text-center py-12">
                    <p class="text-gray-500">No foods found for "{{ searchQuery }}"</p>
                </div>

                <!-- Food Grid -->
                <div v-else class="flex-1 grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-4 overflow-y-auto pr-2 scrollbar-thin">
                    <FoodItemCard 
                        v-for="food in filteredFoods"
                        :key="food.id"
                        :food="food"
                        @edit="openEditModal"
                        @delete="confirmDelete"
                    />
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <FoodFormModal 
            :show="showFormModal"
            :food="foodToEdit"
            :categories="foodStore.categories"
            :is-loading="foodStore.isLoading"
            @submit="handleSubmit"
            @close="closeFormModal"
        />

        <!-- Delete Confirmation Modal -->
        <DeleteConfirmModal 
            :show="showDeleteModal"
            title="Delete Food"
            :item-name="foodToDelete?.name"
            :is-loading="foodStore.isLoading"
            @confirm="handleDelete"
            @cancel="closeDeleteModal"
        />

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
import { useFoodStore } from '@/stores/food';
import MainLayout from '@/components/templates/MainLayout.vue';
import CategoryBadge from '@/components/atoms/CategoryBadge.vue';
import FoodItemCard from '@/components/molecules/FoodItemCard.vue';
import FoodFormModal from '@/components/organisms/FoodFormModal.vue';
import DeleteConfirmModal from '@/components/molecules/DeleteConfirmModal.vue';

const router = useRouter();
const foodStore = useFoodStore();

// Search and filter
const searchQuery = ref('');
const selectedCategory = ref(null);

// Modal states
const showFormModal = ref(false);
const showDeleteModal = ref(false);
const foodToEdit = ref(null);
const foodToDelete = ref(null);

// Toast state
const toast = ref({
    show: false,
    message: '',
    type: 'success'
});

// Computed
const foods = computed(() => foodStore.foods);
const filteredFoods = computed(() => {
    return foodStore.filterFoods(selectedCategory.value, searchQuery.value);
});

// Methods
const loadFoods = async () => {
    await foodStore.fetchAll();
};

const goBack = () => {
    router.push('/pos');
};

const openCreateModal = () => {
    foodToEdit.value = null;
    showFormModal.value = true;
};

const openEditModal = (food) => {
    foodToEdit.value = food;
    showFormModal.value = true;
};

const closeFormModal = () => {
    showFormModal.value = false;
    foodToEdit.value = null;
};

const confirmDelete = (food) => {
    foodToDelete.value = food;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    foodToDelete.value = null;
};

const showToast = (message, type = 'success') => {
    toast.value = { show: true, message, type };
    setTimeout(() => {
        toast.value.show = false;
    }, 3000);
};

const handleSubmit = async (formData) => {
    let result;
    if (foodToEdit.value) {
        result = await foodStore.updateFood(foodToEdit.value.id, formData);
        if (result) {
            showToast('Food updated successfully');
            closeFormModal();
        } else {
            showToast(foodStore.error || 'Failed to update food', 'error');
        }
    } else {
        result = await foodStore.createFood(formData);
        if (result) {
            showToast('Food created successfully');
            closeFormModal();
        } else {
            showToast(foodStore.error || 'Failed to create food', 'error');
        }
    }
};

const handleDelete = async () => {
    if (!foodToDelete.value) return;

    const result = await foodStore.deleteFood(foodToDelete.value.id);
    if (result) {
        showToast('Food deleted successfully');
        closeDeleteModal();
    } else {
        showToast(foodStore.error || 'Failed to delete food', 'error');
    }
};

onMounted(() => {
    loadFoods();
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
