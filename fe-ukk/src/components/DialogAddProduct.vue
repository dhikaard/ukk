<template>
    <Dialog v-model="context.visible" :modal="true" :closable="true" :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        :style="{ width: '55vw', maxWidth: '90vw', minWidth: '380px' }">
        <template #header>
            <div class="flex w-full justify-content-between align-items-center">
                <div class="flex align-items-center">
                    <span
                        class="w-3rem h-3rem border-circle flex justify-content-center align-items-center bg-blue-100 mr-3">
                        <i class="pi pi-shopping-bag text-blue-700 text-2xl"></i>
                    </span>
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
                    <Dropdown v-model="context.brandId" editable :options="context.brandOptions" optionLabel="brandName"
                        optionValue="id" placeholder="Canon" emptyMessage="Tidak ada opsi yang tersedia" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="state" class="font-medium text-900">Kategori</label>
                    <Dropdown v-model="context.ctgrId" editable :options="context.ctgrOptions" optionLabel="ctgrName"
                        optionValue="id" placeholder="Kamera" emptyMessage="Tidak ada opsi yang tersedia" />

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
                <div class="field mb-4 col-12 md:col-6">
                    <label for="state" class="font-medium text-900">Harga / hari</label>
                    <InputNumber v-model="context.price" inputId="currency-id" mode="currency" :minFractionDigits="0"
                        currency="IDR" locale="id-ID" :allowEmpty="false" :min="0" inputClass="w-full">
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="state" class="font-medium text-900">Denda (%)</label>
                    <InputNumber v-model="context.fineBill" inputId="percent" suffix="%" :allowEmpty="false" :min="0"
                        :max="100" inputClass="w-full">
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12">
                    <label for="bio" class="font-medium text-900">Keterangan</label>
                    <Editor v-model="context.desc" editorStyle="height: 200px">
                        <template v-slot:toolbar>
                            <span class="ql-formats">
                                <button v-tooltip.bottom="'Bold'" class="ql-bold"></button>
                                <button v-tooltip.bottom="'Italic'" class="ql-italic"></button>
                                <button v-tooltip.bottom="'Underline'" class="ql-underline"></button>
                            </span>
                        </template>
                    </Editor>
                </div>
                <div class="field mb-4 col-12">
                    <label for="thumbnail" class="font-medium text-900">Foto Barang</label>
                    <div class="align-items-center">
                        <FileUpload :showUploadButton="false" chooseLabel="Pilih" cancelLabel="Batal" name="demo[]"
                            @select="onFileSelect" :multiple="false" accept="image/*" :maxFileSize="1000000">
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
                <Button :loading="context.loading['addProduct']" icon="pi pi-check" @click="handleAddProduct"
                    label="Selesai" class="w-full p-button-primary"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useManageProductStore } from '@/stores/manage-product.store';

const context = useManageProductStore();
const selectedFiles = ref([]);
const emit = defineEmits();

onMounted(async () => {
    await context.getBrand();
    await context.getCtgr();
})

const onFileSelect = (event) => {
    selectedFiles.value = event.files;
};

const handleAddProduct = async () => {
    const formData = new FormData();
    formData.append('productName', context.productName);

    // Logika untuk brandId dan brandName
    const selectedBrand = context.brandOptions.find((brand) => brand.id === context.brandId);
    if (selectedBrand) {
        // Jika brandId valid dari dropdown
        formData.append('brandId', selectedBrand.id);
        formData.append('brandName', selectedBrand.brandName);
    } else {
        // Jika input manual (brandId tidak ditemukan)
        formData.append('brandId', -99);
        formData.append('brandName', context.brandId); // Brand input manual masuk ke v-model brandId
    }

    // Logika untuk ctgrId dan ctgrName
    const selectedCategory = context.ctgrOptions.find((ctgr) => ctgr.id === context.ctgrId);
    if (selectedCategory) {
        // Jika ctgrId valid dari dropdown
        formData.append('ctgrId', selectedCategory.id);
        formData.append('ctgrName', selectedCategory.ctgrName);
    } else {
        // Jika input manual (ctgrId tidak ditemukan)
        formData.append('ctgrId', -99);
        formData.append('ctgrName', context.ctgrId); // Category input manual masuk ke v-model ctgrId
    }

    formData.append('stock', context.stock);
    formData.append('price', context.price);
    formData.append('fineBill', context.fineBill);
    formData.append('desc', context.desc);

    selectedFiles.value.forEach((file) => {
        formData.append('urlImg', file);
    });

    console.log('FormData:', {
        productName: formData.get('productName'),
        brandId: formData.get('brandId'),
        brandName: formData.get('brandName'),
        ctgrId: formData.get('ctgrId'),
        ctgrName: formData.get('ctgrName'),
    });

    await context.addProduct(formData);

    if (!context.loading['addProduct']) {
        emit('update:visible', false);  // Use `emit` here instead of `$emit`
    }
};


</script>
