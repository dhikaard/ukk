<template>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
            <div class="flex flex-column text-center md:text-left">
                <span class="text-900 text-3xl font-medium mb-2">Sewa Barang</span>
                <span class="text-600 text-xl">Yuk, cari barang yang mau kamu sewa!😋</span>
            </div>
            <span class="p-input-icon-right mt-5 mb-2 md:mt-0 md:mb-0 w-full lg:w-25rem">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="value1" placeholder="Nama Barang" class="w-full" />
                </IconField>
            </span>
        </div>
        <Divider class="w-full"></Divider>
        <div class="flex flex-wrap lg:flex-nowrap">
            <div class="col-fixed lg:col-2 flex p-0 flex-column w-full lg:w-3">
                <div class="flex flex-column p-0">
                    <p tabindex="0"
                        class="cursor-pointer text-900 font-bold mb-3 hover:text-primary transition-duration-150">
                        Kategori</p>
                    <a tabindex="0"
                        class="cursor-pointer text-900 font-medium mb-3 hover:text-primary transition-duration-150">Kamera</a>
                    <a tabindex="0"
                        class="cursor-pointer text-900 font-medium mb-3 hover:text-primary transition-duration-150">Lensa</a>
                    <a tabindex="0"
                        class="cursor-pointer text-900 font-medium mb-3 hover:text-primary transition-duration-150">Baterai</a>
                    <a tabindex="0"
                        class="cursor-pointer text-900 font-medium mb-3 hover:text-primary transition-duration-150">Memori</a>
                    <a tabindex="0"
                        class="cursor-pointer text-900 font-medium mb-3 hover:text-primary transition-duration-150">Tripod</a>
                </div>
                <Divider class="w-full m-0 p-0"></Divider>

                <Accordion :multiple="true" class="-mb-1 mt-3" :activeIndex="[0, 1, 2, 3]">
                    <AccordionTab
                        :header="'Merk (' + (selectedBrand_1.length !== 0 ? selectedBrand_1.length : '0') + ')'">
                        <div class="ransition-all transition-duration-400 transition-ease-in-out">
                            <div class="mb-3">
                                <IconField iconPosition="left">
                                    <InputIcon class="pi pi-search"> </InputIcon>
                                    <InputText v-model="value1" placeholder="Cari" class="w-full" />
                                </IconField>
                            </div>
                            <div class="flex w-full justify-content-between">
                                <div class="field-checkbox">
                                    <Checkbox value="Alfred" id="alfred" v-model="selectedBrand_1"></Checkbox>
                                    <label for="alfred" class="text-900">Alfred</label>
                                </div>
                                <Badge value="42" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                            </div>
                            <div class="flex w-full justify-content-between">
                                <div class="field-checkbox">
                                    <Checkbox value="Hyper" id="hyper" v-model="selectedBrand_1"></Checkbox>
                                    <label for="hyper" class="text-900">Hyper</label>
                                </div>
                                <Badge value="18" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                            </div>
                            <div class="flex w-full justify-content-between">
                                <div class="field-checkbox">
                                    <Checkbox value="Bastion" id="bastion" v-model="selectedBrand_1"></Checkbox>
                                    <label for="bastion" class="text-900">Bastion</label>
                                </div>
                                <Badge value="7" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                            </div>
                            <div class="flex w-full justify-content-between">
                                <div class="field-checkbox">
                                    <Checkbox value="Peak" id="peak" v-model="selectedBrand_1"></Checkbox>
                                    <label for="peak" class="text-900">Peak</label>
                                </div>
                                <Badge value="36" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                            </div>
                            <a tabindex="0" class="block cursor-pointer my-3 text-primary font-medium">Show all...</a>
                        </div>
                    </AccordionTab>
                    <AccordionTab header="Rentang Harga">
                        <div class="transition-all transition-duration-400 transition-ease-in-out">
                            <Slider v-model="rangeValues" :range="true" class="mt-3 mx-auto" style="max-width:93%"
                                max="2000000"></Slider>
                            <div class="flex my-4">
                                <InputNumber inputId="currency-id" mode="currency" :minFractionDigits="0" currency="IDR"
                                    locale="id-ID" :allowEmpty="false" v-model="rangeValues[0]" :min="0"
                                    inputClass="w-full mr-3">
                                </InputNumber>
                                <InputNumber inputId="currency-id" mode="currency" :minFractionDigits="0" currency="IDR"
                                    locale="id-ID" :allowEmpty="false" v-model="rangeValues[1]" inputClass="w-full">
                                </InputNumber>
                            </div>
                        </div>
                    </AccordionTab>
                </Accordion>
            </div>
            <div class="col w-full border-2 border-dashed border-round surface-border surface-section mt-3 lg:mt-0"
                style="max-height: 50rem">
                <ScrollPanel style="width: 100%; height: 100%">
                    <DataView :value="products" :layout="layout" class="h-full">
                        <template #header>
                            <div class="flex justify-content-end w-full align-item-center">
                                <DataViewLayoutOptions v-model="layout" />
                            </div>
                        </template>

                        <template #list="slotProps">
                            <div class="grid grid-nogutter">
                                <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                                    <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3"
                                        :class="{ 'border-top-1 surface-border': index !== 0 }">
                                        <div class="md:w-10rem relative">
                                            <img class="block xl:block mx-auto border-round"
                                                :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`"
                                                :alt="item.name" />
                                            <Tag :value="item.inventoryStatus" :severity="getSeverity(item)"
                                                class="absolute" style="left: 4px; top: 4px"></Tag>
                                        </div>
                                        <div
                                            class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                            <div
                                                class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                                <div>
                                                    <span class="font-medium text-secondary text-sm">{{ item.category
                                                        }}</span>
                                                    <div class="text-lg font-medium text-900 mt-2">{{ item.name }}</div>
                                                </div>
                                                <div class="surface-100 p-1" style="border-radius: 30px">
                                                    <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2"
                                                        style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                                        <span class="text-900 font-medium text-sm">{{ item.rating
                                                            }}</span>
                                                        <i class="pi pi-star-fill text-yellow-500"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-column md:align-items-end gap-5">
                                                <span class="text-xl font-semibold text-900">{{
                                                    toCurrencyLocale(item.price) }}</span>
                                                <div class="flex flex-row-reverse md:flex-row gap-2">
                                                    <Button icon="pi pi-external-link" label="Lihat Detail"
                                                        class="flex-auto white-space-nowrap p-button-sm"
                                                        @click="viewDetail(item.id)"></Button>
                                                    <!-- <Button icon="pi pi-shopping-cart" label="Sewa Sekarang"
                                                        :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                                                        class="flex-auto md:flex-initial white-space-nowrap p-button-sm"></Button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template #grid="slotProps">
                            <div class="grid grid-nogutter">
                                <div v-for="(item, index) in slotProps.items" :key="index"
                                    class="col-12 sm:col-6 md:col-4 p-2">
                                    <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                                        <div class="surface-50 flex justify-content-center border-round p-3">
                                            <div class="relative mx-auto">
                                                <img class="border-round w-full"
                                                    :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`"
                                                    :alt="item.name" style="max-width: 300px" />
                                                <Tag :value="item.inventoryStatus" :severity="getSeverity(item)"
                                                    class="absolute" style="left: 4px; top: 4px"></Tag>
                                            </div>
                                        </div>
                                        <div class="pt-4">
                                            <div class="flex flex-row justify-content-between align-items-start gap-2">
                                                <div>
                                                    <span class="font-medium text-secondary text-sm">{{ item.category
                                                        }}</span>
                                                    <div class="text-lg font-medium text-900 mt-1">{{ item.name }}</div>
                                                </div>
                                                <div class="surface-100 p-1" style="border-radius: 30px">
                                                    <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2"
                                                        style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                                        <span class="text-900 font-medium text-sm">{{ item.rating
                                                            }}</span>
                                                        <i class="pi pi-star-fill text-yellow-500"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-column gap-4 mt-4">
                                                <span class="text-2xl font-semibold text-900">{{
                                                    toCurrencyLocale(item.price) }}</span>
                                                <div class="flex gap-2">
                                                    <!-- <Button icon="pi pi-shopping-cart" label="Sewa Sekarang"
                                                        :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                                                        class="flex-auto white-space-nowrap p-button-sm"></Button> -->
                                                    <Button icon="pi pi-external-link" label="Lihat Detail" outlined
                                                        class="flex-auto white-space-nowrap p-button-sm"
                                                        @click="viewDetail(item.id)"></Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </DataView>
                </ScrollPanel>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRouter } from 'vue-router';
import { toCurrencyLocale } from '../utils/currency';

const products = ref([]);
const layout = ref('grid');
const selectedBrand_1 = ref([]);
const rangeValues = ref([0, 2000000]);
const router = useRouter();

const viewDetail = (id) => {
    router.push({ name: 'product', params: { id } });
};

onMounted(() => {
    products.value = [
        {
            id: '1',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65000,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '2',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 70000,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '3',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 100000,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '4',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '5',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '6',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '7',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '8',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '9',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
        {
            id: '10',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },

        {
            id: '11',
            name: 'Bamboo Watch',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'TERSEDIA',
            rating: 5
        },
    ];
});

const getSeverity = (product) => {
    switch (product.inventoryStatus) {
        case 'TERSEDIA':
            return 'success';

        case 'LOWSTOCK':
            return 'warn';

        case 'OUTOFSTOCK':
            return 'danger';

        default:
            return null;
    }
};


</script>