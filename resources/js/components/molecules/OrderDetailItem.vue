<template>
    <div class="flex items-center gap-4 py-3 px-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 rounded-lg transition-colors">
        <!-- Food Info -->
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 m-0 truncate">{{ detail.food?.name || 'Unknown Item' }}</p>
            <p class="text-xs text-gray-400 m-0 mt-0.5">
                Rp {{ formatPrice(detail.current_price) }} / item
            </p>
        </div>

        <!-- Quantity -->
        <div class="flex items-center gap-2">
            <template v-if="editable">
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                    <button
                        @click="decrementQty"
                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors border-none bg-transparent cursor-pointer"
                        :disabled="localQuantity <= 1"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                            <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <input
                        v-model.number="localQuantity"
                        type="number"
                        min="1"
                        class="w-10 h-8 text-center text-sm font-medium text-gray-800 border-x border-gray-200 bg-transparent focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    />
                    <button
                        @click="incrementQty"
                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors border-none bg-transparent cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                    </button>
                </div>

                <!-- Save Button -->
                <button
                    v-if="hasChanged"
                    @click="saveChanges"
                    :disabled="isSaving"
                    class="px-3 py-1.5 bg-indigo-500 text-white text-xs font-medium rounded-lg hover:bg-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ isSaving ? '...' : 'Save' }}
                </button>
            </template>
            <template v-else>
                <span class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                    × {{ detail.quantity }}
                </span>
            </template>
        </div>

        <!-- Subtotal -->
        <div class="text-right min-w-[100px]">
            <span class="text-sm font-semibold text-gray-800">
                Rp {{ formatPrice(detail.current_price * (editable ? localQuantity : detail.quantity)) }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    detail: {
        type: Object,
        required: true
    },
    editable: {
        type: Boolean,
        default: false
    },
    isSaving: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update']);

const localQuantity = ref(props.detail.quantity);

watch(() => props.detail.quantity, (newVal) => {
    localQuantity.value = newVal;
});

const hasChanged = computed(() => {
    return localQuantity.value !== props.detail.quantity;
});

function incrementQty() {
    localQuantity.value++;
}

function decrementQty() {
    if (localQuantity.value > 1) {
        localQuantity.value--;
    }
}

function saveChanges() {
    emit('update', props.detail.id, { quantity: localQuantity.value });
}

function formatPrice(price) {
    return Number(price).toLocaleString('id-ID');
}
</script>
