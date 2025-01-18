<template>
    <Dialog closeOnEscape v-model:visible="visible5" :modal="true" :closable="true" :showHeader="true"
        :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        :style="{ width: '40vw', maxWidth: '90vw', minWidth: '380px', height: '21rem' }">
        <template #header>
            <div class="flex w-full justify-content-between align-items-center">
                <div class="flex align-items-center">
                    <span
                        class="w-3rem h-3rem border-circle flex justify-content-center align-items-center bg-blue-100 mr-3">
                        <i class="pi pi-users text-blue-700 text-2xl"></i>
                    </span>
                </div>
            </div>
        </template>
        <section class="flex flex-column w-full">
            <p class="font-semibold text-xl mt-0 mb-2 text-900">Undang Anggota Tim</p>
            <p class="font-normal text-base mt-0 mb-3 text-600">Tambah anggota tim dan tentukan perannya!</p>
            <Dropdown showClear filter :options="members" v-model="selectedMember" optionLabel="name" appendTo="body"
                styleClass="w-full" class="h-4rem align-items-center">
                <template #value>
                    <div class="flex align-items-center">
                        <Avatar v-if="selectedMember" :name="selectedMember.name" alt="User Avatar" class="mr-2"
                            style="height: 2.5rem;" />
                        <div>
                            <p v-if="selectedMember" class="mt-0 mb-0 font-semibold text-base">{{ selectedMember.name }}
                            </p>
                            <p v-if="selectedMember" class="mt-0 mb-0 font-normal text-sm text-600">{{
                                selectedMember.email }}</p>
                            <p v-else class="mt-0 mb-0 font-normal text-base text-600">Pilih anggota</p>
                        </div>
                    </div>
                </template>
                <template #option="{ option }">
                    <div class="flex align-items-center">
                        <Avatar :name="option.name" alt="User Avatar" class="mr-2" style="height: 2.5rem;" />
                        <div>
                            <p class="mt-0 mb-0 font-semibold text-base">{{ option.name }}</p>
                            <p class="mt-0 mb-0 font-normal text-sm text-600">{{ option.email }}</p>
                        </div>
                    </div>
                </template>
            </Dropdown>
        </section>
        <template #footer>
            <div class="pt-2 flex border-top-1 surface-border gap-2 w-full">
                <Button icon="pi pi-times" @click="$emit('update:visible', false)" label="Batal"
                    class="p-button-text w-full p-button-secondary"></Button>
                <Button icon="pi pi-user-plus" @click="$emit('update:visible', false)" label="Undang"
                    class="w-full p-button-primary"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import Avatar from './Avatar.vue';

const members = ref([
    { name: 'John Doe', email: 'john.doe@example.com' },
    { name: 'Jane Smith', email: 'jane.smith@example.com' },
]); // Example data
const selectedMember = ref(null);
</script>
