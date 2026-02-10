<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="pb-4 mb-4 border-b border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900 m-0">Order #{{ order.id }}</h3>
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
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                        <path d="M2 4.5A2.5 2.5 0 014.5 2h11A2.5 2.5 0 0118 4.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 012 15.5v-11z" />
                    </svg>
                    <span>Table {{ order.table_id }}</span>
                </div>
            </div>
        </div>

        <!-- Items Label -->
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-gray-700 m-0">Items</h4>
            <span class="text-xs text-gray-400">{{ order.details?.length || 0 }} items</span>
        </div>

        <!-- Detail Items -->
        <div class="flex-1 overflow-y-auto scrollbar-thin">
            <div v-if="!order.details || order.details.length === 0" class="flex flex-col items-center justify-center py-8 text-gray-400">
                <p class="m-0 text-sm">No items in this order</p>
            </div>
            <OrderDetailItem
                v-for="detail in order.details"
                :key="detail.id"
                :detail="detail"
                :editable="isEditable"
                :is-saving="savingDetailId === detail.id"
                @update="handleDetailUpdate"
            />
        </div>

        <!-- Total Summary -->
        <div class="pt-4 mt-4 border-t border-gray-100">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-500">Total</span>
                <span class="text-lg font-bold text-gray-900">
                    Rp {{ formatPrice(totalPrice) }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import OrderStatusBadge from '@/components/atoms/OrderStatusBadge.vue';
import OrderDetailItem from '@/components/molecules/OrderDetailItem.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['update-detail']);

const savingDetailId = ref(null);

const isEditable = computed(() => {
    return props.order.status === 'on progress';
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

const totalPrice = computed(() => {
    if (!props.order.details) return 0;
    return props.order.details.reduce((sum, detail) => {
        return sum + (detail.current_price * detail.quantity);
    }, 0);
});

async function handleDetailUpdate(detailId, data) {
    savingDetailId.value = detailId;
    emit('update-detail', props.order.id, detailId, data);
    // Parent will handle the actual API call; reset saving state after a delay
    setTimeout(() => {
        savingDetailId.value = null;
    }, 1500);
}

function formatPrice(price) {
    return Number(price).toLocaleString('id-ID');
}
</script>

<style scoped>
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
