<template>
<div class="surface-section">
    <CommandMenu :visible="commandMenuVisible" @update:visible="commandMenuVisible = $event" />
    <div class="block lg:hidden lg:w-3 px-2 md:px-3 mt-3">
    <IconField iconPosition="left" class="w-12">
        <InputIcon class="pi pi-search"> </InputIcon>
        <InputText v-model="context.keyword" placeholder="Cari menu, pintasan, tema, dan yang lainnya.." class="w-full"  @click="showCommandMenu"/>
    </IconField>
    </div>
    <div class="surface-section py-7 px-2 md:px-3">
    <!-- Header/Title Section -->
    <div class="text-center py-5">
        <h1 class="text-900 font-bold text-4xl mb-3">Adventure, Outdoor & Camping</h1>
        <p class="text-600 font-normal text-xl">
        Kami menyediakan berbagai macam perlengkapan untuk kegiatan outdoor Anda
        </p>
    </div>

    <!-- Search & Filter Section -->
        <div class="surface-card p-4 border-round shadow-2 mb-5">
        <!-- Search Bar -->
        <div class="flex flex-column lg:flex-row gap-3 mb-3">
            <Button
            :label="isFilterOpen ? 'Sembunyikan Filter' : 'Tampilkan Filter'"
            :icon="isFilterOpen ? 'pi pi-chevron-up' : 'pi pi-chevron-down'"
            class="p-button-outlined w-full lg:w-auto"
            @click="isFilterOpen = !isFilterOpen"
            />
            <span class="flex-auto">
            <IconField iconPosition="left">
                <InputIcon class="pi pi-search" />
                <InputText v-model="context.keyword" placeholder="Cari Barang" class="w-full" />
            </IconField>

            </span>
        </div>

        <!-- Active Filters -->
        <div class="flex flex-wrap gap-2 mb-3" v-if="context.category?.length || context.orderBy">
            <Chip
            v-for="cat in context.category"
            :key="cat.code"
            :label="cat.name"
            removable
            class="bg-primary-50"
            @remove="removeCategory(cat)"
            />
            <Chip
            v-if="context.orderBy"
            :label="context.orderBy.name"
            removable
            class="bg-primary-50"
            @remove="removeOrder"
            />
            <Button
            icon="pi pi-times"
            label="Reset Filter"
            severity="secondary"
            text
            @click="context.resetFilter()"
            />
        </div>

        <!-- Filter Panel -->
        <div class="overflow-hidden transition-all transition-duration-400"
                :class="{ 'hidden': !isFilterOpen }">
            <Divider class="my-3" />
            <div class="grid">
            <div class="col-12 md:col-6">
                <label class="block text-900 font-medium mb-2">Kategori</label>
                <MultiSelect
                v-model="context.category"
                :options="context.categoryOptions"
                optionLabel="name"
                placeholder="Pilih Kategori"
                class="w-full"
                :maxSelectedLabels="3"
                filter
                />
            </div>
            <div class="col-12 md:col-6">
                <label class="block text-900 font-medium mb-2">Urutkan Berdasarkan</label>
                <Dropdown
                v-model="context.orderBy"
                :options="context.orderByOptions"
                optionLabel="name"
                placeholder="Pilih Urutan"
                class="w-full"
                showClear
                />
            </div>
            </div>
        </div>
        </div>

        <div class="w-full border-2 border-dashed border-round-xs surface-border surface-section ">
        <DataView :value="context.products" :layout="context.layout" :loading="context.loading['products']" class="border-round-lg">
            <template #header>
            <div class="flex justify-content-between w-full align-items-center border-round-lg">
                <Paginator :rows="context.rows" :totalRecords="context.totalRecords" @page="context.onPageChange"
                :template="{
                    '640px': 'PrevPageLink CurrentPageReport NextPageLink',
                    '960px': 'FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink',
                    '1300px': 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink',
                    default: 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown',
                }" class="-mx-3" />
                <DataViewLayoutOptions v-model="context.layout" />
            </div>
            </template>

            <!-- Empty State -->
            <template #empty>
            <div class="flex flex-column align-items-center justify-content-center p-6">
                <i class="pi pi-inbox text-6xl text-primary mb-4"></i>
                <h3 class="text-900 font-medium text-xl mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-600 mb-4 text-center">Coba ubah filter pencarian Anda atau cari dengan kata kunci lain.</p>
                <Button label="Reset Filter" icon="pi pi-refresh" outlined @click="context.resetFilter()" />
            </div>
            </template>


            <template #list="slotProps">
            <div class="grid grid-nogutter">
                <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3"
                    :class="{ 'border-top-1 surface-border': index !== 0 }">
                    <div class="md:w-10rem relative">
                    <Skeleton v-if="context.loading['products']" height="5rem"></Skeleton>
                    <img class="border-round w-full" v-if="!context.loading['products']"
                        :src="item.image" :alt="item.name"
                        style="max-width: 500px" />
                    </div>
                    <div
                    class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                    <div class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                        <div>
                        <Skeleton v-if="context.loading['products']" height="1rem" width="5rem"></Skeleton>
                        <Skeleton v-if="context.loading['products']" height="1rem" width="5rem" class="mt-2"></Skeleton>
                        <span v-if="!context.loading['products']" class="font-medium text-secondary text-sm">{{ item.category }}</span>
                        <div v-if="!context.loading['products']" class="font-medium text-900 mt-2">{{ item.name }}</div>
                        </div>
                        <Skeleton v-if="context.loading['products']" height="1rem" width="7rem"></Skeleton>
                        <Tag v-if="!context.loading['products']"
                            :value="item.inventoryStatus === 'INSTOCK' ? `Tersedia (${item.quantity})` : 'Tidak Tersedia'"
                            :severity="getSeverity(item)" />
                    </div>
                    <div class="flex flex-column md:align-items-end gap-5">
                        <Skeleton v-if="context.loading['products']" height="2rem"></Skeleton>
                        <span v-if="!context.loading['products']" class="text-xl font-semibold text-900">{{ toCurrencyLocale(item.price) }}</span>
                        <div class="flex flex-row-reverse md:flex-row gap-2">
                        <Skeleton v-if="context.loading['products']" height="2rem" width="7rem"></Skeleton>
                        <Button @click="showDetail(item)"
                                v-if="!context.loading['products']"
                                icon="pi pi-eye"
                                label="Lihat Detail"
                                class="flex-auto white-space-nowrap" />
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </template>
            <template #grid="slotProps">
            <div class="grid grid-nogutter">
                <div v-for="(item, index) in slotProps.items" :key="item.id" class="col-12 sm:col-6 md:col-3 p-1">
                <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                    <Skeleton v-if="context.loading['products']" height="17rem"></Skeleton>
                    <div class="surface-50 flex justify-content-center border-round p-3" v-if="!context.loading['products']">
                    <div class="relative mx-auto">
                        <img class="border-round w-full"
                        :src="item.image" :alt="item.name"
                        style="max-width: 300px; height: 200px; object-fit: cover;" />
                    </div>
                    </div>
                    <div class="pt-4">
                    <div class="flex flex-row justify-content-between align-items-start gap-2">
                        <div>
                        <Skeleton v-if="context.loading['products']" height="1rem" width="5rem"></Skeleton>
                        <Skeleton v-if="context.loading['products']" height="1rem" width="10rem" class="mt-1"></Skeleton>
                        <span v-if="!context.loading['products']" class="font-medium text-secondary text-sm">{{ item.category }}</span>
                        <div v-if="!context.loading['products']" class="font-medium text-900 mt-1">{{ item.name }}</div>
                        </div>
                        <Skeleton v-if="context.loading['products']" height="2rem" width="7rem"></Skeleton>
                        <Tag v-if="!context.loading['products']"
                            :value="item.inventoryStatus === 'INSTOCK' ? `Tersedia (${item.quantity})` : 'Tidak Tersedia'"
                            :severity="getSeverity(item)" />
                    </div>
                    <div class="flex flex-column gap-4 mt-4">
                        <Skeleton v-if="context.loading['products']" height="2rem" width="10rem"></Skeleton>
                        <span v-if="!context.loading['products']" class="text-2xl font-semibold text-900">{{ toCurrencyLocale(item.price) }}</span>
                        <div class="flex gap-2">
                        <Skeleton v-if="context.loading['products']" height="2rem"></Skeleton>
                        <Button @click="showDetail(item)"
                                v-if="!context.loading['products']"
                                icon="pi pi-eye"
                                label="Lihat Detail"
                                class="flex-auto white-space-nowrap" />
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </template>
        </DataView>
        </div>
    </div>
