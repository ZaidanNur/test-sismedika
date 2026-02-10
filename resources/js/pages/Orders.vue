<template>
    <MainLayout restaurant-name="Restaurant POS">
        <div class="h-[calc(100vh-100px)] flex flex-col">
            <!-- Page Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Orders</h2>
                <p class="text-sm text-gray-500 m-0">Manage and track all orders</p>
            </div>

            <div class="flex-1 grid grid-cols-[1fr_400px] gap-6 min-h-0 max-lg:grid-cols-1">
                <!-- Left Section - Order List -->
                <div class="flex flex-col bg-white rounded-2xl p-6 overflow-hidden">
                    <!-- Filters Bar -->
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <h3 class="text-lg font-semibold text-indigo-500 m-0 mr-auto">Order List</h3>

                        <!-- Sort -->
                        <select 
                            v-model="sortOrder"
                            class="py-2 px-3 border border-gray-200 rounded-xl text-sm bg-white min-w-[140px] cursor-pointer focus:outline-none focus:border-indigo-500 transition-colors"
                        >
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>

                        <!-- Filter by Creator -->
                        <select 
                            v-model="filterCreator"
                            class="py-2 px-3 border border-gray-200 rounded-xl text-sm bg-white min-w-[150px] cursor-pointer focus:outline-none focus:border-indigo-500 transition-colors"
                        >
                            <option value="">All Creators</option>
                            <option 
                                v-for="creator in orderListStore.uniqueCreators" 
                                :key="creator.id" 
                                :value="creator.id"
                            >
                                {{ creator.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Loading State -->
                    <div v-if="orderListStore.isLoading" class="flex-1 flex flex-col items-center justify-center text-gray-500">
                        <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
                        <p>Loading orders...</p>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="orderListStore.error && orderListStore.orders.length === 0" class="flex-1 flex flex-col items-center justify-center text-gray-500">
                        <p>{{ orderListStore.error }}</p>
                        <button 
                            @click="orderListStore.fetchOrders()" 
                            class="mt-4 py-2 px-4 bg-indigo-500 text-white border-none rounded-lg font-medium cursor-pointer text-sm hover:bg-indigo-600 transition-colors"
                        >
                            Try Again
                        </button>
                    </div>

                    <!-- Order List -->
                    <div v-else class="flex-1 flex flex-col gap-3 overflow-y-auto pr-1 scrollbar-thin">
                        <OrderListItem
                            v-for="order in filteredOrders"
                            :key="order.id"
                            :order="order"
                            :is-active="selectedOrderId === order.id"
                            :active-dropdown-id="activeDropdownId"
                            :selected-status="selectedStatuses[order.id] || ''"
                            :is-updating-status="orderListStore.isUpdatingStatus"
                            @select="selectOrder"
                            @toggle-dropdown="toggleDropdown"
                            @status-select="handleStatusSelect"
                            @update-status="handleStatusUpdate"
                        />

                        <div v-if="filteredOrders.length === 0" class="flex-1 flex flex-col items-center justify-center py-8 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 mb-3 opacity-50">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            <p class="m-0 text-sm font-medium">No orders found</p>
                        </div>
                    </div>
                </div>

                <!-- Right Section - Order Detail -->
                <div class="flex flex-col bg-white rounded-2xl p-6 overflow-hidden max-lg:order-first max-lg:max-h-[400px]">
                    <!-- Receipt Actions -->
                    <div v-if="orderListStore.selectedOrder && !orderListStore.isLoadingDetail" class="flex gap-2 mb-4 pb-4 border-b border-gray-100">
                        <a 
                            :href="`/receipt/${orderListStore.selectedOrder.id}`"
                            target="_blank"
                            class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-xl no-underline hover:bg-indigo-100 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41z" clip-rule="evenodd" />
                            </svg>
                            Show Receipt
                        </a>
                        <a 
                            :href="`/receipt/${orderListStore.selectedOrder.id}/download`"
                            class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-50 text-emerald-600 text-sm font-medium rounded-xl no-underline hover:bg-emerald-100 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                            </svg>
                            Download
                        </a>
                    </div>

                    <!-- Loading Detail -->
                    <div v-if="orderListStore.isLoadingDetail" class="flex-1 flex flex-col items-center justify-center text-gray-500">
                        <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
                        <p>Loading details...</p>
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="!orderListStore.selectedOrder" class="flex-1 flex flex-col items-center justify-center text-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16 mb-4 opacity-50">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        <p class="m-0 font-medium text-gray-500">Select an order</p>
                        <span class="text-sm mt-1">Click on an order from the list to view details</span>
                    </div>

                    <!-- Order Detail Panel -->
                    <OrderDetailPanel
                        v-else
                        :order="orderListStore.selectedOrder"
                        @update-detail="handleDetailUpdate"
                    />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import { useOrderListStore } from '@/stores/orderList';
import MainLayout from '@/components/templates/MainLayout.vue';
import OrderListItem from '@/components/molecules/OrderListItem.vue';
import OrderDetailPanel from '@/components/organisms/OrderDetailPanel.vue';

const orderListStore = useOrderListStore();

const sortOrder = ref('newest');
const filterCreator = ref('');
const selectedOrderId = ref(null);
const activeDropdownId = ref(null);
const selectedStatuses = reactive({});

const filteredOrders = computed(() => {
    let orders = [...orderListStore.orders];

    // Filter by creator
    if (filterCreator.value) {
        orders = orders.filter(order => {
            const creator = order.created_by;
            const creatorId = (creator && typeof creator === 'object') ? creator.id : creator;
            return creatorId == filterCreator.value;
        });
    }

    // Sort by date
    orders.sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return sortOrder.value === 'newest' ? dateB - dateA : dateA - dateB;
    });

    return orders;
});

function selectOrder(orderId) {
    selectedOrderId.value = orderId;
    orderListStore.fetchOrderDetail(orderId);
}

function toggleDropdown(orderId) {
    if (activeDropdownId.value === orderId) {
        activeDropdownId.value = null;
    } else {
        activeDropdownId.value = orderId;
        selectedStatuses[orderId] = '';
    }
}

function handleStatusSelect(orderId, status) {
    selectedStatuses[orderId] = status;
}

async function handleStatusUpdate(orderId) {
    const newStatus = selectedStatuses[orderId];
    if (!newStatus) return;

    const result = await orderListStore.updateOrderStatus(orderId, newStatus);
    if (result.success) {
        activeDropdownId.value = null;
        delete selectedStatuses[orderId];
    } else {
        alert('Failed to update status. Please try again.');
    }
}

async function handleDetailUpdate(orderId, detailId, data) {
    await orderListStore.updateOrderDetail(orderId, detailId, data);
}

function handleClickOutside(event) {
    if (activeDropdownId.value !== null) {
        const dropdown = event.target.closest('.absolute.top-full');
        const button = event.target.closest('button[title="Change status"]');
        if (!dropdown && !button) {
            activeDropdownId.value = null;
        }
    }
}

onMounted(() => {
    orderListStore.fetchOrders();
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    orderListStore.clearSelectedOrder();
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
