<template>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div v-if="store.loading['productDetail']" class="grid">
            <div class="col-12 lg:col-6">
                <div class="flex">
                    <div class="pl-3 w-10">
                        <Skeleton height="25rem" />
                    </div>
                </div>
            </div>
            <div class="col-12 lg:col-6 py-3 lg:pl-6">
                <Skeleton width="70%" height="2rem" class="mb-4"/>
                <div class="flex align-items-center justify-content-between mb-5">
                    <Skeleton width="40%" height="3rem"/>
                    <Skeleton width="30%" height="3rem"/>
                </div>

                <Skeleton width="40%" height="1.5rem" class="mb-3"/>
                <Skeleton height="3rem" class="mb-5"/>

                <div class="flex flex-column gap-3 mb-4">
                    <Skeleton width="100%" height="1.5rem" class="mb-3"/>
                    <div class="p-inputgroup w-full flex gap-2">
                        <Skeleton width="100%" height="3rem"/>
                    </div>
                </div>

                <div class="flex align-items-center flex-1 mt-3 sm:mt-0 ml-0 sm:ml-5">
                    <Skeleton width="48%" height="3rem" class="mr-2"/>
                    <Skeleton width="48%" height="3rem"/>
                </div>
            </div>
        </div>

        <div v-else-if="product" class="grid mb-7">
            <div class="col-12 lg:col-6">
                <div class="flex">
                    <div class="pl-3 w-10">
                        <img :src="product.image" :alt="product.name" class="w-full" />
                    </div>
                </div>
            </div>
            <div class="col-12 lg:col-6 py-3 lg:pl-6">
                <div class="flex align-items-center text-xl font-medium font-bold mb-2">{{ product.category }}</div>
                <div class="flex align-items-center text-xl font-medium text-900 mb-4">{{ product.name }}</div>
                <div class="flex align-items-center justify-content-between mb-5">
                    <span class="text-900 font-medium text-3xl block">{{ toCurrencyLocale(product.price) }} / hari</span>
                    <Tag :severity="product.inventoryStatus === 'INSTOCK' ? 'success' : 'danger'"
                         style="word-wrap: break-word;"
                         class="p-2 text-xl">
                        {{ product.inventoryStatus === 'INSTOCK' ? 'TERSEDIA' : 'TIDAK TERSEDIA' }}
                    </Tag>
                </div>

                <template v-if="product.hasSize">
                    <div class="mb-3 flex align-items-center justify-content-between">
                        <span class="font-bold text-900">Ukuran</span>
                    </div>
                    <div class="grid grid-nogutter align-items-center mb-5">
                        <div v-for="sizeOption in product.sizes" :key="sizeOption.size"
                            class="col h-3rem border-1 border-300 text-900 inline-flex justify-content-center align-items-center flex-shrink-0 border-round mr-3 cursor-pointer hover:surface-100 transition-duration-150 transition-colors"
                            :class="{
                                'border-primary border-2 text-primary': selectedSize === sizeOption.size,
                                'opacity-50 cursor-not-allowed': sizeOption.stock === 0
                            }"
                            @click="selectSize(sizeOption)">
                            {{ sizeOption.size }}
                            <span class="text-xs ml-2">({{ sizeOption.stock }})</span>
                        </div>
                    </div>
                </template>

                <div class="grid lg:flex-row lg:flex-wrap flex-column-reverse">
                <div class="col-12 lg:col-6 ">
                    <div class="font-bold text-900 mb-3">Quantity</div>
                    <div class="flex flex-column gap-3 mb-4">
                        <div class="p-inputgroup w-full">
                            <Button
                                icon="pi pi-minus"
                                @click="decrementQuantity"
                                :disabled="quantity <= 1" />
                            <InputNumber
                                v-model="quantity"
                                :min="1"
                                :max="getMaxQuantity"
                                buttonLayout="horizontal"
                                inputClass="text-center"
                                :step="1" />
                            <Button
                                icon="pi pi-plus"
                                @click="incrementQuantity"
                                :disabled="quantity >= getMaxQuantity" />
                        </div>
                        <small class="text-600">
                            Tersedia: {{ getMaxQuantity }} unit
                            <span v-if="selectedSize">(Ukuran {{ selectedSize }})</span>
                        </small>
                    </div>
                </div>
                <div class="col-12 lg:col-6">
                    <div class="font-bold text-900 mb-3">Tanggal Penyewaan</div>
                    <div class="flex align-items-center w-full">
                        <Calendar
                            class="w-full"
                            showIcon
                            showTime
                            hourFormat="24"
                            dateFormat="dd/mm/yy"
                            v-model="cartStore.dates"
                            selectionMode="range"
                            :manualInput="false"
                            :minDate="today"
                            :stepMinute="30"
                            @hide="onDateSelect"
                            ref="calendar" />
                        </div>
                </div>
            </div>

                <div class="flex align-items-center flex-1 mt-3 sm:mt-0 ml-0 sm:ml-5">
                    <Button icon="pi pi-shopping-cart"
                        label="Tambah ke Keranjang"
                        severity="secondary"
                        class="flex-1 mr-2"
                        :disabled="!isValid || !isAuthenticated"
                        @click="addToCart" />
                    <Button icon="pi pi-check"
                        label="Sewa Sekarang"
                        severity="success"
                        class="flex-1"
                        :disabled="!isValid || !isAuthenticated"
                        @click="rentNow" />
                </div>  
            </div>
        </div>

        <TabView v-if="!store.loading['productDetail'] && product">
            <TabPanel header="Deskripsi">
                <div v-if="store.loading['productDetail']">
                    <Skeleton width="50%" height="3rem" class="mb-4"/>
                    <Skeleton height="10rem"/>
                </div>
                <template v-else>
                    <div class="text-900 font-medium text-3xl mb-4 mt-2">Deskripsi Barang</div>
                    <div v-html="product.description" class="line-height-3 text-700"></div>
                </template>
            </TabPanel>
        </TabView>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRentViewStore } from '@/stores/rent-view.store';
