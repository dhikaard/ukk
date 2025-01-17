<template>
    <div class="surface-section px-4 pt-8 md:px-6 lg:px-8">
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
            <div class="flex flex-column text-center md:text-left">
                <span class="text-900 text-3xl font-medium mb-2">Admin & Peran</span>
                <span class="text-600 text-xl">Kamu dapat dengan mudah mengelola tim di halaman ini!😎</span>
            </div>
            <span class="p-input-icon-right mt-5 mb-2 md:mt-0 md:mb-0 w-full lg:w-25rem">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="value1" placeholder="Nama Lengkap" class="w-full" />
                </IconField>
            </span>
        </div>
        <section class="flex-wrap gap-3 justify-content-between border-bottom-1 surface-border">
            <Divider class="w-full"></Divider>
            <div class="flex-shrink-0 w-15rem">
                <h3 class="mb-4 mt-0 text-900 font-medium text-xl border-primary border-bottom-2 inline-block"
                    style="padding-bottom: 0.5rem;">
                    Admin
                </h3>
                <p class="mb-4 mt-0 text-700 font-normal text-base">Kelola admin kamu di sini!</p>
                <Button icon="pi pi-users" label="Undang Anggota" class="w-auto" @click="showDialog = true"></Button>
            </div>
            <div class="overflow-x-scroll mt-3">
                <DataTable :value="members" :tabStyle="{ 'min-width': '60rem' }" rowHover>
                    <Column style="min-width:25rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Lengkap</span>
                        </template>
                        <template #body="{ data }">
                            <div class="flex align-items-center gap-3">
                                <Avatar :name="data.name" alt="User Avatar" style="height: 2.5rem;" />
                                <div>
                                    <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.name }}</p>
                                    <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.username }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Tanggal Bergabung</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.date }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:13rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Terakhir Aktif</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.active }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:8rem">
                        <template #body>
                            <Button type="button" icon="pi pi-ellipsis-v" class="p-button-text p-button-secondary"
                                @click="$refs.menu.toggle($event)"></Button>
                            <Menu ref="menu" appendTo="body" popup :model="items"></Menu>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </section>
        <section class="flex-wrap gap-3 py-6 justify-content-between surface-border">
            <div class="flex-shrink-0 w-15rem">
                <h3 class="mb-4 mt-0 text-900 font-medium text-xl border-primary border-bottom-2 inline-block"
                    style="padding-bottom: 0.5rem;">
                    Peran
                </h3>
                <p class="mb-4 mt-0 text-700 font-normal text-base">Tambah / ubah peran di sini!</p>
                <Button icon="pi pi-plus" label="Tambah Peran" class="w-auto"></Button>
            </div>
            <div class="overflow-x-scroll mt-3">
                <DataTable :value="roles" rowHover>
                    <Column style="min-width:25rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Peran</span>
                        </template>
                        <template #body="{ data }">
                            <div class="flex align-items-center">
                                <span class="border-circle mr-2" :class="data.color"
                                    style="width: 7px; height: 7px;"></span>
                                <p class="mt-0 mb-0 font-medium text-lg text-color-primary">{{ data.alias }}</p>
                            </div>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Terakhir Diubah</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-0 font-normal text-base text-color-secondary">{{ data.date }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:13rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Pengguna</span>
                        </template>
                        <template #body="{ data }">
                            <p class="mt-0 mb-0 font-normal text-base text-color-secondary">{{ data.users }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:8rem">
                        <template #body>
                            <Button type="button" icon="pi pi-ellipsis-v" class="p-button-text p-button-secondary"
                                @click="$refs.menu.toggle($event)"></Button>
                            <Menu ref="menu" appendTo="body" popup :model="items"></Menu>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </section>
        <DialogInviteAdmin :visible="showDialog" @update:visible="showDialog = $event" />
    </div>
</template>

<script setup>
import { defineAsyncComponent, ref } from 'vue';
import Avatar from '../components/Avatar.vue';

const DialogInviteAdmin = defineAsyncComponent(() => import('../components/DialogInviteAdmin.vue'));
const showDialog = ref(false);

// Data palsu untuk tabel members
const members = ref([
    {
        name: 'Alexander Agung',
        username: '@alexander',
        date: '2023-01-15',
        active: '2023-12-20',
    },
    {
        name: 'Maria Clara',
        username: '@maria',
        date: '2022-10-10',
        active: '2023-12-18',
    },
    {
        name: 'John Doe',
        username: '@johndoe',
        date: '2021-07-23',
        active: '2023-12-15',
    },
    {
        name: 'Jane Smith',
        username: '@janesmith',
        date: '2020-05-30',
        active: '2023-12-12',
    },
]);

// Data palsu untuk tabel roles
const roles = ref([
    {
        alias: 'Administrator',
        color: 'bg-blue-500',
        date: '2023-11-10',
        users: 5,
    },
    {
        alias: 'Editor',
        color: 'bg-green-500',
        date: '2023-10-05',
        users: 8,
    },
    {
        alias: 'Viewer',
        color: 'bg-orange-500',
        date: '2023-09-12',
        users: 20,
    },
    {
        alias: 'Moderator',
        color: 'bg-purple-500',
        date: '2023-12-01',
        users: 3,
    },
]);
</script>
