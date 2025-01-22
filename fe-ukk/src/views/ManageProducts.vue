<template>
    <div class="surface-section px-4 pt-8 md:px-6 lg:px-8">
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
            <div class="flex flex-column text-center md:text-left">
                <div class="flex align-items-center justify-content-center md:justify-content-start mb-2">
                    <span
                        class="border-circle w-3rem h-3rem flex align-items-center justify-content-center surface-100 mr-2">
                        <i class="bi bi-box-seam text-900 text-3xl"></i>
                    </span> <span class="text-900 text-3xl font-medium">Barang</span>
                </div>
                <span class="text-600 text-xl">Kamu dapat dengan mudah mengelola barang sewa di halaman ini!🤭</span>
            </div>
            <span class="p-input-icon-right mt-5 mb-2 md:mt-0 md:mb-0 w-full lg:w-25rem">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="value1" placeholder="Nama Barang / Merk / Kategori" class="w-full" />
                </IconField>
            </span>
        </div>
        <section class="flex-wrap gap-3 justify-content-between border-bottom-1 surface-border">
            <Divider class="w-full"></Divider>
            <div class="flex-shrink-0">
                <Button icon="pi pi-plus" label="Tambah Barang" class="w-auto"
                    @click="showDialogAddProduct = true"></Button>
            </div>
            <div class="mt-3">
                <DataTable :value="members" :tabStyle="{ 'min-width': '60rem' }" rowHover>
                    <Column style="min-width:25rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Barang</span>
                        </template>
                        <template #body="{ data }">
                            <div class="flex align-items-center gap-3">
                                <Avatar :name="data.name" alt="User Avatar" style="height: 2.5rem;" />
                                <div>
                                    <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.namaBarang }}
                                    </p>
                                    <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.merk }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column style="min-width:10rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Kategori</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.ctgr }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:5rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Stok</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{ data.stock }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:10rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Harga / hari</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{
                                toCurrencyLocale(data.price) }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:10rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Denda (%)</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-right text-base text-color-secondary">{{ data.fine_bill
                                }}%</p>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Tanggal Ditambahkan</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.date }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Terakhir Pembaruan</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.date }}</p>
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
        <DialogAddProduct v-model:visible="showDialogAddProduct" />
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
        namaBarang: 'Kamera Lensa M310',
        merk: 'EPSON',
        stock: 1,
        price: 200000,
        fine_bill: 10,
        ctgr: 'Lensa',
        date: '2023-01-15',
        active: '2023-12-20',
    },
    {
        namaBarang: 'Kamera Lensa M310',
        merk: 'EPSON',
        stock: 1,
        price: 200000,
        fine_bill: 10,
        ctgr: 'Lensa',
        date: '2022-10-10',
        active: '2023-12-18',
    },
    {
        namaBarang: 'Kamera Lensa M310',
        merk: 'EPSON',
        stock: 1,
        price: 200000,
        fine_bill: 10,
        ctgr: 'Lensa',
        date: '2021-07-23',
        active: '2023-12-15',
    },
    {
        namaBarang: 'Kamera Lensa M310',
        merk: 'EPSON',
        stock: 1,
        price: 200000,
        fine_bill: 10,
        ctgr: 'Lensa',
        date: '2020-05-30',
        active: '2023-12-12',
    },
]);

</script>
