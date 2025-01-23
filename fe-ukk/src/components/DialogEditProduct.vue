<template>
    <Dialog :lazy="true" v-model="context.visible" :modal="true" :closable="true"
        :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        :style="{ width: '55vw', maxWidth: '90vw', minWidth: '380px' }">
        <template #header>
            <div class="flex w-full justify-content-between align-items-center">
                <div class="flex align-items-center">
                    <span
                        class="w-3rem h-3rem border-circle flex justify-content-center align-items-center bg-blue-100 mr-3">
                        <i class="pi pi-shopping-bag text-blue-700 text-2xl"></i>
                    </span>
                    <div class="flex flex-column">
                        <p class="font-semibold text-xl mt-0 mb-2 text-900">Edit Barang</p>
                        <p class="font-normal text-base mt-0 mb-3 text-600">Ubah data barang untuk disewakan!
                        </p>
                    </div>
                </div>
            </div>
        </template>
        <section>
            <div class="grid formgrid p-fluid">
                <div class="field mb-4 col-12 md:col-6">
                    <label for="city" class="font-medium text-900">Nama Barang</label>
                    <InputText v-model="context.productName" id="city" type="text"
                        placeholder="EOS 200D II (EF-S 18-55mm f/4-5.6 IS STM)" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="city" class="font-medium text-900">Merk</label>
                    <Dropdown :loading="context.loading['getBrand']" v-model="context.brandId" editable
                        :options="context.brandOptions" optionLabel="brandName" optionValue="id" placeholder="Canon"
                        emptyMessage="Tidak ada opsi yang tersedia" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="state" class="font-medium text-900">Kategori</label>
                    <Dropdown :loading="context.loading['getCtgr']" v-model="context.ctgrId" editable
                        :options="context.ctgrOptions" optionLabel="ctgrName" optionValue="id" placeholder="Kamera"
                        emptyMessage="Tidak ada opsi yang tersedia" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="city" class="font-medium text-900">Stok</label>
                    <InputNumber v-model="context.stock" showButtons buttonLayout="horizontal" :min="1" placeholder="1">
                        <template #incrementbuttonicon>
                            <span class="pi pi-plus" />
                        </template>
                        <template #decrementbuttonicon>
                            <span class="pi pi-minus" />
                        </template>
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12 md:col-3">
                    <label for="state" class="font-medium text-900">Harga / hari</label>
                    <InputNumber v-model="context.price" inputId="currency-id" mode="currency" :minFractionDigits="0"
                        currency="IDR" locale="id-ID" :allowEmpty="false" :min="0" inputClass="w-full">
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12 md:col-3">
                    <label for="state" class="font-medium text-900">Denda (%)</label>
                    <InputNumber v-model="context.fineBill" inputId="percent" suffix="%" :allowEmpty="false" :min="0"
                        :max="100" inputClass="w-full">
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="status" class="font-medium text-900">Status</label>
                    <SelectButton v-model="context.active" :options="context.statusOptions" optionLabel="label" optionValue="value" aria-labelledby="basic" />
                </div>
                <div class="field mb-4 col-12">
                    <label for="desc" class="font-medium text-900">Keterangan</label>
                    <Textarea id="desc" v-model="context.desc" :rows="6" :autoResize="true"></Textarea>
                </div>

                <div class="field mb-4 col-12">
                    <label for="thumbnail" class="font-medium text-900">Foto Barang</label>
                    <div class="align-items-center">
                        <FileUpload invalidFileLimitMessage="Melewati batas unggahan maksimum file"
                            invalidFileSizeMessage="Ukuran file terlalu besar" :showUploadButton="false"
                            chooseLabel="Pilih" cancelLabel="Batal" name="demo[]" @select="onFileSelect"
                            :multiple="false" accept="image/*" :maxFileSize="2000000" previewWidth="100" fileLimit="1">
                            <template #empty>
                                <p>Seret dan taruh file ke sini untuk mengunggah.</p>
                            </template>
                        </FileUpload>
                    </div>
                </div>
            </div>
        </section>
        <template #footer>
            <div class="pt-2 flex border-top-1 surface-border gap-2 w-full">
                <Button icon="pi pi-times" @click="$emit('update:visible', false)" label="Batal"
                    class="p-button-text w-full p-button-secondary"></Button>
                <Button :loading="context.loading['editProduct']" icon="pi pi-check" @click="handleEditProduct"
                    label="Selesai" class="w-full p-button-primary"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useEditProductStore } from '@/stores/edit-product.store';

const context = useEditProductStore();
const selectedFiles = ref([]);
const emit = defineEmits();

onMounted(async () => {
    await context.getBrand()
    await context.getCtgr()
    context.productId = context.selectedProduct.id;
    context.productName = context.selectedProduct.productName;
    context.brandId = context.selectedProduct.brandId;
    context.ctgrId = context.selectedProduct.ctgrId;
    context.stock = context.selectedProduct.stock;
    context.price = context.selectedProduct.price;
    context.fineBill = context.selectedProduct.fineBill;
    context.desc = context.selectedProduct.desc;
    context.active = context.selectedProduct.active;
    context.urlImg = context.selectedProduct.urlImg;
});

const onFileSelect = (event) => {
    selectedFiles.value = event.files;
};

const handleEditProduct = async () => {
    const formData = new FormData();
    formData.append('productId', context.productId);
    formData.append('productName', context.productName);

    const selectedBrand = context.brandOptions.find((brand) => brand.id === context.brandId);
    if (selectedBrand) {
        formData.append('brandId', selectedBrand.id);
        formData.append('brandName', selectedBrand.brandName);
    } else {
        formData.append('brandId', -99);
        formData.append('brandName', context.brandId);
    }

    const selectedCategory = context.ctgrOptions.find((ctgr) => ctgr.id === context.ctgrId);
    if (selectedCategory) {
        formData.append('ctgrId', selectedCategory.id);
        formData.append('ctgrName', selectedCategory.ctgrName);
    } else {
        formData.append('ctgrId', -99);
        formData.append('ctgrName', context.ctgrId);
    }

    formData.append('stock', context.stock);
    formData.append('price', context.price);
    formData.append('fineBill', context.fineBill);
    formData.append('desc', context.desc || '');
    formData.append('active', context.active);

    selectedFiles.value.forEach((file) => {
        formData.append('urlImg', file);
    });

    await context.editProduct(formData);

    if (!context.loading['editProduct']) {
        emit('update:visible', false);
    }
};
</script>
