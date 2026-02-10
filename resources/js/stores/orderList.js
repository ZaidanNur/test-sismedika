import { defineStore } from 'pinia';
import { useAuthStore } from './auth';

export const useOrderListStore = defineStore('orderList', {
    state: () => ({
        orders: [],
        selectedOrder: null,
        isLoading: false,
        isLoadingDetail: false,
        isUpdatingStatus: false,
        isUpdatingDetail: false,
        error: null
    }),

    getters: {
        uniqueCreators: (state) => {
            const unique = [];
            const seen = new Set();
            for (const order of state.orders) {
                const creator = order.created_by;
                if (creator && typeof creator === 'object' && !seen.has(creator.id)) {
                    seen.add(creator.id);
                    unique.push(creator);
                }
            }
            return unique;
        }
    },

    actions: {
        async fetchOrders() {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch('/api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to fetch orders');
                }

                this.orders = data.data;
            } catch (error) {
                this.error = error.message;
                console.error('Error fetching orders:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchOrderDetail(orderId) {
            this.isLoadingDetail = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/orders/${orderId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to fetch order detail');
                }

                this.selectedOrder = data.data;
            } catch (error) {
                this.error = error.message;
                console.error('Error fetching order detail:', error);
            } finally {
                this.isLoadingDetail = false;
            }
        },

        async updateOrderStatus(orderId, status) {
            this.isUpdatingStatus = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/orders/${orderId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: JSON.stringify({ status })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update order status');
                }

                // Update in local list
                const index = this.orders.findIndex(o => o.id === orderId);
                if (index !== -1) {
                    this.orders[index].status = status;
                }

                // Update selected order if it's the same
                if (this.selectedOrder && this.selectedOrder.id === orderId) {
                    this.selectedOrder.status = status;
                }

                return { success: true };
            } catch (error) {
                this.error = error.message;
                console.error('Error updating order status:', error);
                return { success: false, message: error.message };
            } finally {
                this.isUpdatingStatus = false;
            }
        },

        async updateOrderDetail(orderId, detailId, detailData) {
            this.isUpdatingDetail = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/orders/${orderId}/details/${detailId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: JSON.stringify(detailData)
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update order detail');
                }

                // Update the detail in selectedOrder
                if (this.selectedOrder && this.selectedOrder.id === orderId) {
                    const detailIndex = this.selectedOrder.details.findIndex(d => d.id === detailId);
                    if (detailIndex !== -1) {
                        this.selectedOrder.details[detailIndex] = data.data;
                    }
                }

                return { success: true, data: data.data };
            } catch (error) {
                this.error = error.message;
                console.error('Error updating order detail:', error);
                return { success: false, message: error.message };
            } finally {
                this.isUpdatingDetail = false;
            }
        },

        clearSelectedOrder() {
            this.selectedOrder = null;
        }
    }
});
