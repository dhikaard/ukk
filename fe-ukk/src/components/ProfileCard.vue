<template>
    <div class="surface-card border-round shadow-2 select-none">
        <ul class="list-none p-2 m-0">
            <li>
                <a v-ripple class="flex p-2 align-items-center cursor-pointer p-ripple"
                    v-styleclass="{ selector: '#pb_profile_submenu', enterClass: 'hidden', enterActiveClass: 'slidedown', leaveToClass: 'hidden', leaveActiveClass: 'slideup' }">
                    <span class="mr-3 inline-flex">
                        <Avatar :name="(local.getUser()?.[0]?.name || 'Tamu')" alt="User Avatar"
                            style="height: 2.5rem;" />
                    </span>
                    <div>
                        <span class="font-medium text-900 mb-2">{{ userName }}</span>
                        <p class="mt-1 mb-0 text-600">{{ userRole }}</p>
                    </div>
                    <i class="pi pi-chevron-down text-700 ml-auto"></i>
                </a>
                <div class="border-top-1 surface-border my-3" style="height:1px"></div>
                <ul id="pb_profile_submenu" class="list-none p-0 m-0 overflow-hidden">
                    <li class="mb-2" v-if="hasAccess('manageAdmin')">
                        <a v-ripple
                            class="flex p-2 align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                            @click="goToAdminRoles">
                            <i class="pi pi-users text-500 mr-3 text-xl"></i>
                            <span>
                                <span class="block text-700 font-medium">Anggota Tim</span>
                                <p class="mt-1 mb-0 text-600 text-sm">Kelola admin dan peran</p>
                            </span>
                        </a>
                    </li>
                    <li class="mb-2" v-if="hasAccess('manageProduct') || hasAccess('manageAdmin')">
                        <a v-ripple
                            class="flex p-2 align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                            @click="goToBrandCtgr">
                            <i class="bi bi-boxes text-500 mr-3 text-xl"></i>
                            <span>
                                <span class="block text-700 font-medium">Merk & Kategori</span>
                                <p class="mt-1 mb-0 text-600 text-sm">Kelola merk dan kategori barang</p>
                            </span>
                        </a>
                    </li>
                    <li class="mb-2" v-if="hasAccess('manageProduct') || hasAccess('manageAdmin')">
                        <a v-ripple
                            class="flex p-2 align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                            @click="goToProduct">
                            <i class="bi bi-box-seam text-500 mr-3 text-xl"></i>
                            <span>
                                <span class="block text-700 font-medium">Barang</span>
                                <p class="mt-1 mb-0 text-600 text-sm">Kelola barang sewa</p>
                            </span>
                        </a>
                    </li>
                    <li class="mb-2" v-if="hasAccess('manageProduct') || hasAccess('manageAdmin')">
                        <a v-ripple
                            class="flex p-2 align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                            @click="goToTransaction">
                            <i class="bi bi-ui-checks text-500 mr-3 text-xl"></i>
                            <span>
                                <span class="block text-700 font-medium">Transaksi</span>
                                <p class="mt-1 mb-0 text-600 text-sm">Kelola transaksi penyewa</p>
                            </span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a v-ripple
                            class="flex p-2 align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                            <i class="pi pi-cog text-500 mr-3 text-xl"></i>
                            <span>
                                <span class="block text-700 font-medium">Pengaturan</span>
                                <p class="mt-1 mb-0 text-600 text-sm">Ubah data pribadi</p>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="mb-2">
                <a v-ripple
                    class="block p-2 font-medium text-700 flex align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                    <i class="pi pi-question-circle mr-2"></i>
                    <span>Bantuan</span>
                </a>
            </li>
            <li class="mb-2">
                <a v-ripple
                    class="block p-2 font-medium text-700 flex align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                    @click="logout">
                    <i class="pi pi-sign-out mr-2"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Avatar from './Avatar.vue';
import { useRouter } from 'vue-router';
import local from '@/utils/local-storage';
import helper from '@/utils/helper'

const hasAccess = helper.methods.hasAccess;
const router = useRouter();

const userName = ref('Tamu');
const userRole = ref('Pengunjung');

const goToAdminRoles = () => {
    if (hasAccess('manageAdmin')) {
        router.push({ name: 'adminRoles' });
    }
};


const goToBrandCtgr = () => {
    if (hasAccess('manageProduct') || hasAccess('manageAdmin')) {
        router.push({ name: 'manageBrandCtgr' });
    }
};

const goToProduct = () => {
    if (hasAccess('manageProduct') || hasAccess('manageAdmin')) {
        router.push({ name: 'manageProducts' });
    }
};


const goToTransaction = () => {
    if (hasAccess('manageProduct') || hasAccess('manageAdmin')) {
        router.push({ name: 'manageTransactions' });
    }
};

const logout = () => {
    local.remove('token');
    local.remove('user');
    router.push({ name: 'login' });
};

onMounted(() => {
    const userData = local.getUser();
    if (userData) {
        userName.value = userData[0].name;
        userRole.value = userData[0].roleName;
    }
});
</script>