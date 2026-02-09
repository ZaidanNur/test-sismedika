import { defineStore } from 'pinia';
import { useAuthStore } from './auth';

export const useFoodStore = defineStore('food', {
    state: () => ({
        foods: [],
        categories: [],
        currentFood: null,
        isLoading: false,
        error: null
    }),

    getters: {
        getFoodsByCategory: (state) => (categoryId) => {
            if (!categoryId) return state.foods;
            return state.foods.filter(food => food.category_id === categoryId);
        },

        searchFoods: (state) => (query) => {
            if (!query) return state.foods;
            const lowerQuery = query.toLowerCase();
            return state.foods.filter(food =>
                food.name.toLowerCase().includes(lowerQuery)
            );
        },

        filterFoods: (state) => (categoryId, query) => {
            let filtered = state.foods;

            if (categoryId) {
                filtered = filtered.filter(food => food.category_id === categoryId);
            }

            if (query) {
                const lowerQuery = query.toLowerCase();
                filtered = filtered.filter(food =>
                    food.name.toLowerCase().includes(lowerQuery)
                );
            }

            return filtered;
        }
    },

    actions: {
        async fetchFoods() {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch('/api/foods', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.foods = data.data;
                } else {
                    throw new Error(data.message || 'Failed to fetch foods');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error fetching foods:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchCategories() {
            try {
                const authStore = useAuthStore();
                const response = await fetch('/api/food-categories', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.categories = data.data;
                } else {
                    throw new Error(data.message || 'Failed to fetch categories');
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },

        async fetchAll() {
            await Promise.all([
                this.fetchFoods(),
                this.fetchCategories()
            ]);
        },

        async createFood(formData) {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch('/api/foods', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.foods.push(data.data);
                    return data.data;
                } else {
                    throw new Error(data.message || 'Failed to create food');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error creating food:', error);
                return null;
            } finally {
                this.isLoading = false;
            }
        },

        async updateFood(id, formData) {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/foods/${id}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    const index = this.foods.findIndex(f => f.id === id);
                    if (index !== -1) {
                        this.foods[index] = data.data;
                    }
                    return data.data;
                } else {
                    throw new Error(data.message || 'Failed to update food');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error updating food:', error);
                return null;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteFood(id) {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/foods/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.foods = this.foods.filter(f => f.id !== id);
                    return true;
                } else {
                    throw new Error(data.message || 'Failed to delete food');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error deleting food:', error);
                return false;
            } finally {
                this.isLoading = false;
            }
        },

        clearError() {
            this.error = null;
        },

        clearCurrentFood() {
            this.currentFood = null;
        }
    }
});
