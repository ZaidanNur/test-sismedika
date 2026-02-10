<template>
    <div 
        class="relative p-4 bg-white border-2 rounded-xl cursor-pointer transition-all hover:shadow-md"
        :class="isActive ? 'border-indigo-500 shadow-md shadow-indigo-500/10' : 'border-gray-100 hover:border-gray-200'"
        @click="$emit('select', order.id)"
    >
        <!-- Settings Button -->
        <div class="absolute top-3 right-3">
            <button 
                @click.stop="$emit('toggle-dropdown', order.id)"
                class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                title="Change status"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Status Dropdown -->
            <div 
                v-if="activeDropdownId === order.id"
                class="absolute top-full right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 p-2 min-w-[160px]"
                @click.stop
            >
                <select 
                    :value="selectedStatus"
                    @change="$emit('status-select', order.id, $event.target.value)"
                    class="w-full py-2 px-3 border border-gray-200 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-indigo-500 mb-2"
                >
                    <option value="" disabled>Select status</option>
                    <option 
                        v-for="status in availableStatuses" 
                        :key="status" 
                        :value="status"
                    >
                        {{ formatStatus(status) }}
                    </option>
                </select>
                <button 
                    @click.stop="$emit('update-status', order.id)"
                    :disabled="!selectedStatus || isUpdatingStatus"
                    class="w-full py-2 px-3 bg-indigo-500 text-white text-sm font-medium rounded-lg hover:bg-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ isUpdatingStatus ? 'Setting...' : 'Set' }}
                </button>
            </div>
        </div>

        <!-- Order Info -->
        <div class="pr-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-sm font-bold text-gray-800">#{{ order.id }}</span>
                <OrderStatusBadge :status="order.status" />
            </div>
            
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                        <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ formattedDate }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                        <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                    </svg>
                    <span>{{ creatorName }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import OrderStatusBadge from '@/components/atoms/OrderStatusBadge.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true
    },
    isActive: {
        type: Boolean,
        default: false
    },
    activeDropdownId: {
        type: [Number, null],
        default: null
    },
    selectedStatus: {
        type: String,
        default: ''
    },
    isUpdatingStatus: {
        type: Boolean,
        default: false
    }
});

defineEmits(['select', 'toggle-dropdown', 'status-select', 'update-status']);

const allStatuses = ['on progress', 'closed', 'cancelled'];

const availableStatuses = computed(() => {
    return allStatuses.filter(s => s !== props.order.status);
});

const formattedDate = computed(() => {
    if (!props.order.created_at) return '-';
    const date = new Date(props.order.created_at);
    return date.toLocaleDateString('id-ID', { 
        day: '2-digit', 
        month: 'short', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
});

const creatorName = computed(() => {
    const creator = props.order.created_by;
    if (creator && typeof creator === 'object') {
        return creator.name;
    }
    return 'Unknown';
});

function formatStatus(status) {
    return status.charAt(0).toUpperCase() + status.slice(1);
}
</script>
