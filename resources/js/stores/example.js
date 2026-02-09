import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

/**
 * Example Store
 * 
 * Contoh store menggunakan Composition API style
 * Anda bisa menggunakan ini sebagai template untuk store lainnya
 */
export const useExampleStore = defineStore('example', () => {
    // State
    const count = ref(0);
    const items = ref([]);
    const loading = ref(false);
    const error = ref(null);

    // Getters
    const doubleCount = computed(() => count.value * 2);
    const itemCount = computed(() => items.value.length);

    // Actions
    function increment() {
        count.value++;
    }

    function decrement() {
        count.value--;
    }

    function reset() {
        count.value = 0;
    }

    function addItem(item) {
        items.value.push(item);
    }

    function removeItem(index) {
        items.value.splice(index, 1);
    }

    async function fetchItems() {
        loading.value = true;
        error.value = null;

        try {
            // Contoh fetch data dari API
            // const response = await axios.get('/api/items');
            // items.value = response.data;

            // Simulasi delay
            await new Promise(resolve => setTimeout(resolve, 1000));
            items.value = [
                { id: 1, name: 'Item 1' },
                { id: 2, name: 'Item 2' },
            ];
        } catch (err) {
            error.value = err.message;
        } finally {
            loading.value = false;
        }
    }

    return {
        // State
        count,
        items,
        loading,
        error,
        // Getters
        doubleCount,
        itemCount,
        // Actions
        increment,
        decrement,
        reset,
        addItem,
        removeItem,
        fetchItems,
    };
});
