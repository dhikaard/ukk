<template>
    <div class="surface-card px-2 py-7 md:px-5 shadow-2 border-round">
        <div class="">
            <div class="text-900 font-bold text-3xl text-center">
                Informasi & Petunjuk
                <p class="text-600 font-normal text-base text-center">
                    Untuk memastikan pengalaman rental yang nyaman dan aman, kami menyediakan panduan lengkap mengenai
                    proses penyewaan, pembayaran, dan pengembalian barang. Pastikan Anda membaca informasi ini sebelum
                    melakukan pemesanan. Dengan menyewa di tempat kami, Anda dianggap telah menyetujui seluruh peraturan
                    yang berlaku.</p>
            </div>
        </div>
        <TabView>
            <TabPanel header="Ketentuan" class="border-round-lg" >
                <div class="grid">
                    <p class="font-bold text-xl">Berikut adalah ketentuan dari sistem rental kami :</p>
                    <div v-for="(rule, index) in rentalRules" :key="index" class="col-12">
                        <div class="flex align-items-start gap-3 p-3 surface-100 border-round">
                            <span class="text-lg">{{ index + 1 }}.</span>
                            <span class="text-lg">{{ rule.text }}</span>
                        </div>
                    </div>
                </div>
            </TabPanel>
            <TabPanel header="Jam Operasional">
                <p class="font-bold text-xl">Berikut adalah jam operasional dari toko kami :</p>
                <div class="grid">
                    <div v-for="(day, index) in operatingHours" :key="index" class="col-12 md:col-6">
                        <div class="flex justify-content-between align-items-center p-2 border-round bg-gray-100">
                            <span class="font-medium text-lg">{{ day.day }}</span>
                            <span class="text-sm">{{ day.hours }}</span>
                        </div>
                    </div>
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
</script>
