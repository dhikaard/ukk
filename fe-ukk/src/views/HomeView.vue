<template>
    <div class="block lg:hidden lg:w-3 px-3 pt-5">
    <IconField iconPosition="right" class="w-12">
            <InputIcon class="pi pi-search"> </InputIcon>
            <InputText v-model="value1" placeholder="Search" class="w-full" />
    </IconField>
    </div> 
    <div class="surface-section py-6 px-1 md:px-3 lg:px-5 ">
    <div class="text-900 font-bold text-3xl text-center">
      Adventure, Outdoor & Camping
    </div>
    <p class="text-600 font-normal text-xl text-center">
      Kami menyediakan berbagai macam perlengkapan untuk kegiatan outdoor Anda
    </p>
    <Divider class="w-full"></Divider>
        <div class="grid grid-nogutter">
        <div class="col-12 p-0 mt-2 flex flex-column lg:flex-row align-items-center mb-2">
            <Button class="p-button-rounded text-white px-5 lg:mr-4 sm w-full lg:w-auto border-none" label="Filters" :icon="openDropdown ? 'ml-3 pi pi-chevron-down' : 'ml-3 pi pi-chevron-up'" iconPos="right"
                v-styleclass="{ selector: '.filter-container', enterClass:'hidden', enterActiveClass:'slidedown', leaveToClass:'hidden', leaveActiveClass:'slideup' }" @click="openDropdown = !openDropdown"></Button>
            <div class="grid flex-column lg:flex-row w-full h-full">
                <div class="col-12 lg:col flex align-items-center flex-wrap" style="column-gap: 5px; row-gap:5px;">
                    <Chip v-for="filter of selectedFilters" :key="filter" :label="filter" removable class="mr-3 h-auto px-4 mt-3 lg:mt-0" removeIcon="pi pi-times" :style="{'border-radius':'50px'}"
                        @click="selectedFilters.splice(selectedFilters.indexOf(filter.toString()), 1) && selectedCategory.splice(selectedCategory.indexOf(filter), 1) && selectedSizes2.splice(selectedSizes2.indexOf(filter), 1)"></Chip>
                    <a v-ripple v-if="selectedFilters.length > 0 || selectedColors2 !== 0" tabindex="0" class="text-900 cursor-pointer text-center px-3 py-2 mt-3 lg:mt-0 border-1 border-200 lg:border-none inline-block hover:bg-primary hover:border-primary transition-duration-150 p-ripple" style="border-radius:50px; max-width: 7rem;" @click="selectedCategory = []; selectedFilters = []; selectedColors2 = 0; selectedSizes2 = [];">Clear All</a>
                </div>
            </div>
        </div>
        <div class="filter-container col-12 overflow-hidden transition-all transition-duration-400 transition-ease-in-out">
            <div class="grid grid-nogutter flex-column lg:flex-row">
                <div class="flex-auto lg:flex-1 col mt-0 lg:mt-2 mr-0 lg:mr-4 p-4 flex-column border-1 surface-border border-round">
                    <a tabindex="0" class="cursor-pointer text-900 font-medium mb-3 flex justify-content-between w-full hover:text-primary transition-duration-150">Kategori ({{selectedCategory.length}})</a>
                    <div class="flex flex-column">
                        <div class="mb-3">
                            <span class="p-input-icon-right w-full">
                                <i class="pi pi-search"></i>
                                <InputText placeholder="Search" class="w-full" />
                            </span>
                        </div>
                        <div class="flex w-full justify-content-between">
                            <div class="field-checkbox">
                                <Checkbox value="Alfred" id="alfred2" v-model="selectedFilters" @change="selectedCategory.indexOf('Alfred') === -1 ? selectedCategory.push('Alfred') : selectedCategory.splice(selectedCategory.indexOf('Alfred'), 1)"></Checkbox>
                                <label for="alfred2" class="text-900">Alfred</label>
                            </div>
                            <Badge value="42" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                        </div>
                        <div class="flex w-full justify-content-between">
                            <div class="field-checkbox">
                                <Checkbox value="Hyper" id="hyper2" v-model="selectedFilters" @change="selectedCategory.indexOf('Hyper') === -1 ? selectedCategory.push('Hyper') : selectedCategory.splice(selectedCategory.indexOf('Hyper'), 1)"></Checkbox>
                                <label for="hyper2" class="text-900">Hyper</label>
                            </div>
                            <Badge value="18" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                        </div>
                        <div class="flex w-full justify-content-between">
                            <div class="field-checkbox">
                                <Checkbox value="Bastion" id="bastion2" v-model="selectedFilters" @change="selectedCategory.indexOf('Bastion') === -1 ? selectedCategory.push('Bastion') : selectedCategory.splice(selectedCategory.indexOf('Bastion'), 1)"></Checkbox>
                                <label for="bastion2" class="text-900">Bastion</label>
                            </div>
                            <Badge value="7" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                        </div>
                        <div class="flex w-full justify-content-between">
                            <div class="field-checkbox">
                                <Checkbox value="Peak" id="peak2" v-model="selectedFilters" @change="selectedCategory.indexOf('Peak') === -1 ? selectedCategory.push('Peak') : selectedCategory.splice(selectedCategory.indexOf('Peak'), 1)"></Checkbox>
                                <label for="peak2" class="text-900">Peak</label>
                            </div>
                            <Badge value="36" class="mr-2 bg-gray-200 text-gray-900 p-0 border-circle"></Badge>
                        </div>
                        <a tabindex="0" class="cursor-pointer text-primary font-medium mb-3">Show all...</a>
                    </div>
                </div>
                <div class="flex-auto lg:flex-1 col mt-4 lg:mt-2 lg:mr-4 p-4 flex-column border-1 surface-border border-round">
                    <a tabindex="0" class="cursor-pointer text-900 font-medium mb-3 flex justify-content-between w-full hover:text-primary transition-duration-150">Color ({{selectedColors2}})</a>
                    <div class="grid mb-3">
                        <div class="col-4 flex flex-column align-items-center">
                            <div class="w-3rem h-3rem border-circle bg-gray-900 cursor-pointer border-none flex justify-content-center align-items-center" @click="selectedFilters.indexOf('Black') === -1 ? selectedFilters.push('Black') & selectedColors2++ : selectedFilters.splice(selectedFilters.indexOf('Black'), 1) && selectedColors2 --">
                                <i class="pi pi-check text-2xl text-white" v-if="selectedFilters.indexOf('Black') !== -1"></i>
                            </div>
                            <p class="text-900 text-sm text-center mt-1">Black</p>
                        </div>
                        <div class="col-4 flex flex-column align-items-center">
                            <div class="w-3rem h-3rem border-circle bg-orange-500 cursor-pointer border-none flex justify-content-center align-items-center" @click="selectedFilters.indexOf('Orange') === -1 ? selectedFilters.push('Orange') & selectedColors2++ : selectedFilters.splice(selectedFilters.indexOf('Orange'), 1) && selectedColors2 --">
                                <i class="pi pi-check text-2xl text-white" v-if="selectedFilters.indexOf('Orange') !== -1"></i>
                            </div>
                            <p class="text-900 text-sm text-center mt-1">Orange</p>
                        </div>
                        <div class="col-4 flex flex-column align-items-center">
                            <div class="w-3rem h-3rem border-circle bg-indigo-500 cursor-pointer border-none flex justify-content-center align-items-center" @click="selectedFilters.indexOf('Indigo') === -1 ? selectedFilters.push('Indigo') & selectedColors2++ : selectedFilters.splice(selectedFilters.indexOf('Indigo'), 1) && selectedColors2 --">
                                <i class="pi pi-check text-2xl text-white" v-if="selectedFilters.indexOf('Indigo') !== -1"></i>
                            </div>
                            <p class="text-900 text-sm text-center mt-1">Indigo</p>
                        </div>
                        <div class="col-4 flex flex-column align-items-center">
                            <div class="w-3rem h-3rem border-circle bg-gray-500 cursor-pointer border-none flex justify-content-center align-items-center" @click="selectedFilters.indexOf('Gray') === -1 ? selectedFilters.push('Gray') & selectedColors2++ : selectedFilters.splice(selectedFilters.indexOf('Gray'), 1) && selectedColors2 --">
                                <i class="pi pi-check text-2xl text-white" v-if="selectedFilters.indexOf('Gray') !== -1"></i>
                            </div>
                            <p class="text-900 text-sm text-center mt-1">Gray</p>
                        </div>
                        <div class="col-4 flex flex-column align-items-center">
                            <div class="w-3rem h-3rem border-circle bg-cyan-500 cursor-pointer border-none flex justify-content-center align-items-center" @click="selectedFilters.indexOf('Cyan') === -1 ? selectedFilters.push('Cyan') & selectedColors2++ : selectedFilters.splice(selectedFilters.indexOf('Cyan'), 1) && selectedColors2 --">
                                <i class="pi pi-check text-2xl text-white" v-if="selectedFilters.indexOf('Cyan') !== -1"></i>
                            </div>
                            <p class="text-900 text-sm text-center mt-1">Cyan</p>
                        </div>
                        <div class="col-4 flex flex-column align-items-center">
                            <div class="w-3rem h-3rem border-circle bg-pink-500 cursor-pointer border-none flex justify-content-center align-items-center" @click="selectedFilters.indexOf('Pink') === -1 ? selectedFilters.push('Pink') & selectedColors2++ : selectedFilters.splice(selectedFilters.indexOf('Pink'), 1) && selectedColors2 --">
                                <i class="pi pi-check text-2xl text-white" v-if="selectedFilters.indexOf('Pink') !== -1"></i>
                            </div>
                            <p class="text-900 text-sm text-center mt-1">Pink</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div
        class="w-full border-2 border-dashed border-round surface-border surface-section mt-5 px-1"
      >
        <DataView :value="paginatedProducts" :layout="layout" >
          <template #header >
            <div class="flex justify-content-between w-full align-items-center -px-8">
              <Paginator
                :rows="rows"
                :totalRecords="totalRecords"
                @page="onPageChange"
                :template="{
                  '640px': 'PrevPageLink CurrentPageReport NextPageLink',
                  '960px':
                    'FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink',
                  '1300px':
                    'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink',
                  default:
                    'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown',
                }"
                class="-mx-3"
              />
              <DataViewLayoutOptions v-model="layout"  />
            </div>
          </template>

          <template #list="slotProps">
            <div class="grid grid-nogutter">
              <div
                v-for="(item, index) in slotProps.items"
                :key="index"
                class="col-12"
              >
                <div
                  class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3"
                  :class="{ 'border-top-1 surface-border': index !== 0 }"
                >
                  <div class="md:w-10rem relative">
                    <img
                      class="border-round w-full"
                      :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`"
                      :alt="item.name"
                      style="max-width: 400px"
                    />
                    <Tag
                      :value="item.inventoryStatus"
                      :severity="getSeverity(item)"
                      class="absolute"
                      style="left: 4px; top: 4px"
                    ></Tag>
                  </div>
                  <div
                    class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4"
                  >
                    <div
                      class="flex flex-row md:flex-column justify-content-between align-items-start gap-2"
                    >
                      <div>
                        <span class="font-medium text-secondary text-sm">{{
                          item.category
                        }}</span>
                        <div class="text-lg font-medium text-900 mt-2">
                          {{ item.name }}
                        </div>
                      </div>
                      <div class="surface-100 p-1" style="border-radius: 30px">
                        <div
                          class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2"
                          style="
                            border-radius: 30px;
                            box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04),
                              0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                          "
                        >
                          <span class="text-900 font-medium text-sm">{{
                            item.rating
                          }}</span>
                          <i class="pi pi-star-fill text-yellow-500"></i>
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-column md:align-items-end gap-5">
                      <span class="text-xl font-semibold text-900"
                        >${{ item.price }}</span
                      >
                      <div class="flex flex-row-reverse md:flex-row gap-2">
                        <Button icon="pi pi-heart" outlined></Button>
                        <Button
                          icon="pi pi-shopping-cart"
                          label="Sewa Sekarang"
                          :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                          class="flex-auto md:flex-initial white-space-nowrap"
                        ></Button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>

          <template #grid="slotProps">
            <div class="grid grid-nogutter">
              <div
                v-for="(item, index) in slotProps.items"
                :key="index"
                class="col-12 sm:col-6 md:col-4 p-2"
              >
                <div
                  class="p-4 border-1 surface-border surface-card border-round flex flex-column"
                >
                  <div
                    class="surface-50 flex justify-content-center border-round p-3"
                  >
                    <div class="relative mx-auto">
                      <img
                        class="border-round w-full"
                        :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`"
                        :alt="item.name"
                        style="max-width: 300px"
                      />
                      <Tag
                        :value="item.inventoryStatus"
                        :severity="getSeverity(item)"
                        class="absolute"
                        style="left: 4px; top: 4px"
                      ></Tag>
                    </div>
                  </div>
                  <div class="pt-4">
                    <div
                      class="flex flex-row justify-content-between align-items-start gap-2"
                    >
                      <div>
                        <span class="font-medium text-secondary text-sm">{{
                          item.category
                        }}</span>
                        <div class="text-lg font-medium text-900 mt-1">
                          {{ item.name }}
                        </div>
                      </div>
                      <div class="surface-100 p-1" style="border-radius: 30px">
                        <div
                          class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2"
                          style="
                            border-radius: 30px;
                            box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04),
                              0px 1px 2px 0px rgba(0, 0, 0, 0.06);
                          "
                        >
                          <span class="text-900 font-medium text-sm">{{
                            item.rating
                          }}</span>
                          <i class="pi pi-star-fill text-yellow-500"></i>
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-column gap-4 mt-4">
                      <span class="text-2xl font-semibold text-900"
                        >${{ item.price }}</span
                      >
                      <div class="flex gap-2">
                        <Button
                          icon="pi pi-shopping-cart"
                          label="Sewa Sekarang"
                          :disabled="item.inventoryStatus === 'OUTOFSTOCK'"
                          class="flex-auto white-space-nowrap"
                        ></Button>
                        <Button icon="pi pi-heart" outlined></Button>
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
import { onMounted, ref } from "vue";

const products = ref([]);
const paginatedProducts = ref([]);
const layout = ref("grid");
const rows = ref(10);
const totalRecords = ref(0);
const currentPage = ref(0);

const selectedCategory = ref(0);
const selectedFilters = ref([]);
const selectedSizes2 = ref([]);
const rangeValues = ref([10, 10000]);

onMounted(() => {
  products.value = [
    {
      id: "1",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "2",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "3",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "4",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "5",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "6",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "7",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "8",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "9",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
    {
      id: "10",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },

    {
      id: "11",
      name: "Bamboo Watch",
      image: "bamboo-watch.jpg",
      price: 65,
      category: "Accessories",
      quantity: 24,
      inventoryStatus: "INSTOCK",
      rating: 5,
    },
  ];
  totalRecords.value = products.value.length;
  updatePaginatedProducts();
});

// Fungsi untuk mengupdate produk yang ditampilkan berdasarkan halaman
const updatePaginatedProducts = () => {
  const start = currentPage.value * rows.value;
  const end = start + rows.value;
  paginatedProducts.value = products.value.slice(start, end);
};

// Fungsi untuk menangani perubahan halaman
const onPageChange = (event) => {
  currentPage.value = event.page;
  updatePaginatedProducts();
};

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
