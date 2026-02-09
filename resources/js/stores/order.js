import { defineStore } from 'pinia';
import { useAuthStore } from './auth';

export const useOrderStore = defineStore('order', {
    state: () => ({
        selectedTableId: null,
        orderItems: [],
        isSubmitting: false,
        error: null,
        serviceChargePercent: 0,
        taxPercent: 11
    }),

    getters: {
        subtotal: (state) => {
            return state.orderItems.reduce((sum, item) => {
                return sum + (item.price * item.quantity);
            }, 0);
        },

        serviceCharge: (state) => {
            const subtotal = state.orderItems.reduce((sum, item) => {
                return sum + (item.price * item.quantity);
            }, 0);
            return subtotal * (state.serviceChargePercent / 100);
        },

        tax: (state) => {
            const subtotal = state.orderItems.reduce((sum, item) => {
                return sum + (item.price * item.quantity);
            }, 0);
            return subtotal * (state.taxPercent / 100);
        },

        total() {
            return this.subtotal + this.serviceCharge + this.tax;
        },

        itemCount: (state) => {
            return state.orderItems.reduce((sum, item) => sum + item.quantity, 0);
        },

        hasItems: (state) => {
            return state.orderItems.length > 0;
        }
    },

    actions: {
        setTable(tableId) {
            this.selectedTableId = tableId;
        },

        addItem(food) {
            const existingItem = this.orderItems.find(item => item.food_id === food.id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.orderItems.push({
                    food_id: food.id,
                    name: food.name,
                    price: parseFloat(food.price),
                    quantity: 1,
                    image: food.images?.[0]?.url || null
                });
            }
        },

        removeItem(foodId) {
            const index = this.orderItems.findIndex(item => item.food_id === foodId);
            if (index !== -1) {
                this.orderItems.splice(index, 1);
            }
        },

        updateQuantity(foodId, quantity) {
            const item = this.orderItems.find(item => item.food_id === foodId);
            if (item) {
                if (quantity <= 0) {
                    this.removeItem(foodId);
                } else {
                    item.quantity = quantity;
                }
            }
        },

        incrementQuantity(foodId) {
            const item = this.orderItems.find(item => item.food_id === foodId);
            if (item) {
                item.quantity += 1;
            }
        },

        decrementQuantity(foodId) {
            const item = this.orderItems.find(item => item.food_id === foodId);
            if (item) {
                if (item.quantity <= 1) {
                    this.removeItem(foodId);
                } else {
                    item.quantity -= 1;
                }
            }
        },

        clearOrder() {
            this.orderItems = [];
        },

        async submitOrder() {
            if (!this.selectedTableId || this.orderItems.length === 0) {
                this.error = 'Please select a table and add items to order';
                return false;
            }

            this.isSubmitting = true;
            this.error = null;

            try {
                const authStore = useAuthStore();

                const orderData = {
                    table_id: this.selectedTableId,
                    details: this.orderItems.map(item => ({
                        food_id: item.food_id,
                        quantity: item.quantity
                    }))
                };

                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: JSON.stringify(orderData)
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to create order');
                }

                // Clear order after successful submission
                this.clearOrder();
                this.selectedTableId = null;

                return data.data;
            } catch (error) {
                this.error = error.message;
                console.error('Error submitting order:', error);
                return false;
            } finally {
                this.isSubmitting = false;
            }
        }
    }
});
