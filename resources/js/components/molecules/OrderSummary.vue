<template>
    <div class="pt-4 border-t border-gray-200">
        <div class="flex flex-col gap-2 mb-4">
            <div class="flex justify-between items-center text-sm text-gray-500">
                <span>Subtotal</span>
                <span>Rp {{ formatPrice(subtotal) }}</span>
            </div>
            <div v-if="serviceCharge > 0" class="flex justify-between items-center text-sm text-gray-500">
                <span>Service Charge ({{ serviceChargePercent }}%)</span>
                <span>Rp {{ formatPrice(serviceCharge) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm text-gray-500">
                <span>Tax ({{ taxPercent }}%)</span>
                <span>Rp {{ formatPrice(tax) }}</span>
            </div>
            <div class="flex justify-between items-center text-base font-semibold text-gray-800 pt-2 border-t border-gray-200">
                <span>Total</span>
                <span>Rp {{ formatPrice(total) }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <button 
                v-if="hasItems"
                class="w-full py-3 px-6 rounded-xl text-sm font-semibold cursor-pointer transition-all bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700"
                @click="$emit('clear')"
            >
                Clear Order
            </button>
            <button 
                class="w-full py-3 px-6 rounded-xl text-sm font-semibold cursor-pointer transition-all bg-gradient-to-r from-violet-500 to-indigo-500 text-white hover:from-violet-600 hover:to-indigo-600 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                :disabled="!hasItems || isSubmitting"
                @click="$emit('submit')"
            >
                <span v-if="isSubmitting">Processing...</span>
                <span v-else>Continue</span>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    subtotal: {
        type: Number,
        default: 0
    },
    serviceCharge: {
        type: Number,
        default: 0
    },
    serviceChargePercent: {
        type: Number,
        default: 0
    },
    tax: {
        type: Number,
        default: 0
    },
    taxPercent: {
        type: Number,
        default: 11
    },
    total: {
        type: Number,
        default: 0
    },
    hasItems: {
        type: Boolean,
        default: false
    },
    isSubmitting: {
        type: Boolean,
        default: false
    }
});

defineEmits(['submit', 'clear']);

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}
</script>
