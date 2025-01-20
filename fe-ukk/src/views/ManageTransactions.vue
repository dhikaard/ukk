<template>
    <div class="surface-section px-4 pt-8 md:px-6 lg:px-8">
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
            <div class="flex flex-column text-center md:text-left">
                <div class="flex align-items-center justify-content-center md:justify-content-start mb-2">
                    <span
                        class="border-circle w-3rem h-3rem flex align-items-center justify-content-center surface-100 mr-2">
                        <i class="bi bi-ui-checks text-900 text-3xl"></i>
                    </span> <span class="text-900 text-3xl font-medium">Transaksi</span>
                </div>
                <span class="text-600 text-xl">Halaman untuk mengelola transaksi penyewaan!💸</span>
            </div>
            <span class="p-input-icon-right mt-5 mb-2 md:mt-0 md:mb-0 w-full lg:w-25rem">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="value1" placeholder="Nama Penyewa / Nama Barang / Transaksi" class="w-full" />
                </IconField>
            </span>
        </div>
        <section class="flex-wrap gap-3 justify-content-between border-bottom-1 surface-border">
            <Divider class="w-full"></Divider>
            <div class="mt-3">
                <DataTable :value="members" :tabStyle="{ 'min-width': '60rem' }" rowHover>
                    <Column style="min-width:25rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Penyewa</span>
                        </template>
                        <template #body="{ data }">
                            <div class="flex align-items-center gap-3">
                                <Avatar :name="data.name" alt="User Avatar" style="height: 2.5rem;" />
                                <div>
                                    <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.nama }}
                                    </p>
                                    <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.email }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column style="min-width:20rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Alamat / Ponsel</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.address }}</p>
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.phone }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:20rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Barang / Transaksi</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.productName }}</p>
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.trxCode }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:5rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Qty</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{ data.qty }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:20rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Tanggal Penyewaan</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.dateFrom }} - {{
                                data.dateTo }}</p>
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.hari }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:10rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Terlambat (hari)</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{
                                data.terlambatHari }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Total Harga</span>
                        </template>
                        <template #body="{ data }">
                            <!-- Total Harga termasuk denda -->
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{
                                data.totalHarga }}
                            </p>
                            <!-- Denda yg sudah dihitung % -->
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{
                                data.denda }}
                            </p> 
                        </template>
                    </Column>
                    <Column style="min-width:10rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Status</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.status }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:20rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Keterangan</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.desc }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:8rem">
                        <template #body>
                            <Button type="button" icon="pi pi-ellipsis-v" class="p-button-text p-button-secondary"
                                @click="$refs.menu.toggle($event)"></Button>
                            <Menu ref="menu" appendTo="body" popup :model="items"></Menu>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </section>
        <DialogAddProduct :visible="showDialogAddProduct" @update:visible="showDialogAddProduct = $event" />
    </div>
</template>

<script setup>
import { defineAsyncComponent, ref } from 'vue';
import Avatar from '../components/Avatar.vue';
import { toCurrencyLocale } from '../utils/currency';

const DialogAddProduct = defineAsyncComponent(() => import('../components/DialogAddProduct.vue'));
const showDialogAddProduct = ref(false);

const members = ref([
    {
        nama: 'Alexander Agung',
        email: 'alexander@gmail.com',
        address: 'Jl. Cinde Dalam I No. 24',
        phone: '081229530843',
        productName: 'Kamera Lensa M310',
        trxCode: 'LNSM310-2023-12-20',  
        qty: 1,
        active: '2023-12-20',
    },
    {
        nama: 'Ardian Mahardhika',
        email: 'dhikaa@gmail.com',
        address: 'Jl. Cinde Dalam I No. 24',
        phone: '081229530843',
        productName: 'Kamera Lensa M310',
        trxCode: 'LNSM310-2023-12-20',
        qty: 1,
        active: '2023-12-18',
    },
    {
        nama: 'Kevin Linparo',
        email: 'kepinch@gmail.com',
        address: 'Jl. Cinde Dalam I No. 24',
        phone: '081229530843',
        productName: 'Kamera Lensa M310',
        trxCode: 'LNSM310-2023-12-20',
        qty: 1,
        date: '2021-07-23',
        active: '2023-12-15',
    },
    {
        nama: 'Maria Clara',
        email: 'mariaclara@gmail.com',
        address: 'Jl. Cinde Dalam I No. 24',
        phone: '081229530843',
        productName: 'Kamera Lensa M310',
        trxCode: 'LNSM310-2023-12-20',
        qty: 2,
        active: '2023-12-12',
    },
]);

</script>
