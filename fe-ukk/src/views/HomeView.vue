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
    <div>
      <div class="text-900 font-bold text-3xl text-center">
        Adventure, Outdoor & Camping
        <p class="text-600 font-normal text-base text-center">
          Kami menyediakan berbagai macam perlengkapan untuk kegiatan outdoor Anda
        </p>
      </div>
    </div>
    <Divider class="w-full"></Divider>
    <div class="grid grid-nogutter">
      <div class="col-12 flex flex-column lg:flex-row align-items-center mb-1 gap-2">
          <Button class="p-button-rounded p-button-primary text-white px-5 py-3 h-2rem lg:h-2rem w-full lg:w-auto border-none" label="Filters" :icon="openDropdown ? 'ml-3 pi pi-chevron-down' : 'ml-3 pi pi-chevron-up'" iconPos="right"
              v-styleclass="{ selector: '.filter-container', enterClass:'hidden', enterActiveClass:'slidedown', leaveToClass:'hidden', leaveActiveClass:'slideup' }" @click="openDropdown = !openDropdown"></Button>
              <div class="grid flex-column lg:flex-row w-full h-full">
                <div class="col-12 lg:col flex align-items-center flex-wrap" style="column-gap: 5px; row-gap:5px;">
                  <Chip v-for="filter of context.selectedFilters" :key="filter" :label="filter" removable class="mr-3 h-auto px-4 mt-3 lg:mt-0" removeIcon="pi pi-times" :style="{'border-radius':'50px'}"
                        @click="context.selectedFilters.splice(context.selectedFilters.indexOf(filter.toString()), 1) && context.selectedCategory.splice(context.selectedCategory.indexOf(filter), 1)">
                  </Chip>
                  <a v-ripple v-if="context.selectedFilters.length > 0" tabindex="0" class="text-900 cursor-pointer text-center px-3 py-2 mt-3 lg:mt-0 border-1 border-200 lg:border-none inline-block hover:bg-primary hover:border-primary transition-duration-150 p-ripple" style="border-radius:50px; max-width: 7rem;" @click="context.selectedFilters = []; context.selectedCategory = [];">Clear All</a>
                </div>
              </div>
            </div>
            <div class="filter-container col-12 overflow-hidden transition-all transition-duration-400 transition-ease-in-out">
  <div class="grid grid-nogutter flex-column lg:flex-row">
    <!-- Filter -->
    <div class="grid formgrid flex-auto lg:flex-1 col-12 mt-0 lg:mt-2 mr-0 lg:mr-4 px-4 flex-column border-1 surface-border border-round">
      <p class="text-900 font-medium mb-3 flex justify-content-between w-full transition-duration-150">Filter Produk</p>

      <!-- Search Field -->
        <IconField iconPosition="left">
          <InputIcon class="pi pi-search" />
          <InputText v-model="context.keyword" placeholder="Cari Barang" class="w-full" />
        </IconField>

      <!-- Filter Kategori -->
      <div class="grid mt-4">
        <div class="col-6">
          <FloatLabel class="w-full">
            <MultiSelect id="category" filter v-model="context.category" :options="context.categoryOptions" optionLabel="name" :maxSelectedLabels="3" class="w-full" />
            <label for="category" class=" -ml-1 text-sm">Pilih Kategori</label>
          </FloatLabel>
        </div>

        <!-- Dropdown Urutkan -->
        <div class="col-6">
          <FloatLabel class="w-full">
            <Dropdown v-model="context.priceOrder" :options="['A-Z', 'Z-A', 'Terbaru', 'Termurah ke Termahal', 'Termahal ke Termurah']" placeholder="Urutkan" class="w-full" showClear/>
            <label for="priceOrder" class=" -ml-1 text-sm">Urutkan Berdasarkan</label>
          </FloatLabel>
        </div>
      </div>

      <!-- Filter Button -->
      <div class="flex flex-column mt-3">
        <Button label="Terapkan Filter" icon="pi pi-filter" class="text-white p-button-rounded w-full w-auto p-button-primary h-2rem mb-2" @click="applyFilter" />
      </div>
    </div>
  </div>
</div>

      <div class="w-full border-2 border-dashed border-round-xs surface-border surface-section mt-5 ">
        <DataView :value="context.product" :layout="context.layout" class="border-round-lg">
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

          <template #list="slotProps">
            <div class="grid grid-nogutter">
              <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3"
                  :class="{ 'border-top-1 surface-border': index !== 0 }">
                  <div class="md:w-10rem relative">
                    <img class="border-round w-full"
                      :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`" :alt="item.name"
                      style="max-width: 500px" />
                    <Tag :value="item.inventoryStatus" :severity="getSeverity(item)" class="absolute"
                      style="left: 4px; top: 4px"></Tag>
                  </div>
                  <div
                    class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                    <div class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                      <div>
                        <span class="font-medium text-secondary text-sm">{{ item.category }}</span>
                        <div class="text-lg font-medium text-900 mt-2">{{ item.name }}</div>
                      </div>
                    </div>
                    <div class="flex flex-column md:align-items-end gap-5">
                      <span class="text-xl font-semibold text-900">${{ item.price }}</span>
                      <div class="flex flex-row-reverse md:flex-row gap-2">
                        <Button @click="showDetail(item)" icon="pi pi-shopping-cart" label="Lihat Detail"
                          :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                          class="flex-auto md:flex-initial white-space-nowrap"></Button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template #grid="slotProps">
            <div class="grid grid-nogutter">
              <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 sm:col-6 md:col-4 p-2">
                <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                  <div class="surface-50 flex justify-content-center border-round p-3">
                    <div class="relative mx-auto">
                      <img class="border-round w-full"
                        :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`" :alt="item.name"
                        style="max-width: 300px" />
                      <Tag :value="item.inventoryStatus" :severity="getSeverity(item)" class="absolute"
                        style="left: 4px; top: 4px"></Tag>
                    </div>
                  </div>
                  <div class="pt-4">
                    <div class="flex flex-row justify-content-between align-items-start gap-2">
                      <div>
                        <span class="font-medium text-secondary text-sm">{{ item.category }}</span>
                        <div class="text-lg font-medium text-900 mt-1">{{ item.name }}</div>
                      </div>
                    </div>
                    <div class="flex flex-column gap-4 mt-4">
                      <span class="text-2xl font-semibold text-900">${{ item.price }}</span>
                      <div class="flex gap-2">
                        <Button @click="showDetail(item)" icon="pi pi-shopping-cart" label="Lihat Detail"
                          :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                          class="flex-auto white-space-nowrap"></Button>
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
</div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useHomeViewStore } from '@/stores/home-view.store';
import { useRouter } from 'vue-router';
import CommandMenu from '@/components/CommandMenu.vue';

const router = useRouter();
const context = useHomeViewStore();
const commandMenuVisible = ref(false);

const showDetail = (data) => {
  context.setSelectedData(data);
  
  console.log('context.setSelectedData', data.id);
  const id = context.selectedData.id;
  router.push({ name: 'detailProduct', params: { id } });
};

const showCommandMenu = () => {
    commandMenuVisible.value = true;
};

onMounted(() => {
  context.getProducts();
  context.updatePaginatedProducts();
});

const getSeverity = (product) => {
  switch (product.inventoryStatus) {
    case "INSTOCK":
      return "success";

    case "LOWSTOCK":
      return "warn";

    case "OUTOFSTOCK":
      return "danger";

    default:
      return null;
  }
};

</script>
