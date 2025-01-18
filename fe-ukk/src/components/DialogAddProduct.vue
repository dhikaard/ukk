<template>
    <Dialog v-model:visible="visible" :modal="true" :closable="true" :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
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
                    <InputText id="city" type="text" placeholder="EOS 200D II (EF-S 18-55mm f/4-5.6 IS STM)" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="city" class="font-medium text-900">Merk</label>
                    <InputText id="city" type="text" placeholder="Canon" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="state" class="font-medium text-900">Kategori</label>
                    <InputText id="state" type="text" placeholder="Kamera" />
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="city" class="font-medium text-900">Stok</label>
                    <InputNumber v-model="value" showButtons buttonLayout="horizontal" :min="1" placeholder="1">
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
                    <InputNumber inputId="currency-id" mode="currency" :minFractionDigits="0" currency="IDR"
                        locale="id-ID" :allowEmpty="false" :min="0" inputClass="w-full mr-3">
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12 md:col-6">
                    <label for="state" class="font-medium text-900">Denda (%)</label>
                    <InputNumber inputId="percent" suffix="%" :allowEmpty="false" :min="0" :max="100"
                        inputClass="w-full mr-3">
                    </InputNumber>
                </div>
                <div class="field mb-4 col-12">
                    <label for="bio" class="font-medium text-900">Deskripsi</label>
                    <Editor v-model="value" editorStyle="height: 200px">
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
                    <label for="thumbnail" class="font-medium text-900">Foto</label>
                    <div class="align-items-center">
                        <FileUpload uploadLabel="Unggah" chooseLabel="Pilih" cancelLabel="Batal" name="demo[]"
                            url="/api/upload" @upload="onAdvancedUpload($event)" :multiple="true" accept="image/*"
                            :maxFileSize="1000000">
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
                <Button icon="pi pi-check" @click="$emit('update:visible', false)" label="Selesai"
                    class="w-full p-button-primary"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';

const visible = ref(false);
const value = ref(1);
</script>
