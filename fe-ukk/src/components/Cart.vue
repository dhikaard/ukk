<template>
    <div class="cart-wrapper" v-if="visible" @click="$emit('update:visible', false)">
        <div id="slideover-cart"
             class="surface-section fixed top-0 right-0 shadow-2 w-full md:w-30rem h-full z-5"
             @click.stop>
            <div class="flex flex-column h-full">
                <!-- Header -->
                <div class="bg-primary p-3 flex align-items-center">
                    <Button icon="pi pi-chevron-right"
                            class="p-button-rounded p-button-white"
                            @click="$emit('update:visible', false)" />
                    <div class="border-right-1 border-white-alpha-30 mx-3 h-2rem"></div>
                    <span class="text-white text-xl font-medium">Keranjang Sewa</span>
                </div>

                <!-- Content -->
                <div class="flex-auto overflow-y-auto py-4 px-4 surface-ground">
                    <!-- Empty State -->
                    <div v-if="store.items.length === 0" class="flex flex-column align-items-center justify-content-center h-full">
                        <i class="pi pi-shopping-cart text-6xl text-primary mb-4"></i>
                        <p class="text-900 font-medium">Keranjang sewa kosong</p>
                    </div>

                    <div v-else class="flex flex-column gap-3">
                        <!-- Rental Dates -->
                        <div class="surface-card p-3 border-round shadow-1">
                            <span class="text-primary font-medium block mb-2">Tanggal Sewa:</span>
                            <div class="text-900 font-medium">
                                {{ formatDate(store.rentDates[0]) }} - {{ formatDate(store.rentDates[1]) }}
                            </div>
                        </div>

                        <!-- Items -->
                        <div v-for="item in store.items" :key="item.id"
                             class="surface-card p-3 border-round shadow-1">
                            <div class="flex gap-3">
                                <!-- Image -->
                                <img :src="item.image" :alt="item.name"
                                     class="w-8rem h-8rem border-round-lg shadow-1"
                                     style="object-fit: cover;" />

                                <!-- Details -->
                                <div class="flex-1">
                                    <div class="flex flex-column h-full">
                                        <span class="bg-primary-50 text-primary-700 px-2 py-1 border-round text-sm mb-2 align-self-start">
                                            {{ item.category }}
                                        </span>
                                        <div class="text-600 text-sm mb-3">
                                            {{ item.category }}
                                            <span v-if="item.selectedSize" class="ml-2">
                                                | Ukuran {{ item.selectedSize }}
                                            </span>
                                            <span v-else class="ml-2">
                                                | Regular
                                            </span>
                                        </div>
                                        <span class="text-900 font-medium text-lg mb-2">{{ item.name }}</span>
                                        <span class="text-primary font-bold text-xl mb-3">{{ toCurrencyLocale(item.price) }}</span>

                                        <div class="flex justify-content-between align-items-center mt-auto">
                                            <div class="surface-100 px-3 py-1 border-round">
                                                <span class="text-700 font-medium">Qty: {{ item.quantity }}</span>
                                            </div>
                                            <Button icon="pi pi-trash"
                                                   severity="danger"
                                                   text
                                                   class="p-button-rounded"
                                                   @click="removeItem(item)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="surface-card p-4 shadow-2">
                    <div class="flex justify-content-between align-items-center mb-3">
                        <span class="text-900 font-medium text-xl">Total</span>
                        <span class="text-900 font-bold text-2xl">{{ toCurrencyLocale(store.total) }}</span>
                    </div>
                    <Button severity="success"
                            class="w-full mb-2 p-3"
                            label="Checkout"
                            :disabled="!store.items.length"
                            @click="checkout" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCartStore } from '@/stores/cart.store';
import { useRouter } from 'vue-router';
import { toCurrencyLocale } from '@/utils/currency';
import { useToast } from 'primevue/usetoast';
import { showError, showSuccessAdd, showSessionExp, showSuccessRemove } from '@/utils/toast-service';

const router = useRouter();
const toast = useToast();
const store = useCartStore();

const props = defineProps({
    visible: {
        type: Boolean,
        default: false
    }
});

onMounted(() => {
    store.loadCart();
});

defineEmits(['update:visible']);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const removeItem = async (item) => {
    try {
        store.removeItem(item.id);
        showSuccessRemove(toast);
    } catch (error) {
        showError(toast, error.message);
    }
};

const checkout = async () => {
    try {
        const token = localStorage.getItem('token');
        if (!token) {
            showSessionExp(toast);
            router.push('/login');
            return;
        }

        await store.checkout();
        showSuccessAdd(toast);
        router.push('/history');
    } catch (error) {
        showError(toast, error.message);
    }
};
</script>


<style scoped>
.cart-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

#slideover-cart {
    z-index: 1001;
    animation: slideIn 0.2s ease-out;
}

#slideover-cart {
    animation: slideIn 0.2s ease-out;
    transform: translateX(0);
    opacity: 1;
}

#slideover-cart.hidden {
    animation: slideOut 0.2s ease-in forwards;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>