import { useRoute } from 'vue-router';
import { toCurrencyLocale } from '@/utils/currency';
import { useCartStore } from '@/stores/cart.store';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router';
import { showError, showSessionExp } from '@/utils/toast-service';
import local from '@/utils/local-storage';

const toast = useToast();
const route = useRoute();
const store = useRentViewStore();
const cartStore = useCartStore();
const product = ref(null);
const quantity = ref(1);
const dates = ref();
const selectedSize = ref(null);
const calendar = ref(null);
const today = ref(new Date());
const router = useRouter();

const isAuthenticated = computed(() => {
    return !!local.get('token');
});

const validateDateRange = (start, end) => {
    const minHours = 24;
    const hoursDiff = (end - start) / (1000 * 60 * 60);
    return hoursDiff >= minHours;
};

const onDateSelect = () => {
    if (cartStore.dates?.[0] && cartStore.dates?.[1]) {
        if (!validateDateRange(cartStore.dates[0], cartStore.dates[1])) {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Minimal waktu sewa adalah 24 jam',
                life: 3000
            });
            cartStore.dates = null;
            return;
        }
    }
};

onMounted(async () => {
    const itemCode = route.params.code;
    if (itemCode) {
        try {
            product.value = await store.getProductDetail(itemCode);
        } catch (error) {
            showError(toast, error.message);
        }
    }
});


const addToCart = async () => {
    try {
        await cartStore.addToCart({
            ...product.value,
            selectedSize: selectedSize.value
        }, quantity.value, cartStore.dates, toast);
    } catch (error) {
        showError(toast, error.message);
    }
};

const rentNow = async () => {
    try {
        const token = local.get('token');

        if (!token) {
            showSessionExp(toast);
            return;
        }

        if (!cartStore.dates) {
            throw new Error('Pilih tanggal sewa terlebih dahulu');
        }

        // Clear cart first
        cartStore.clearCart();

        // Add item to cart
        await cartStore.addToCart(
            {
                ...product.value,
                selectedSize: selectedSize.value
            }, 
            quantity.value, 
            cartStore.dates
        );

        // Then checkout
        await cartStore.checkout();

        toast.add({
            severity: 'success',
            summary: 'Berhasil',
            detail: 'Pesanan berhasil dibuat',
            life: 3000
        });
        
        router.push('/history');
    } catch (error) {
        showError(toast, error.message);
    }
};

const incrementQuantity = () => {
    if (quantity.value < getMaxQuantity.value) {
        quantity.value++;
    }
};

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const selectSize = (sizeOption) => {
    if (sizeOption.stock === 0) return;
    selectedSize.value = sizeOption.size;
    quantity.value = 1;
};

const getMaxQuantity = computed(() => {
    if (product.value?.hasSize) {
        if (!selectedSize.value) return 0;
        const sizeStock = product.value.sizes.find(s => s.size === selectedSize.value)?.stock || 0;
        return sizeStock;
    }
    return product.value?.quantity || 0;
});

const isValid = computed(() => {
    return product.value &&
           cartStore.dates?.[0] &&
           cartStore.dates?.[1] &&
           quantity.value > 0 &&
           quantity.value <= getMaxQuantity.value &&
           (!product.value.hasSize || selectedSize.value);
});
</script>