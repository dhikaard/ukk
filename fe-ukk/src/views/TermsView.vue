<template>
    <div class="surface-card px-4 py-8 md:px-6 lg:px-8 shadow-2">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="text-center mb-5">
                <i class="pi pi-info-circle text-4xl text-primary mb-3"></i>
                <h1 class="text-900 font-bold text-4xl mb-3">Informasi & Petunjuk</h1>
                <p class="text-600 font-medium text-xl line-height-3 mb-0">
                    Untuk memastikan pengalaman rental yang nyaman dan aman, kami menyediakan panduan lengkap mengenai
                    proses penyewaan, pembayaran, dan pengembalian barang.
                </p>
            </div>
        </div>

        <TabView>
            <!-- Terms Panel -->
            <TabPanel header="Ketentuan">
                <div class="grid my-4">
                    <div class="col-12">
                        <div class="flex align-items-center mb-4">
                            <i class="pi pi-book text-2xl text-primary mr-3"></i>
                            <h2 class="text-900 font-bold text-2xl m-0">Ketentuan Rental</h2>
                        </div>
                        <div class="grid">
                            <div v-for="(rule, index) in rentalRules" 
                                 :key="index" 
                                 class="col-12 md:col-6">
                                <div class="p-4 surface-50 border-round-lg shadow-1 h-full hover:shadow-3 transition-duration-300">
                                    <div class="flex align-items-start gap-3">
                                        <div class="flex align-items-center justify-content-center border-circle bg-primary-100 h-2rem w-2rem">
                                            <span class="text-primary font-bold">{{ index + 1 }}</span>
                                        </div>
                                        <p class="text-900 line-height-3 m-0">{{ rule.text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </TabPanel>

            <!-- Operating Hours Panel -->
            <TabPanel header="Jam Operasional">
                <div class="grid  my-4">
                    <div class="col-12">
                        <div class="flex align-items-center mb-4">
                            <i class="pi pi-clock text-2xl text-primary mr-3"></i>
                            <h2 class="text-900 font-bold text-2xl m-0">Jam Operasional</h2>
                        </div>
                        <div class="grid">
                            <div v-for="(day, index) in operatingHours" 
                                 :key="index" 
                                 class="col-12 md:col-6 lg:col-4">
                                <div class="p-4 surface-50 border-round-lg shadow-1 mb-3 hover:shadow-3 transition-duration-300">
                                    <div class="flex justify-content-between align-items-center">
                                        <div class="flex align-items-center">
                                            <i class="pi pi-calendar text-primary mr-2"></i>
                                            <span class="font-medium text-900">{{ day.day }}</span>
                                        </div>
                                        <span class="text-primary font-medium">{{ day.hours }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 surface-100 border-round flex align-items-center">
                            <i class="pi pi-info-circle text-primary mr-2"></i>
                            <small class="text-600">Jadwal dapat berubah pada hari libur nasional atau acara khusus</small>
                        </div>
                    </div>
                </div>
            </TabPanel>

            <!-- Additional Tab -->
            <TabPanel header="Cara Sewa">
                <div class="card  my-4">
                    <Timeline :value="rentalSteps" align="alternate" class="customized-timeline">
                        <template #marker="slotProps">
                            <span class="flex w-2rem h-2rem align-items-center justify-content-center text-white border-circle z-1 shadow-4" 
                                :style="{ backgroundColor: slotProps.item.color }">
                                <i :class="slotProps.item.icon"></i>
                            </span>
                        </template>
                        <template #content="slotProps">
                            <Card class="mt-3 shadow-3">
                                <template #title>
                                    {{ slotProps.item.status }}
                                </template>
                                <template #subtitle>
                                    {{ slotProps.item.subtitle }}
                                </template>
                                <template #content>
                                    <div class="flex flex-column align-items-center gap-3">
                                        <i :class="slotProps.item.contentIcon + ' text-6xl text-primary'"></i>
                                        <p class="text-700 line-height-3 m-0 text-center">
                                            {{ slotProps.item.description }}
                                        </p>
                                    </div>
                                </template>
                            </Card>
                        </template>
                    </Timeline>
                </div>
            </TabPanel>
        </TabView>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Daftar Ketentuan Sewa dalam Bentuk Array
const rentalRules = ref([
    { text: "Lama sewa adalah 2 hari 1 malam. Maksimal pengembalian hingga pukul 00.00 WIB." },
    { text: "Penyewa wajib meninggalkan jaminan berupa identitas resmi (KTP, KTM, SIM, dll)." },
    { text: "Jika terjadi keterlambatan pengembalian, akan dikenakan biaya tambahan sesuai SOP." },
    { text: "Kerusakan atau kehilangan barang sepenuhnya menjadi tanggung jawab penyewa." },
    { text: "Disarankan melakukan pemesanan atau booking terlebih dahulu untuk ketersediaan barang." }
]);

const operatingHours = ref([
    { day: "Senin", hours: "07:00 - 22:00" },
    { day: "Selasa", hours: "07:00 - 22:00" },
    { day: "Rabu", hours: "07:00 - 22:00" },
    { day: "Kamis", hours: "07:00 - 22:00" },
    { day: "Jumat", hours: "07:00 - 22:00" },
    { day: "Sabtu", hours: "07:00 - 23:30" },
    { day: "Minggu", hours: "12:30 - 23:30" }
]);

const rentalSteps = ref([
    { 
        status: 'Pilih Barang',
        subtitle: 'Langkah 1',
        icon: 'pi pi-search',
        contentIcon: 'pi pi-shopping-bag',
        color: '#9C27B0',
        description: 'Pilih peralatan yang Anda butuhkan dari katalog lengkap kami. Pastikan untuk memeriksa spesifikasi dan ketersediaan barang.'
    },
    { 
        status: 'Tambah ke Keranjang',
        subtitle: 'Langkah 2',
        icon: 'pi pi-shopping-cart',
        contentIcon: 'pi pi-cart-plus',
        color: '#673AB7',
        description: 'Masukkan barang ke keranjang, tentukan tanggal sewa dan durasi penyewaan yang Anda inginkan.'
    },
    { 
        status: 'Pembayaran',
        subtitle: 'Langkah 3',
        icon: 'pi pi-credit-card',
        contentIcon: 'pi pi-wallet',
        color: '#FF9800',
        description: 'Lakukan pembayaran minimal DP 50% dan siapkan jaminan berupa identitas resmi (KTP/SIM).'
    },
    { 
        status: 'Ambil Barang',
        subtitle: 'Langkah 4',
        icon: 'pi pi-check-circle',
        contentIcon: 'pi pi-box',
        color: '#607D8B',
        description: 'Datang ke toko sesuai jadwal yang ditentukan. Tim kami akan membantu memeriksa kelengkapan barang.'
    }
]);
</script>

<style scoped>


@media screen and (max-width: 960px) {
    ::v-deep(.customized-timeline) {
        .p-timeline-event:nth-child(even) {
            flex-direction: row !important;

            .p-timeline-event-content {
                text-align: left;
            }
        }

        .p-timeline-event-opposite {
            flex: 0;
        }
    }
}
</style>
