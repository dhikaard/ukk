<template>
    <div class="surface-section sticky top-0 px-3 lg:px-6 flex align-items-stretch relative border-bottom-1 surface-border"
        style="min-height: 80px;">
        <div class="flex align-items-center justify-content-center">
            <img src="../assets/logo.jpg" alt="Image" height="40" class="hidden lg:inline mr-3 lg:mr-6">
            <img src="../assets/logo.jpg" alt="Image" height="40" class="inline lg:hidden mr-3 lg:mr-6">
        </div>
        <div class="flex align-items-center flex-auto">
            <template v-if="isLoggedIn">
                <IconField iconPosition="left" class="w-full max-w-full flex sm:flex">
                    <InputIcon class="pi pi-search"></InputIcon>
                    <InputText v-model="value1" placeholder="Cari" class="w-full" @click="showCommandMenu" />
                </IconField>
            </template>
        </div>
        <div class="flex ml-3 lg:ml-5">
            <template v-if="isLoggedIn">
                <ul class="list-none p-0 m-0 flex gap-2">
                    <li class="inline-flex relative">
                        <a v-ripple
                            :class="['text-900', 'font-medium', 'inline-flex', 'align-items-center', 'cursor-pointer', 'px-1', 'lg:px-3', 'border-bottom-2', 'border-transparent', 'hover:border-primary', isActive('history') ? 'border-primary' : '']"
                            @click="goToHistory">
                            <i v-tooltip.bottom="'Riwayat'" class="bi bi-receipt-cutoff text-2xl" v-badge></i>
                            <span class="hidden">History</span>
                        </a>
                    </li>
                    <li class="inline-flex relative">
                        <a v-ripple
                            class="text-900 font-medium inline-flex align-items-center cursor-pointer px-1 lg:px-3 mr-2 lg:mr-0 border-bottom-2 border-transparent select-none p-ripple"
                            v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein', leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true }">
                            <Avatar :name="local.getUser()?.[0]?.name || 'Tamu'" alt="User Avatar"
                                style="height: 2.5rem;" />
                            <span class="hidden">My Account</span>
                        </a>
                        <div
                            :class="['absolute right-0 top-100 z-1 w-16rem origin-top', { 'hidden': !profileCardVisible }]">
                            <ProfileCard />
                        </div>
                    </li>
                </ul>
            </template>
            <template v-else>
                <div class="flex align-items-center">
                    <Button label="Masuk" class="p-button-text h-3rem font-bold" @click="goToLogin"></Button>
                    <Button label="Daftar" class="ml-3 h-3rem font-bold" @click="goToRegister"></Button>
                </div>
            </template>

        </div>
    </div>
    <CommandMenu :visible="commandMenuVisible" @update:visible="commandMenuVisible = $event" />
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import Avatar from './Avatar.vue';
import ProfileCard from './ProfileCard.vue';
import CommandMenu from './CommandMenu.vue';
import local from '../utils/local-storage';

const router = useRouter();
const profileCardVisible = ref(false);
const commandMenuVisible = ref(false);

const goToHistory = () => {
    router.push({ name: 'history' });
};

const goToLogin = () => {
    router.push({ name: 'login' });
};

const goToRegister = () => {
    router.push({ name: 'register' });
};

const showCommandMenu = () => {
    commandMenuVisible.value = true;
};

const isActive = (routeName) => {
    return router.currentRoute.value.name === routeName;
};

const isLoggedIn = computed(() => {
    return local.getUser() && local.get('token');
});
</script>
