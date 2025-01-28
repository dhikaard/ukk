<template>
    <div class="surface-section px-4 pt-8 md:px-6 lg:px-8">
        <div class="flex flex-column md:flex-row justify-content-between align-items-center mb-4">
            <div class="flex flex-column text-center md:text-left">
                <div class="flex align-items-center justify-content-center md:justify-content-start mb-2">
                    <span
                        class="border-circle w-3rem h-3rem flex align-items-center justify-content-center surface-100 mr-2">
                        <i class="pi pi-users text-900 text-3xl"></i>
                    </span> <span class="text-900 text-3xl font-medium">Anggota Tim</span>
                </div>
                <span class="text-600 text-xl">Kamu dapat dengan mudah mengelola tim di halaman ini!😎</span>
            </div>
            <span class="p-input-icon-right mt-5 mb-2 md:mt-0 md:mb-0 w-full lg:w-25rem">
                <IconField iconPosition="left">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="context.keyword" placeholder="Nama Lengkap / Email" class="w-full"
                        @keyup.enter="context.getManageAdmin" />
                </IconField>
            </span>
        </div>
        <section class="flex-wrap gap-3 justify-content-between border-bottom-1 surface-border">
            <Divider class="w-full"></Divider>
            <div class="flex-shrink-0">
                <h3 class="mb-4 mt-0 text-900 font-medium text-xl border-primary border-bottom-2 inline-block"
                    style="padding-bottom: 0.5rem;">
                    Anggota
                </h3>
                <p class="mb-4 mt-0 text-700 font-normal text-base">Kelola anggota tim Kamu di sini!</p>
                <Button icon="pi pi-users" label="Undang Anggota" class="w-auto"
                    @click="showDialogInvite = true"></Button>
            </div>
            <div class="mt-3">
                <DataTable :value="context.dataTableAdmin" :tabStyle="{ 'min-width': '60rem' }" rowHover>
                    <template #empty>
                        <p class="text-center w-full">Data tidak tersedia</p>
                    </template>
                    <Column style="min-width:25rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Lengkap / Email</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getAdmin']" height="2rem"></Skeleton>
                            <div v-else class="flex align-items-center gap-3">
                                <Avatar :name="data.name" alt="User Avatar" style="height: 2.5rem;" />
                                <div>
                                    <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.name }}</p>
                                    <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.email }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column style="min-width:20rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Alamat / Telepon</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getAdmin']" height="2rem"></Skeleton>
                            <div v-else class="flex align-items-center gap-3">
                                <div>
                                    <p class="mt-0 mb-2 font-medium text-lg text-color-primary">{{ data.address }}</p>
                                    <p class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.phone }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column style="min-width:13rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Peran</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getAdmin']" height="2rem"></Skeleton>
                            <p v-else class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.roleName }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Tanggal Bergabung</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getAdmin']" height="2rem"></Skeleton>
                            <p v-else class="mt-0 mb-2 font-normal text-base text-color-secondary">{{ data.updatedAt }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:8rem">
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getAdmin']" height="2rem"></Skeleton>
                            <Button v-else type="button" icon="pi pi-ellipsis-v" class="p-button-text p-button-secondary"
                                @click="$refs['menu-' + data.id].toggle($event)"></Button>
                            <Menu :ref="'menu-' + data.id" appendTo="body" popup :model="getMenuAdminItems(data)">
                            </Menu>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </section>
        <section class="flex-wrap gap-3 py-6 justify-content-between surface-border">
            <div class="flex-shrink-0">
                <h3 class="mb-4 mt-0 text-900 font-medium text-xl border-primary border-bottom-2 inline-block"
                    style="padding-bottom: 0.5rem;">
                    Peran & Izin
                </h3>
                <p class="mb-4 mt-0 text-700 font-normal text-base">Kelola peran dan izin di sini!</p>
                <Button icon="pi pi-plus" label="Tambah Peran" class="w-auto"
                    @click="showDialogAddRoles = true"></Button>
            </div>
            <div class="mt-3">
                <DataTable :value="context.dataTableRoles" rowHover>
                    <Column style="min-width:20rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Nama Peran</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getRole']" height="2rem"></Skeleton>
                            <p v-else class="mt-0 mb-0 font-medium text-lg text-color-primary">{{ data.roleName }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:15rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Izin</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getRole']" height="2rem"></Skeleton>
                            <p v-else class="mt-0 mb-0 font-normal text-base text-color-secondary">{{
                                data.permissionName }}</p>
                        </template>
                    </Column>
                    <Column style="min-width:8rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Pengguna</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getRole']" height="2rem"></Skeleton>
                            <p v-else class="mt-0 mb-0 font-normal text-right text-base text-color-secondary">{{
                                data.userCount }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:14rem">
                        <template #header>
                            <span class="font-semibold text-sm text-color-secondary">Tanggal Pembaruan</span>
                        </template>
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getRole']" height="2rem"></Skeleton>
                            <p v-else class="mt-0 mb-0 font-normal text-base text-color-secondary">{{ data.updatedAt }}
                            </p>
                        </template>
                    </Column>
                    <Column style="min-width:8rem">
                        <template #body="{ data }">
                            <Skeleton v-if="context.loading['getRole']" height="2rem"></Skeleton>
                            <Button v-else type="button" icon="pi pi-ellipsis-v" class="p-button-text p-button-secondary"
                                @click="$refs['menu-' + data.id].toggle($event)"></Button>
                            <Menu :ref="'menu-' + data.id" appendTo="body" popup :model="getMenuRoleItems(data)"></Menu>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </section>
        <DialogInviteAdmin v-if="showDialogInvite" v-model:visible="showDialogInvite" />
        <DialogAddRoles v-if="showDialogAddRoles" v-model:visible="showDialogAddRoles" />
        <DialogEditAdmin v-if="showDialogEditAdmin" v-model:visible="showDialogEditAdmin" />
        <DialogConfirm v-if="showDialogConfirm" v-model:visible="showDialogConfirm" :acceptLabel="'Keluarkan'"
            :onAccept="confirmDelete" :onReject="cancelDelete" :header="confirmHeader" :message="confirmMessage"
            :acceptLoading="context.loading['removeAdmin']" />

    </div>
</template>

<script setup>
import { defineAsyncComponent, ref, onMounted } from 'vue';
import { useManageAdminStore } from '@/stores/manage-admin.store';
import Avatar from '../components/Avatar.vue';

const context = useManageAdminStore();
const DialogInviteAdmin = defineAsyncComponent(() => import('../components/DialogInviteAdmin.vue'));
const DialogAddRoles = defineAsyncComponent(() => import('../components/DialogAddRoles.vue'));
const showDialogInvite = ref(false);
const showDialogAddRoles = ref(false);
const DialogEditAdmin = defineAsyncComponent(() => import('../components/DialogEditAdmin.vue'));
const showDialogEditAdmin = ref(false);
const DialogConfirm = defineAsyncComponent(() => import('../components/DialogConfirm.vue'));
const showDialogConfirm = ref(false);
const selectedUserId = ref(null);
const confirmHeader = ref('');
const confirmMessage = ref('');

onMounted(async () => {
    await context.getManageAdmin();
    await context.getRolePermission();
})

const confirmDelete = () => {
    context.removeUserAdmin(selectedUserId.value);
    showDialogConfirm.value = false;
};

const cancelDelete = () => {
    showDialogConfirm.value = false;
};

const getMenuAdminItems = (data) => {
    return [
        {
            label: 'Pilihan',
            items: [
                {
                    label: 'Edit',
                    icon: 'pi pi-pencil',
                    command: () => {
                        context.selectedMember = data
                        showDialogEditAdmin.value = true;
                    }
                },
                {
                    label: 'Keluarkan',
                    icon: 'pi pi-sign-out',
                    command: () => {
                        selectedUserId.value = data.id;
                        confirmHeader.value = 'Konfirmasi Keluar';
                        confirmMessage.value = `Apakah Kamu yakin ingin mengeluarkan ${data.name}?`;
                        showDialogConfirm.value = true;
                    }
                }
            ]
        }
    ];
};

const getMenuRoleItems = (data) => {
    return [
        {
            label: 'Pilihan',
            items: [
                {
                    label: 'Edit',
                    icon: 'pi pi-pencil',
                    command: () => {
                        context.selectedProduct = data
                        showDialogEditProduct.value = true;
                    }
                },
                {
                    label: 'Hapus',
                    icon: 'pi pi-trash',
                    command: () => deleteProduct(data.id)
                }
            ]
        }
    ];
};

</script>
