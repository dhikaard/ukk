<template>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="grid mb-7">
            <div class="col-12 lg:col-6">
                <div class="flex justify-content-center align-items-center h-full w-full">
                    <div class="flex flex-column align-items-center">
                        <img v-for="(image, i) of images2" :key="image" :src="product.urlImg"
                            class="w-full cursor-pointer border-2 border-round border-transparent transition-colors transition-duration-150"
                            :class="{ 'border-primary': selectedImageIndex2 === i }" @click="selectedImageIndex2 = i" />
                    </div>
                    <div class="pl-3 w-10">
                        <img :src="product.urlImg" class="w-full" />
                    </div>
                </div>
            </div>
            <div class="col-12 lg:col-6 py-3 lg:pl-6">
                <div class="flex align-items-center justify-content-between text-xl font-semibold text-900 mb-4">
                    <div>
                        {{ product.brandName }}
                        <span class="font-normal">| {{ product.ctgrName }}</span>
                    </div>
                    <Tag :severity="product.active === 'Y' ? 'success' : 'danger'" style="word-wrap: break-word;"
                        class="p-2 text-xl ml-auto">
                        TERSISA: {{ product.stock }}
                    </Tag>
                </div>
                <div class="flex align-items-center text-xl font-medium text-900 mb-4">{{ product.productName }}</div>
                <div class="flex align-items-center justify-content-between mb-5">
                    <span class="text-900 font-medium text-3xl block">{{ toCurrencyLocale(product.price) }} /
                        hari</span>
                </div>

                <div class="font-bold text-900 mb-3">Tanggal Penyewaan</div>
                <div class="flex align-items-center mb-5">
                    <Calendar showIcon v-model="dates" selectionMode="range" :manualInput="false" dateFormat="d M yy" />
                </div>

                <div class="font-bold text-900 mb-3">Quantity</div>
                <div class="flex flex-column sm:flex-row sm:align-items-center sm:justify-content-between">
                    <InputNumber :showButtons="true" buttonLayout="horizontal" spinnerMode="horizontal" :min="0"
                        inputClass="w-3rem text-center" v-model="quantity1" decrementButtonClass="p-button-text"
                        incrementButtonClass="p-button-text" incrementButtonIcon="pi pi-plus"
                        decrementButtonIcon="pi pi-minus">
                    </InputNumber>
                    <div class="flex align-items-center flex-1 mt-3 sm:mt-0 ml-0 sm:ml-5">
                        <Button icon="pi pi-shopping-cart" label="Sewa Sekarang" class="flex-1 mr-5"
                            @click="redirectToWhatsApp"></Button>
                        <i class="pi pi-question-circle text-2xl cursor-pointer"></i>
                    </div>
                </div>
            </div>
        </div>

        <TabView>
            <TabPanel header="Deskripsi">
                <div class="text-900 font-medium text-3xl mb-4 mt-2">Deskripsi</div>
                <p class="line-height-3 text-700 p-0 mx-0 mt-0 mb-4">{{ product.desc }}</p>
            </TabPanel>
            <TabPanel header="Syarat & Ketentuan">
                <div class="text-900 font-medium text-3xl mb-4 mt-2">Syarat & Ketentuan</div>
                <div class="grid">
                    <div class="col-12 lg:col-4">
                        <span class="text-900 block font-medium mb-3">Syarat</span>
                        <ul class="py-0 pl-3 m-0 text-700 mb-3">
                            <li class="mb-2">Barang yang disewa hanya boleh digunakan untuk keperluan pribadi dan tidak
                                diperbolehkan untuk disewakan kembali.</li>
                            <li class="mb-2">Pembayaran dilakukan di tempat serta memberikan jaminan.</li>
                        </ul>
                    </div>
                    <div class="col-12 lg:col-4 ml-5">
                        <span class="text-900 block font-medium mb-3">Ketentuan</span>
                        <ul class="p-0 m-0 text-700 mb-3">
                            <li class="mb-2"><span class="font-medium">Denda keterlambatan:</span> {{ product.fineBill
                                }}% dari harga sewa per hari.</li>
                            <li class="mb-2"><span class="font-medium">Pembatalan Penyewaan:</span> Penyewa yang
                                membatalkan pesanan kurang dari 1 hari sebelum tanggal penyewaan akan dikenakan biaya
                                pembatalan sebesar 10% dari total harga sewa.</li>
                            <li class="mb-2"><span class="font-medium">Harga dan Pembayaran:</span> Harga penyewaan barang dihitung
                                per hari atau sesuai ketentuan yang
                                disepakati sebelumnya.</li>
                            <li><span class="font-medium">Periode Penyewaan:</span>Barang dapat disewa dengan minimal
                                durasi 1 hari.</li>
                        </ul>
                    </div>
                </div>
            </TabPanel>
        </TabView>
    </div>
</template>
<script setup>
import { onMounted, ref } from "vue";
import { toCurrencyLocale } from '@/utils/currency';
import { useProductDetailStore } from '@/stores/product-detail.store';
import { useRoute } from 'vue-router';
import local from '@/utils/local-storage';

const context = useProductDetailStore();
const dates = ref();
const product = ref({});
const route = useRoute();

const redirectToWhatsApp = () => {
    const userData = {
        name: local.getUser()[0].name,
        phone: local.getUser()[0].phone,
        address: local.getUser()[0].address,
    };

    const rentalDetails = {
        productName: product.value.productName,
        brandName: product.value.brandName,
        ctgrName: product.value.ctgrName,
        price: toCurrencyLocale(product.value.price),
        fineBill: product.value.fineBill,
    };
    const dateRange = dates.value
        ? `${dates.value[0].toLocaleDateString()} s/d ${dates.value[1].toLocaleDateString()}`
        : "Belum dipilih";

    const message = `Halo [Nama Usaha],
  
Saya ingin melakukan pemesanan barang rental dengan detail sebagai berikut:

Nama Lengkap: ${userData.name || ""}
Nomor HP: ${userData.phone || ""}
Alamat: ${userData.address || ""}

Detail Barang:

Nama Barang: ${rentalDetails.productName}
Kategori Barang: ${rentalDetails.ctgrName}
Merk Barang: ${rentalDetails.brandName}

Informasi Penyewaan:

Tanggal Penyewaan: ${dateRange}
Harga per Hari: ${rentalDetails.price}

Catatan:
Saya memahami bahwa apabila barang tidak dikembalikan tepat waktu, saya akan dikenakan denda sebesar ${rentalDetails.fineBill}% per hari keterlambatan sesuai dengan syarat dan ketentuan yang berlaku.

Mohon konfirmasinya, apakah barang tersedia untuk tanggal yang saya ajukan. Terima kasih.`;

    const waURL = `https://api.whatsapp.com/send?phone=6281229530843&text=${encodeURIComponent(
        message
    )}`;

    window.open(waURL, "_blank");
};

onMounted(async () => {
    const productCode = route.params.productCode;
    await context.getProducts(productCode);
    product.value = context.selectedProduct;
});
</script>
