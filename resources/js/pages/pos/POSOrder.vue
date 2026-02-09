<template>
    <MainLayout restaurant-name="Restaurant POS">
        <div class="h-[calc(100vh-100px)] flex flex-col">
            <!-- Header with back button -->
            <div class="flex items-center gap-4 mb-6">
                <button 
                    @click="goBack" 
                    class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 text-sm cursor-pointer transition-all hover:bg-gray-100"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                    </svg>
                    Back
                </button>
                <h2 class="text-xl font-semibold text-gray-800 m-0">Table {{ tableId }}</h2>
            </div>

            <div class="flex-1 grid grid-cols-[1fr_350px] gap-6 min-h-0 max-lg:grid-cols-1">
                <!-- Left Section - Food List -->
                <div class="flex flex-col bg-white rounded-2xl p-6 overflow-hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-indigo-500 m-0">Items</h3>
                        
                        <!-- Search -->
                        <div class="relative w-[300px]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search items..."
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
                    <div v-if="foodStore.isLoading" class="flex-1 flex flex-col items-center justify-center text-gray-500">
                        <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
                        <p>Loading menu...</p>
                    </div>

                    <!-- Food Grid -->
                    <div v-else class="flex-1 grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-4 overflow-y-auto pr-2 scrollbar-thin">
                        <FoodCard 
                            v-for="food in filteredFoods"
                            :key="food.id"
                            :food="food"
                            @add="addToOrder"
                        />

                        <div v-if="filteredFoods.length === 0" class="col-span-full text-center py-8 text-gray-500">
                            <p>No items found</p>
                        </div>
                    </div>
                </div>

                <!-- Right Section - Current Order -->
                <div class="flex flex-col bg-white rounded-2xl p-6 overflow-hidden max-lg:order-first max-lg:max-h-[300px]">
                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 m-0">Current Order</h3>
                        <span class="text-xs font-medium px-3 py-1 bg-indigo-50 text-indigo-500 rounded-full">Table {{ tableId }}</span>
                    </div>

                    <!-- Empty State -->
                    <div v-if="!orderStore.hasItems" class="flex-1 flex flex-col items-center justify-center text-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 mb-4 opacity-50">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                        <p class="m-0 font-medium text-gray-500">No items added yet</p>
                        <span class="text-sm mt-1">Select items from the menu</span>
                    </div>

                    <!-- Order Items -->
                    <div v-else class="flex-1 overflow-y-auto mb-4 scrollbar-thin">
                        <OrderItem 
                            v-for="item in orderStore.orderItems"
                            :key="item.food_id"
                            :item="item"
                            @increment="orderStore.incrementQuantity"
                            @decrement="orderStore.decrementQuantity"
                            @remove="orderStore.removeItem"
                        />
                    </div>

                    <!-- Order Summary -->
                    <OrderSummary 
                        :subtotal="orderStore.subtotal"
                        :service-charge="orderStore.serviceCharge"
                        :service-charge-percent="orderStore.serviceChargePercent"
                        :tax="orderStore.tax"
                        :tax-percent="orderStore.taxPercent"
                        :total="orderStore.total"
                        :has-items="orderStore.hasItems"
                        :is-submitting="orderStore.isSubmitting"
                        @submit="submitOrder"
                        @clear="orderStore.clearOrder"
                    />

                    <!-- Error Message -->
                    <div v-if="orderStore.error" class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg text-red-500 text-sm text-center">
                        {{ orderStore.error }}
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useFoodStore } from '@/stores/food';
import { useOrderStore } from '@/stores/order';
import MainLayout from '@/components/templates/MainLayout.vue';
import CategoryBadge from '@/components/atoms/CategoryBadge.vue';
import FoodCard from '@/components/molecules/FoodCard.vue';
import OrderItem from '@/components/molecules/OrderItem.vue';
import OrderSummary from '@/components/molecules/OrderSummary.vue';

const route = useRoute();
const router = useRouter();
const foodStore = useFoodStore();
const orderStore = useOrderStore();

const tableId = computed(() => route.params.tableId);
const searchQuery = ref('');
const selectedCategory = ref(null);

const filteredFoods = computed(() => {
    return foodStore.filterFoods(selectedCategory.value, searchQuery.value);
});

function addToOrder(food) {
    orderStore.addItem(food);
}

async function submitOrder() {
    const result = await orderStore.submitOrder();
    if (result) {
        router.push('/pos');
    }
}

function goBack() {
    router.push('/pos');
}

onMounted(() => {
    orderStore.setTable(parseInt(tableId.value));
    foodStore.fetchAll();
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
