import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        isLoading: false,
        error: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        getUser: (state) => state.user
    },

    actions: {
        async login(email, password) {
            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Login failed');
                }

                this.token = data.data.token;
                this.user = data.data.user;
                localStorage.setItem('token', this.token);

                return true;
            } catch (error) {
                this.error = error.message;
                return false;
            } finally {
                this.isLoading = false;
            }
        },

        logout() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('token');
        }
    }
});