</div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRentViewStore } from '@/stores/rent-view.store';
import { useRouter } from 'vue-router';
import CommandMenu from '@/components/CommandMenu.vue';
import { toCurrencyLocale } from '@/utils/currency';


const router = useRouter();
const context = useRentViewStore();
const commandMenuVisible = ref(false);
const isFilterOpen = ref(false);


const showDetail = (data) => {
    // Create URL friendly slug from item name
    const slug = data.name
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');

    router.push({
        name: 'detailProduct',
        params: {
            code: data.code,
            slug: slug
        }
    });
};

const showCommandMenu = () => {
    commandMenuVisible.value = true;
};

onMounted(async () => {
    await Promise.all([
        context.getCategories(),
        context.getProducts()
    ]);
});

const removeCategory = (cat) => {
    context.category = context.category.filter(c => c.code !== cat.code);
    context.applyFilter();
};

const removeOrder = () => {
    context.orderBy = null;
    context.applyFilter();
};

// Watch for keyword changes with delay
watch(() => context.keyword, (newValue) => {
    if (newValue !== undefined) {
    setTimeout(() => {
        context.applyFilter();
    }, 300);
    }
}, { immediate: true });

// Watch category changes
watch(() => context.category, () => {
    context.applyFilter();
}, { deep: true });

// Watch sort changes
watch(() => context.orderBy, () => {
    context.applyFilter();
});


const getSeverity = (item) => {
    const totalStock = (item.quantity || 0); // This already includes both stock and itemStock sum

    if (totalStock === 0) {
        return 'danger';
    } else if (totalStock <= 5) {
        return 'warning';
    } else {
        return 'success';
    }
};

</script>
