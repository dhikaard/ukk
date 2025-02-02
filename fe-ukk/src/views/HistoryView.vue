<template>
<div class="surface-ground px-3 py-5 md:px-6 lg:px-8">
    <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
        <div class="flex flex-column text-center md:text-left w-full md:w-auto mb-4 md:mb-0">
            <span class="text-900 text-2xl md:text-3xl font-medium mb-2">Riwayat Sewa</span>
            <span class="text-600 text-base md:text-xl">Riwayat transaksi penyewaan Anda</span>
        </div>
    </div>

    <Skeleton v-if="store.loading['history']" height="10rem" class="mb-4" />
    
    <div v-else-if="store.items.length === 0" class="surface-card p-4 border-round shadow-2 text-center">
        <i class="pi pi-inbox text-5xl md:text-6xl text-primary mb-4"></i>
        <p>Belum ada riwayat penyewaan</p>
    </div>

    <div v-else v-for="item in store.items" :key="item.trx_rent_items_id" 
         class="surface-card border-round shadow-2 mb-4">
        <!-- Transaction Header -->
        <div class="grid grid-nogutter p-2 surface-100 border-round-top">
            <div class="col-12 md:col-4 p-2 text-center md:text-left">
                <span class="text-700 block text-sm md:text-base">Nomor Transaksi</span>
                <span class="text-900 font-medium block mt-2">#{{ item.trx_code }}</span>
            </div>
            <div class="col-12 md:col-4 p-2 text-center">
                <span class="text-700 block text-sm md:text-base">Tanggal Sewa</span>
                <span class="text-900 font-medium block mt-2 text-sm md:text-base">
                    {{ formatDate(item.rent_start_date) }} - {{ formatDate(item.rent_end_date) }}
                </span>
            </div>
            <div class="col-12 md:col-4 p-2 text-center md:text-right">
                <span class="text-700 block text-sm md:text-base">Total</span>
                <span class="text-900 font-medium block mt-2">{{ toCurrencyLocale(item.total) }}</span>
            </div>
        </div>

        <!-- Detail Items -->
        <div class="p-3">
            <div v-for="detail in item.details" :key="detail.trx_rent_items_detail_id" 
                 class="border-bottom-1 surface-border py-3">
                <div class="grid formgrid">
                    <!-- Image -->
                    <div class="col-12 md:col-2 flex justify-content-center md:justify-content-start">
                        <img :src="detail.item.image" :alt="detail.item.items_name"
                             class="w-8rem h-8rem border-round shadow-2" 
                             style="object-fit: cover;" />
                    </div>
                    
                    <!-- Item Details -->
                    <div class="col-12 md:col-7 flex flex-column justify-content-center mt-3 md:mt-0">
                        <span class="text-900 font-medium text-xl mb-2 text-center md:text-left">
                            {{ detail.item.items_name }}
                        </span>
                        <div class="text-center md:text-left">
                            <span class="text-600 mb-2 block">
                                {{ detail.item.ctgr_items.ctgr_items_name }}
                            </span>
                            <span class="text-600 mb-2 block">
                                <span v-if="detail.item_stock_id === -99">
                                    Regular Stock
                                </span>
                                <span v-else-if="detail.item_stock">
                                    Size: {{ detail.item_stock.size }}
                                </span>
                            </span>
                            <div class="flex align-items-center justify-content-center md:justify-content-start">
                                <span class="text-primary font-medium text-lg mr-2">
                                    {{ toCurrencyLocale(detail.item.price) }}
                                </span>
                                <span class="text-700">
                                    x {{ detail.qty }} item(s)
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subtotal -->
                    <div class="col-12 md:col-3 flex flex-column justify-content-center mt-3 md:mt-0">
                        <div class="text-center md:text-right">
                            <span class="text-600 block mb-2">Subtotal</span>
                            <span class="text-900 font-medium text-xl block">
                                {{ toCurrencyLocale(detail.sub_total) }}
                            </span>
                            <span v-if="detail.fine_amount > 0" 
                                  class="text-pink-500 font-medium block mt-2">
                                Denda: {{ toCurrencyLocale(detail.fine_amount) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Actions -->
        <div class="flex flex-column md:flex-row justify-content-between align-items-center gap-3 p-3 surface-100 border-round-bottom">
            <Tag :severity="getStatusSeverity(item.status)" 
                 :value="getStatusLabel(item.status)" 
                 class="text-lg" />
            <Button v-if="item.status === 'P'"
                    label="Batalkan" 
                    severity="danger"
                    :loading="loading[item.trx_rent_items_id]"
                    text
                    @click="cancelOrder(item.trx_rent_items_id)" />
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useHistoryStore } from '@/stores/history.store';
import { useToast } from 'primevue/usetoast';
import { toCurrencyLocale } from '@/utils/currency';

const store = useHistoryStore();
const toast = useToast();
const loading = ref({});

onMounted(() => {
    store.getHistory();
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusLabel = (status) => {
    const labels = {
        'P': 'Pending',
        'D': 'Sedang Disewa',
        'S': 'Selesai',
        'B': 'Dibatalkan',
        'T': 'Ditolak'
    };
    return labels[status] || status;
};

const getStatusSeverity = (status) => {
    const severities = {
        'P': 'warning',
        'D': 'info',
        'S': 'success',
        'B': 'danger',
        'T': 'danger'
    };
    return severities[status] || 'info';
};

const cancelOrder = async (id) => {
    try {
        loading.value[id] = true;
        await store.cancelRent(id);
        toast.add({
            severity: 'success',
            summary: 'Berhasil',
            detail: 'Pesanan berhasil dibatalkan',
            life: 3000
        });
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Gagal',
            detail: error.message,
            life: 3000
        });
    } finally {
        loading.value[id] = false;
    }
};
</script>
