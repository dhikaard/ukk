<template>
    <Dialog closeOnEscape v-model:visible="visible5" :modal="true" :closable="true" :showHeader="true"
        :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        :style="{ width: '40vw', maxWidth: '90vw', minWidth: '380px', height: '35rem' }">
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
            <p class="font-semibold text-xl mt-0 mb-2 text-900">Edit Anggota Tim</p>
            <p class="font-normal text-base mt-0 mb-3 text-600">Ubah perannya dalam tim!</p>
            <Divider class="w-full"></Divider>
            <label class="font-semibold">Anggota</label>
            <Dropdown disabled :emptyMessage="'Data tidak tersedia'"
                :emptyFilterMessage="'Tidak ada hasil yang ditemukan'" :options="context.userOptions"
                v-model="context.selectedMember" optionLabel="name" appendTo="body" styleClass="w-full"
                class="h-4rem align-items-center w-full mt-3">
                <template #value>
                    <div class="flex align-items-center">
                        <Avatar v-if="context.selectedMember" :name="context.selectedMember.name" alt="User Avatar"
                            class="mr-2" style="height: 2.5rem;" :key="context.selectedMember.name" />
                        <div>
                            <p v-if="context.selectedMember" class="mt-0 mb-0 font-semibold text-base">{{
                                context.selectedMember.name }}</p>
                            <p v-if="context.selectedMember" class="mt-0 mb-0 font-normal text-sm text-600">{{
                                context.selectedMember.email }}</p>
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
        <div v-if="context.dataTableRoles" class="mt-4">
            <label class="font-semibold">Peran</label>
            <div v-for="role in context.dataTableRoles" :key="role.id"
                class="surface-card border-2 p-3 mt-3 flex align-items-center cursor-pointer border-round mb-2"
                :class="{ 'border-primary': context.selectedRole === role.id, 'surface-border': context.selectedRole !== role.id }"
                @click="context.selectedRole = role.id">
                <RadioButton name="role" :value="role.id" v-model="context.selectedRole" class="mr-3"></RadioButton>
                <div class="flex flex-column">
                    <div class="font-medium text-base mb-1">{{ role.roleName }}</div>
                    <span class="text-sm text-600">{{ role.permissionName }}</span>
                </div>
            </div>
        </div>
        <template #footer>
            <div class="pt-2 flex border-top-1 surface-border gap-2 w-full">
                <Button icon="pi pi-times" @click="$emit('update:visible', false)" label="Batal"
                    class="p-button-text w-full p-button-secondary"></Button>
                <Button :loading="context.loading['editUser']" :disabled="context.isInviteAdminDisabled" icon="pi pi-check" @click="saveRole" label="Selesai"
                    class="w-full p-button-primary"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { onMounted } from 'vue';
import { useManageAdminStore } from '@/stores/manage-admin.store';
import Avatar from './Avatar.vue';

const emit = defineEmits();
const context = useManageAdminStore();

const saveRole = async () => {
    await context.editUserAdmin(context.selectedMember.id, context.selectedRole)
    if (!context.loading['editUser']) {
        emit('update:visible', false);
    }
};

onMounted(async () => {
    await context.getRolePermission()
    context.id = context.selectedMember.id;
    context.selectedRole = context.selectedMember.roleId;
});

</script>
