<template>
    <Dialog v-model:visible="visible5" :modal="true" :closable="false" :showHeader="false"
        :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        :style="{ width: '40vw', maxWidth: '90vw', minWidth: '320px', height: '20rem' }">
        <section class="flex flex-column w-full mt-4">
            <div class="flex w-full justify-content-between mb-4">
                <span class="w-4rem h-4rem border-circle flex justify-content-center align-items-center bg-blue-100">
                    <i class="pi pi-users text-blue-700 text-4xl"></i>
                </span>
                <Button type="button" icon="pi pi-times"
                    class="p-button-rounded p-button-secondary p-button-text align-self-start"
                    @click="$emit('update:visible', false)">
                </Button>
            </div>
            <p class="font-semibold text-xl mt-0 mb-2 text-900">Undang Anggota Tim</p>
            <p class="font-normal text-base mt-0 mb-3 text-600">Tambah anggota tim sebagai admin!</p>
            <Dropdown filter :options="members" v-model="selectedMember" optionLabel="name" appendTo="body"
                styleClass="w-full border-round-lg">
                <template #value>
                    <div class="flex align-items-center">
                        <!-- Check if selectedMember is not null -->
                        <p v-if="selectedMember" class="mt-0 mb-0 font-normal text-base text-600">{{ selectedMember.name
                            }}</p>
                        <p v-else class="mt-0 mb-0 font-normal text-base text-600">Select a member</p>
                    </div>
                </template>
                <template #option="{ option }">
                    <div class="flex align-items-center">
                        <p class="mt-0 mb-0 font-normal text-base text-600">{{ option.name }}</p>
                    </div>
                </template>
            </Dropdown>
        </section>
        <template #footer>
            <div class="pt-2 flex gap-2 w-full">
                <Button icon="pi pi-times" @click="$emit('update:visible', false)" label="Batal"
                    class="p-button-text w-full p-button-secondary"></Button>
                <Button icon="pi pi-user-plus" @click="$emit('update:visible', false)" label="Undang" class="w-full p-button-primary"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';

const members = ref([
    { name: 'John Doe', avatar: 'avatar1.png' },
    { name: 'Jane Smith', avatar: 'avatar2.png' },
]); // Example data
const selectedMember = ref(null);
</script>
