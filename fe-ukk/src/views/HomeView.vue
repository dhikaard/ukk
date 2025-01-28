<template>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
            <div class="flex flex-column text-center md:text-left">
                <div class="flex align-items-center justify-content-center md:justify-content-start mb-2">
                    <span
                        class="border-circle w-3rem h-3rem flex align-items-center justify-content-center surface-100 mr-2">
                        <i class="pi pi-warehouse text-900 text-3xl"></i>
                    </span>
                    <span class="text-900 text-3xl font-medium">Sewa Barang</span>
                </div>
                <span class="text-600 text-xl">Yuk, cari barang yang mau kamu sewa!😋</span>
            </div>
            <span class="p-input-icon-right mt-5 mb-2 md:mt-0 md:mb-0 w-full lg:w-25rem">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="context.keyword" placeholder="Nama Barang" class="w-full" @keyup.enter="context.getProducts"/>
                </IconField>
            </span>
        </div>
        <Divider class="w-full"></Divider>
        <div class="flex flex-wrap lg:flex-nowrap">
            <div class="col-fixed lg:col-2 flex p-0 flex-column w-full lg:w-3">
                <div class="flex flex-column pb-2">
                    <p tabindex="0"
                        class="cursor-pointer text-900 font-bold mb-3 hover:text-primary transition-duration-150"
                        @click="resetCategoryFilter">
                        Kategori
                    </p>
                    <div v-for="category in context.ctgrOptions" :key="category.id" class="mb-2">
                        <Skeleton v-if="context.loading['getCtgr']" class="mb-2 w-10rem"></Skeleton>
                        <a v-else tabindex="0" @click="filterByCategory(category.id)"
                            :class="{'text-primary': context.ctgrId === category.id, 'text-900': context.ctgrId !== category.id}"
                            class="cursor-pointer font-medium mb-3 hover:text-primary transition-duration-150">
                            {{ category.ctgrName }}
                        </a>
                    </div>
                </div>
                <Divider class="w-full m-0 p-0"></Divider>

                <Accordion :multiple="true" class="-mb-1 mt-3" :activeIndex="[0, 1, 2, 3]">
                    <AccordionTab
                        :header="'Merk (' + (selectedBrand_1.length !== 0 ? selectedBrand_1.length : '0') + ')'">
                        <div class="ransition-all transition-duration-400 transition-ease-in-out">
                            <div class="mb-3">
                                <IconField iconPosition="left">
                                    <InputIcon class="pi pi-search"> </InputIcon>
                                    <InputText v-model="wleo" placeholder="Cari" class="w-full" />
                                </IconField>
                            </div>
                            <div v-for="brand in context.brandOptions" :key="brand.id"
                                class="flex w-full justify-content-between">
                                <Skeleton v-if="context.loading['getBrand']" class="mb-2 h-2rem w-9rem"></Skeleton>
                                <div v-else class="field-checkbox">
                                    <Checkbox :value="brand.id" :id="brand.id" v-model="selectedBrand_1"
                                        @change="filterByBrand"></Checkbox>
                                    <label :for="brand.id" class="text-900">{{ brand.brandName }}</label>
                                </div>
                            </div>
                        </div>
                    </AccordionTab>
                    <AccordionTab header="Rentang Harga">
                        <div class="transition-all transition-duration-400 transition-ease-in-out">
                            <Slider v-model="rangeValues" :range="true" class="mt-3 mx-auto" style="max-width:93%"
                                max="2000000" @change="filterByPriceRange"></Slider>
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
                    <DataView :value="context.dataTable" :layout="layout" class="h-full">
                        <template #header>
                            <div class="flex justify-content-end w-full align-item-center">
                                <DataViewLayoutOptions v-model="layout" />
                            </div>
                        </template>

                        <template #list="slotProps">
                            <div class="grid grid-nogutter">
                                <div v-for="(item, index) in context.dataTable" :key="index" class="col-12">
                                    <div class="flex flex-column sm:flex-row sm:align-items-center p-4 pb-6 gap-3"
                                        :class="{ 'border-top-1 surface-border': index !== 0 }">
                                        <div class="surface-border surface-card border-round flex flex-column">
                                            <Skeleton v-if="context.loading['getProducts']" width="13rem" height="10rem"></Skeleton>
                                            <div v-else
                                                class="surface-0 flex justify-content-center align-items-center border-round p-3 w-full h-10rem">
                                                <div class="relative mx-auto">
                                                    <img class="border-round w-full max-h-10rem md:max-h-8rem" :src="item.urlImg"
                                                        :alt="item.productName" style="max-width: 300px" />
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                            <div
                                                class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                                <div>
                                                    <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-10rem mb-2"></Skeleton>
                                                    <span v-else class="font-bold text-secondary text-sm">{{ item.brandName }}</span>
                                                    <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-8rem mb-2"></Skeleton>
                                                    <span v-else class="font-medium text-secondary text-sm"> | {{ item.ctgrName }}</span>
                                                    <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-8rem mb-2"></Skeleton>
                                                    <div v-else class="text-lg font-medium text-900 mt-2">{{ item.productName }}</div>
                                                </div>
                                                <Tag v-if="!context.loading['getProducts']" :value="item.active === 'Y' ? 'TERSEDIA' : 'TIDAK TERSEDIA'"
                                                    :severity="getSeverity(item)"></Tag>
                                            </div>
                                            <div class="flex flex-column md:align-items-end gap-5">
                                                <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-8rem"></Skeleton>
                                                <span v-else class="text-xl font-semibold text-900">{{ toCurrencyLocale(item.price) }}</span>
                                                <div class="flex flex-row-reverse md:flex-row gap-2">
                                                    <Button v-if="!context.loading['getProducts']" icon="pi pi-external-link" label="Lihat Detail" outlined
                                                        class="flex-auto white-space-nowrap p-button-sm" @click="viewDetail(item.productCode)"></Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template #grid="slotProps">
                            <div class="grid grid-nogutter">
                                <div v-for="(item, index) in context.dataTable" :key="index"
                                    class="col-12 sm:col-6 md:col-4 p-2">
                                    <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                                        <Skeleton v-if="context.loading['getProducts']" width="15rem" height="10rem"></Skeleton>
                                        <div v-else 
                                            class="surface-50 flex justify-content-center align-items-center border-round p-3 w-full h-10rem">
                                            <div class="relative mx-auto">
                                                <img class="border-round w-full max-h-8rem" :src="item.urlImg"
                                                    :alt="item.productName" style="max-width: 300px" />
                                            </div>
                                        </div>
                                        <div class="pt-4">
                                            <div class="flex flex-row justify-content-between align-items-start gap-2">
                                                <div class="relative w-full">
                                                    <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-10rem mb-2"></Skeleton>
                                                    <span v-else class="font-bold text-secondary text-sm">{{ item.brandName }}</span>
                                                    <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-8rem mb-2"></Skeleton>
                                                    <span v-else class="font-medium text-secondary text-sm"> | {{ item.ctgrName }}</span>
                                                    <Tag v-if="!context.loading['getProducts']" :value="item.active === 'Y' ? 'TERSEDIA' : 'TIDAK TERSEDIA'"
                                                        :severity="getSeverity(item)" class="absolute" style="right: 0; top: 0"></Tag>
                                                    <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-12rem mt-2"></Skeleton>
                                                    <div v-else class="text-lg font-medium text-900 mt-3">{{ item.productName }}</div>
                                                </div>
                                            </div>
                                            <div class="flex flex-column gap-4 mt-4">
                                                <Skeleton v-if="context.loading['getProducts']" class="h-1rem w-8rem"></Skeleton>
                                                <span v-else class="text-2xl font-semibold text-900">{{ toCurrencyLocale(item.price) }}</span>
                                                <div class="flex gap-2">
                                                    <Button v-if="!context.loading['getProducts']" icon="pi pi-external-link" label="Lihat Detail" outlined
                                                        class="flex-auto white-space-nowrap p-button-sm" @click="viewDetail(item.productCode)"></Button>
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
import { useProductRentStore } from '@/stores/product-rent.store';

const context = useProductRentStore();
const layout = ref('grid');
const selectedBrand_1 = ref([]);
const rangeValues = ref([0, 2000000]);
const router = useRouter();

const viewDetail = (productCode) => {
    router.push({ name: 'product', params: { productCode } });
};

const filterByCategory = (ctgrId) => {
    context.ctgrId = ctgrId;
    context.getProducts();
};

const filterByBrand = () => {
    context.brandId = selectedBrand_1.value;
    context.getProducts();
};

const filterByPriceRange = () => {
    context.priceRange = rangeValues.value;
    context.getProducts();
};

const resetCategoryFilter = () => {
    context.ctgrId = '';
    context.getProducts();
};

onMounted(async () => {
    await context.getCtgr();
    await context.getBrand();
    await context.getProducts();
});

const getSeverity = (product) => {
    if (product.active === 'Y') {
        return 'success';
    } else {
        return 'danger';
    }
};
</script>