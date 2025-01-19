<template>
  <div class="surface-section">
    <CommandMenu :visible="commandMenuVisible" @update:visible="commandMenuVisible = $event" />
    <div class="block lg:hidden lg:w-3 px-2 md:px-3 mt-3">
      <IconField iconPosition="right" class="w-12">
        <InputIcon class="pi pi-search"> </InputIcon>
        <InputText v-model="context.keyword" placeholder="Search" class="w-full"  @click="showCommandMenu"/>
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
        <div class="col-12 p-0 mt-5 flex flex-column lg:flex-row align-items-center mb-1">
            <Button class="p-button-rounded p-button-primary  text-white px-5 md:py-2 py-3 lg:mr-4 sm w-full lg:w-auto border-none" label="Filters" :icon="openDropdown ? 'ml-3 pi pi-chevron-down' : 'ml-3 pi pi-chevron-up'" iconPos="right"
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
        <div
        class="filter-container col-12 overflow-hidden transition-all transition-duration-400 transition-ease-in-out">
        <Button label="Terapkan Filter" icon="pi pi-filter" class="p-button-rounded w-full lg:w-auto p-button-primary" @click="applyFilter" />
          <div class="grid grid-nogutter flex-column lg:flex-row">
            <div
              class="flex-auto lg:flex-1 col mt-0 lg:mt-2 mr-0 lg:mr-4 p-4 flex-column border-1 surface-border border-round">
              <a tabindex="0"
                class="cursor-pointer text-900 font-medium mb-3 flex justify-content-between w-full hover:text-primary transition-duration-150">Kategori
                ({{ context.selectedCategory.length }})</a>
              <div class="flex flex-column">
                <div class="mb-3">
                  <IconField iconPosition="left">
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="context.keyword" placeholder="Search" class="w-full" />
                  </IconField>
                </div>
                <div class="flex w-full justify-content-between">
                  <div class="field-checkbox">
                    <Checkbox value="Alfred" id="alfred2" v-model="context.selectedFilters"
                      @change="context.selectedCategory.indexOf('Alfred') === -1 ? context.selectedCategory.push('Alfred') : context.selectedCategory.splice(context.selectedCategory.indexOf('Alfred'), 1)">
                    </Checkbox>
                    <label for="alfred2" class="text-900">Alfred</label>
                  </div>
                  <Badge value="42" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                </div>
              </div>
            </div>
            <div
              class="flex-auto lg:flex-1 col mt-4 lg:mt-2 lg:mr-4 p-4 flex-column border-1 surface-border border-round">
              <a tabindex="0"
                class="cursor-pointer text-900 font-medium mb-3 flex justify-content-between w-full hover:text-primary transition-duration-150">Kisaran Harga</a>
              <div class="transition-all transition-duration-400 transition-ease-in-out">
                <Slider v-model="context.rangeValues" :range="true" class="mt-3 mx-auto" style="max-width:100%" />
                <div class="flex my-4">
                  <InputNumber inputId="currency-id" mode="currency" :minFractionDigits="0" currency="IDR" locale="id-ID" :allowEmpty="false" v-model="context.rangeValues[0]" :min="0" inputClass="w-full mr-3" />
                  <InputNumber inputId="currency-id" mode="currency" :minFractionDigits="0" currency="IDR" locale="id-ID" :allowEmpty="false"  v-model="context.rangeValues[1]" inputClass="w-full" />
                  </div>
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
                        style="max-width: 400px" />
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
                          <Button @click="showProducts" icon="pi pi-shopping-cart" label="Lihat Detail"
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
                          <Button @click="showProducts" icon="pi pi-shopping-cart" label="Lihat Detail"
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
import { onMounted, onUnmounted, ref } from 'vue';
import { useHomeViewStore } from '@/stores/home-view.store';
import CommandMenu from '@/components/CommandMenu.vue';

const context = useHomeViewStore();
const commandMenuVisible = ref(false);

// const DialogRentVue = defineAsyncComponent(() => import('../components/DialogRent.vue'));
// const showProducts = () => {
//     dialog.open(DialogRentVue, {
//         props: {
//             header: 'Product List',
//             style: {
//                 width: '50vw',
//             },
//             breakpoints:{
//                 '960px': '75vw',
//                 '640px': '90vw'
//             },
//             modal: true
//         }
//     });
// }

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
