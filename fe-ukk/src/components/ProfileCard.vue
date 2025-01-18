<template>
    <div class="surface-card border-round shadow-2 select-none">
        <ul class="list-none p-2 m-0">
            <li>
                <a v-ripple class="flex p-2 align-items-center cursor-pointer p-ripple"
                    v-styleclass="{ selector: '#pb_profile_submenu', enterClass: 'hidden', enterActiveClass: 'slidedown', leaveToClass: 'hidden', leaveActiveClass: 'slideup' }">
                    <span class="mr-3 inline-flex">
                        <Avatar name="Alexander Agung" alt="User Avatar" style="height: 2.5rem;" />
                    </span>
                    <div>
                        <span class="font-medium text-900 mb-2">Alexander Agung</span>
                        <p class="mt-1 mb-0 text-600">Pengguna</p>
                    </div>
                    <i class="pi pi-chevron-down text-700 ml-auto"></i>
                </a>
                <div class="border-top-1 surface-border my-3" style="height:1px"></div>
                <ul id="pb_profile_submenu" class="list-none p-0 m-0 overflow-hidden">
                    <li class="mb-2">
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
                    <li class="mb-2">
                        <a v-ripple
                            class="flex p-2 align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                            @click="goToProduct">
                            <i class="pi pi-th-large text-500 mr-3 text-xl"></i>
                            <span>
                                <span class="block text-700 font-medium">Barang</span>
                                <p class="mt-1 mb-0 text-600 text-sm">Kelola barang sewa</p>
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
                    class="block p-2 font-medium text-700 flex align-items-center hover:surface-50 border-transparent border-1 hover:border-100 border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                    <i class="pi pi-sign-out mr-2"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import Avatar from './Avatar.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const goToAdminRoles = () => {
    router.push({ name: 'adminRoles' });
};

const goToProduct = () => {
    router.push({ name: 'manageProducts' });
};
</script>
