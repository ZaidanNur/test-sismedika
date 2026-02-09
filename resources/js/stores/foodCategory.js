import { defineStore } from 'pinia';
import { useAuthStore } from './auth';

export const useFoodCategoryStore = defineStore('foodCategory', {
    state: () => ({
        categories: [],
        currentCategory: null,
        isLoading: false,
        error: null
    }),

    getters: {
        getCategoryById: (state) => (id) => {
            return state.categories.find(category => category.id === id);
        }
    },

    actions: {
        async fetchCategories() {
            this.isLoading = true;
            this.error = null;

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
                this.error = error.message;
                console.error('Error fetching categories:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async createCategory(categoryData) {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch('/api/food-categories', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: JSON.stringify(categoryData)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.categories.push(data.data);
                    return data.data;
                } else {
                    throw new Error(data.message || 'Failed to create category');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error creating category:', error);
                return null;
            } finally {
                this.isLoading = false;
            }
        },

        async updateCategory(id, categoryData) {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/food-categories/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authStore.token}`
                    },
                    body: JSON.stringify(categoryData)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    const index = this.categories.findIndex(cat => cat.id === id);
                    if (index !== -1) {
                        this.categories[index] = data.data;
                    }
                    return data.data;
                } else {
                    throw new Error(data.message || 'Failed to update category');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error updating category:', error);
                return null;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteCategory(id) {
            this.isLoading = true;
            this.error = null;

            try {
                const authStore = useAuthStore();
                const response = await fetch(`/api/food-categories/${id}`, {
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
                    this.categories = this.categories.filter(cat => cat.id !== id);
                    return true;
                } else {
                    throw new Error(data.message || 'Failed to delete category');
                }
            } catch (error) {
                this.error = error.message;
                console.error('Error deleting category:', error);
                return false;
            } finally {
                this.isLoading = false;
            }
        },

        clearError() {
            this.error = null;
        },

        clearCurrentCategory() {
            this.currentCategory = null;
        }
    }
});
