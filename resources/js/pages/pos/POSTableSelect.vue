<template>
    <MainLayout restaurant-name="Restaurant POS">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Select Table</h2>
                <p class="text-sm text-gray-500">Choose an available table to start a new order</p>
            </div>

            <!-- Filters -->
            <div class="flex gap-4 mb-6">
                <div class="flex-1 relative max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search tables..."
                        class="w-full py-3 pl-12 pr-4 border border-gray-200 rounded-xl text-sm bg-white transition-all focus:outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-500/10"
                    />
                </div>

                <select 
                    v-model="statusFilter" 
                    class="py-3 px-4 border border-gray-200 rounded-xl text-sm bg-white min-w-[150px] cursor-pointer focus:outline-none focus:border-indigo-500"
                >
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="reserved">Reserved</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Loading State -->
            <div v-if="tableStore.isLoading" class="flex flex-col items-center justify-center py-16 text-gray-500">
                <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
                <p>Loading tables...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="tableStore.error" class="flex flex-col items-center justify-center py-16 text-gray-500">
                <p>{{ tableStore.error }}</p>
                <button 
                    @click="tableStore.fetchTables()" 
                    class="mt-4 py-3 px-6 bg-indigo-500 text-white border-none rounded-lg font-medium cursor-pointer"
                >
                    Try Again
                </button>
            </div>

            <!-- Tables Grid -->
            <div v-else class="grid grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-4">
                <div 
                    v-for="table in filteredTables" 
                    :key="table.id"
                    class="relative flex flex-col items-center p-6 bg-white border-2 rounded-2xl transition-all"
                    :class="[
                        table.status === 'available' ? 'border-emerald-500 cursor-pointer hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/15' : '',
                        table.status === 'occupied' ? 'border-red-500 opacity-60' : '',
                        table.status === 'reserved' ? 'border-amber-500 cursor-pointer hover:-translate-y-0.5 hover:shadow-lg hover:shadow-amber-500/15' : '',
                        table.status === 'inactive' ? 'border-gray-400 opacity-60' : ''
                    ]"
                    @click="selectTable(table)"
                >
                    <!-- Settings Button -->
                    <div class="absolute top-2 right-2">
                        <button 
                            @click.stop="toggleSettingsDropdown(table.id)"
                            class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                            title="Change status"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Settings Dropdown -->
                        <div 
                            v-if="activeSettingsDropdown === table.id"
                            class="absolute top-full right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-2 min-w-[140px]"
                            @click.stop
                        >
                            <select 
                                v-model="selectedStatus[table.id]"
                                class="w-full py-2 px-3 border border-gray-200 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-indigo-500 mb-2"
                            >
                                <option value="" disabled>Select status</option>
                                <option 
                                    v-for="status in getAvailableStatuses(table.status)" 
                                    :key="status" 
                                    :value="status"
                                >
                                    {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                                </option>
                            </select>
                            <button 
                                @click.stop="setTableStatus(table.id)"
                                :disabled="!selectedStatus[table.id] || isUpdatingStatus"
                                class="w-full py-2 px-3 bg-indigo-500 text-white text-sm font-medium rounded-lg hover:bg-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ isUpdatingStatus ? 'Setting...' : 'Set' }}
                            </button>
                        </div>
                    </div>

                    <div 
                        class="w-12 h-12 mb-3"
                        :class="[
                            table.status === 'available' ? 'text-emerald-500' : '',
                            table.status === 'occupied' ? 'text-red-500' : '',
                            table.status === 'reserved' ? 'text-amber-500' : 'text-gray-400'
                        ]"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Table {{ table.id }}</h3>
                    <span 
                        class="text-xs font-medium px-3 py-1 rounded-full capitalize"
                        :class="[
                            table.status === 'available' ? 'bg-emerald-100 text-emerald-800' : '',
                            table.status === 'occupied' ? 'bg-red-100 text-red-800' : '',
                            table.status === 'reserved' ? 'bg-amber-100 text-amber-800' : '',
                            table.status === 'inactive' ? 'bg-gray-100 text-gray-800' : ''
                        ]"
                    >
                        {{ table.status }}
                    </span>
                </div>

                <div v-if="filteredTables.length === 0" class="col-span-full flex flex-col items-center justify-center py-16 text-gray-500">
                    <p>No tables found matching your criteria</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useTableStore } from '@/stores/table';
import { useOrderStore } from '@/stores/order';
import MainLayout from '@/components/templates/MainLayout.vue';

const router = useRouter();
const tableStore = useTableStore();
const orderStore = useOrderStore();

const searchQuery = ref('');
const statusFilter = ref('');
const activeSettingsDropdown = ref(null);
const selectedStatus = reactive({});
const isUpdatingStatus = ref(false);

const allStatuses = ['available', 'occupied', 'reserved', 'inactive'];

const filteredTables = computed(() => {
    let tables = tableStore.tables;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        tables = tables.filter(table => 
            `table ${table.id}`.toLowerCase().includes(query) ||
            table.id.toString().includes(query)
        );
    }

    if (statusFilter.value) {
        tables = tables.filter(table => table.status === statusFilter.value);
    }

    return tables;
});

function selectTable(table) {
    if (table.status !== 'available' && table.status !== 'reserved') {
        return;
    }
    
    orderStore.setTable(table.id);
    router.push(`/pos/order/${table.id}`);
}

function toggleSettingsDropdown(tableId) {
    if (activeSettingsDropdown.value === tableId) {
        activeSettingsDropdown.value = null;
    } else {
        activeSettingsDropdown.value = tableId;
        selectedStatus[tableId] = '';
    }
}

function getAvailableStatuses(currentStatus) {
    return allStatuses.filter(status => status !== currentStatus);
}

async function setTableStatus(tableId) {
    const newStatus = selectedStatus[tableId];
    if (!newStatus) return;

    isUpdatingStatus.value = true;
    const result = await tableStore.updateTableStatus(tableId, newStatus);
    
    if (result.success) {
        activeSettingsDropdown.value = null;
    } else {
        alert('Failed to update table status. Please try again.');
    }
    
    isUpdatingStatus.value = false;
}

function handleClickOutside(event) {
    if (activeSettingsDropdown.value !== null) {
        const dropdown = document.querySelector('.absolute.top-full');
        const button = event.target.closest('button[title="Change status"]');
        if (!dropdown?.contains(event.target) && !button) {
            activeSettingsDropdown.value = null;
        }
    }
}

onMounted(() => {
    tableStore.fetchTables();
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
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
