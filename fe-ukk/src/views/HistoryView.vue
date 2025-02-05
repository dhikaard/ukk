<template>
    <div class="surface-card px-3 py-5 md:px-6 lg:px-8">
        <!-- Header Section with Filter -->
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-5">
            <div class="flex flex-column text-center md:text-left">
                <span class="text-900 text-3xl font-bold mb-2">Riwayat Sewa</span>
                <span class="text-600 text-xl">Riwayat transaksi penyewaan Anda</span>
            </div>
            <div class="flex gap-3 mt-3 md:mt-0">
                <Dropdown v-model="store.selectedStatus" 
                         :options="store.statusOptions" 
                         optionLabel="label" 
                         optionValue="value"
                         placeholder="Filter Status"
                         class="w-full md:w-12rem"
                         @change="store.applyFilter()" />
                <Button icon="pi pi-refresh" 
                        rounded 
                        text 
                        raised 
                        @click="store.resetFilter()" />
            </div>
        </div>
    
        <Skeleton v-if="store.loading['history']" height="10rem" class="mb-4" />
        
        <!-- Empty State -->
        <div v-else-if="store.items.length === 0" 
             class="surface-card p-8 border-round shadow-2 text-center">
            <i class="pi pi-shopping-bag text-6xl text-primary mb-4"></i>
            <h2 class="text-900 font-medium text-xl mb-2">
            {{ store.items.length === 0 ? 'Belum Ada Riwayat' : 'Tidak Ada Data' }}
            </h2>
            <p class="text-600 mb-4">
            {{ store.items.length === 0 
                ? 'Mulai petualangan Anda dengan menyewa peralatan kami'
                : 'Tidak ada transaksi yang sesuai dengan filter yang dipilih' 
            }}
            </p>
            <Button v-if="store.items.length === 0"
                label="Lihat Katalog" 
                icon="pi pi-arrow-right" 
                @click="router.push('/rent')" />
        </div>
    
        <!-- History List -->
        <div v-else v-for="item in store.items" 
         :key="item.trx_rent_items_id" 
             class="surface-card border-round shadow-2 mb-4 transform transition-all hover:shadow-4">

        <!-- Transaction Header -->
        <div class="grid grid-nogutter p-3 surface-100 border-round-top">
            <div class="col-12 md:col-4 flex align-items-center justify-content-center md:justify-content-start gap-2">
            <div class="flex flex-column">
                <span class="text-700 text-center md:text-left">Nomor Transaksi</span>
                <span class="text-900 text-center md:text-left font-medium">#{{ item.trx_code }}</span>
            </div>
            </div>
            <div class="col-12 md:col-4 flex align-items-center justify-content-center md:justify-content-start gap-2 mt-3 md:mt-0">
            <div class="flex flex-column">
                <span class="text-700 text-center md:text-left">Tanggal Sewa</span>
                <span class="text-900 text-center md:text-left font-medium">
                {{ formatDate(item.rent_start_date) }} - {{ formatDate(item.rent_end_date) }}
                </span>
            </div>
            </div>
            <div class="col-12 md:col-4 flex align-items-center justify-content-center md:justify-content-end gap-2 mt-3 md:mt-0">
            <div class="flex flex-column">
                <span class="text-700 text-center md:text-left">Total Pembayaran</span>
                <span class="text-900 text-center md:text-left font-medium">{{ toCurrencyLocale(item.total) }}</span>
                </div>
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
        <div class="flex justify-content-between align-items-center gap-3 p-3">
            <Tag :severity="store.getStatusSeverity(item.status)" 
                 :value="store.getStatusLabel(item.status)"
                 class="text-lg" />
            <div class="flex gap-2">
                <Button v-if="item.status === 'P'"
                        icon="pi pi-times"
                        label="Batalkan" 
                        severity="danger"
                        :loading="store.loading['cancelOrder']"
                        text
                        @click="store.cancelRent(item.trx_rent_items_id)" />
                <Button v-if="item.status === 'S'"
                        icon="pi pi-sync"
                        label="Sewa Lagi" 
                        text
                        @click="rentAgain(item)" />
            </div>
        </div>
    </div>
</div>

</template>


<script setup>
import { onMounted } from 'vue';
import { useHistoryStore } from '@/stores/history.store';
import { toCurrencyLocale } from '@/utils/currency';
import { useRouter } from 'vue-router';

const router = useRouter();
const store = useHistoryStore();

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

const rentAgain = (item) => {
    const routeParams = store.getDetailForRentAgain(item);
    if (routeParams) {
        router.push({
            name: 'detailProduct',
            params: routeParams
        });
    }
};
</script>
