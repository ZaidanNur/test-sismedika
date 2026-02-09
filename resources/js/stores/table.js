import { defineStore } from 'pinia';
import { useAuthStore } from './auth';

export const useTableStore = defineStore('table', {
    state: () => ({
        tables: [],
        statistics: {
            available: 0,
            occupied: 0,
            reserved: 0,
            inactive: 0
        },
        isLoading: false,
        error: null
    }),

    getters: {
        totalTables: (state) => state.tables.length,

        tablesByStatus: (state) => (status) => {
            return state.tables.filter(table => table.status === status);
        }
    },

    actions: {
        async fetchTables() {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch('/api/tables', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.tables = data.data;
                    this.statistics = data.statistics;
                } else {
                    throw new Error(data.message || 'Failed to fetch tables');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error fetching tables:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async updateTableStatus(tableId, status) {
            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/tables/${tableId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: JSON.stringify({ status })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    await this.fetchTables();
                    return { success: true };
                } else {
                    throw new Error(data.message || 'Failed to update table status');
                }
            } catch (error) {
                console.error('Error updating table status:', error);
                return { success: false, error: error.message };
            }
        }
    }
});
