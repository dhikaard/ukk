<template>
  <div class="surface-overlay relative z-5">
    <div class="px-3 md:px-3 py-3 lg:py-3 w-full flex align-items-center justify-content-between ">
        <img src="https://api.dicebear.com/9.x/fun-emoji/svg?seed=Kimberly" alt="Image" height="40" class="lg:hidden block border-round" />
        <div class="flex w-full justify-content-end lg:justify-content-between align-items-center gap-3">
            <img src="https://api.dicebear.com/9.x/fun-emoji/svg?seed=Kimberly" alt="Image" height="40" class="hidden lg:block border-round" />
            <IconField iconPosition="right" class="w-6 hidden lg:block">
                <InputIcon class="pi pi-search"> </InputIcon>
                <InputText v-model="value1" placeholder="Search" class="w-full" />
            </IconField>
            <span class="pi pi-bars text-4xl cursor-pointer block lg:hidden text-700 p-ripple"
              v-styleclass="{ selector: '#slideover-3', enterClass: 'hidden', enterActiveClass: 'fadeinright', leaveToClass: 'hidden', leaveActiveClass: 'fadeoutright', hideOnOutsideClick: true }"></span>
            <div class="hidden lg:block absolute lg:static lg:w-auto w-full surface-overlay left-0 top-100 z-1 shadow-2 lg:shadow-none">
                <ul class="list-none p-0 m-0 flex gap-2">
                    <li>
                        <a v-ripple class="flex px-6 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                            <i class="pi pi-bell text-base lg:text-2xl mr-2 lg:mr-0" v-badge.danger></i>
                            <span class="block lg:hidden font-medium">Notifications</span>
                        </a>
                    </li>
                    <li class="inline-flex relative">
                        <a v-ripple class="text-900 font-medium inline-flex align-items-center cursor-pointer px-1 lg:px-3 mr-2 lg:mr-0 border-bottom-2 border-transparent select-none p-ripple"
                            v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein', leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true}"
                            @click="toggleProfileCard">
                            <Avatar name="Dhika" alt="User Avatar" style="height: 2.5rem;"/>
                            <span class="hidden">My Account</span>
                        </a>
                        <div :class="['absolute right-0 top-100 z-1 w-16rem origin-top', { 'hidden': !profileCardVisible }]">
                            <ProfileCard />
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div id="slideover-3" class="card hidden surface-overlay absolute top-0 right-0 shadow-2 w-full lg:w-6 lg:hidden" style="height: 100vh;">
        <div class="flex flex-column">
            <div class="flex align-items-center justify-content-between px-3 py-3">
                <span class="text-900 text-xl font-medium">Menu</span>
                <Button icon="pi pi-times text-xl" class="p-button-rounded p-button-text p-button-plain"
                    v-styleclass="{ selector: '#slideover-3', leaveToClass: 'hidden', leaveActiveClass: 'fadeoutright' }"></Button>
            </div>
            <div class="surface-overlay border-top-1 surface-border">
                <ul class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row">
                <li>
                    <a v-ripple class="flex px-4 p-3 lg:px-3 lg:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="text-lg pi pi-home mr-2"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a v-ripple class="flex px-4 p-3 lg:px-3 lg:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                    @click="goToHistory">
                        <i class="text-lg pi pi-inbox mr-2"></i>
                        <span>History</span>
                    </a>
                </li>
                <li>
                    <a v-ripple
                        class="flex px-4 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="text-lg pi pi-bell text-base lg:text-2xl mr-2 lg:mr-0" v-badge.danger></i>
                        <span class="block lg:hidden font-medium">Notifications</span>
                    </a>
                </li>
                <li>
                    <a v-ripple class="flex px- p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                        v-styleclass="{ selector: '#pb_profile', enterClass: 'hidden', enterActiveClass: 'slidedown', leaveToClass: 'hidden', leaveActiveClass: 'slideup' }">
                        <span class="mr-3 inline-flex">
                            <Avatar name="Dhika" alt="User Avatar" style="height: 2.5rem;"/>
                        </span>
                        <div>
                            <span class="font-medium text-900 mb-2">Dhika</span>
                            <p class="mt-1 mb-0 text-600">Pengguna</p>
                        </div>
                        <i class="text-lg pi pi-chevron-down text-700 ml-auto"></i>
                    </a>
                    <ul id="pb_profile" class="list-none p-0 m-0 overflow-hidden hidden">
                        <div class="border-top-1 mx-3 surface-border  w-full" style="height:1px"></div>
                        <li class="mb-2">
                            <a v-ripple class="flex px-4 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                                <i class="text-lg pi pi-cog text-500 mr-3 text-xl"></i>
                                <span>
                                    <span class="block text-700 font-medium">Pengaturan</span>
                                    <p class="mt-1 mb-0 text-600 text-sm">Ubah data pribadi</p>
                                </span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a v-ripple class="flex px-4 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                                <i class="pi pi-question-circle mr-2"></i>
                                <span>Bantuan</span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a v-ripple class="flex px-4 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                            <i class="pi pi-sign-out mr-2"></i>
                            <span>Keluar</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            </div>
        </div>
    </div>
    </div>
    <div id="navbar-7"
        class="px-3 md:px-3 hidden lg:block absolute lg:static lg:w-auto w-full surface-overlay left-0 top-100 z-1 border-top-1 surface-border">
        <ul class="list-none p-0 m-0 flex lg:align-items-center flex-column lg:flex-row select-none">
            <li>
                <a
                    class="nav-link relative  cursor-pointer flex items-center px-4 py-3 text-gray-500"
                    :class="{ 'active': isActive('home') }"
                    @click="router.push({ name: 'home' })">
                    <i class="pi pi-home mr-2"></i>
                    <span>Home</span>
                    <span class="border-anim"></span>
                </a>
            </li>
            <li>
                <a
                    class="nav-link relative  cursor-pointer flex items-center px-4 py-3 text-gray-500"
                    :class="{ 'active': isActive('history') }"
                    @click="goToHistory">
                    <i class="pi pi-inbox mr-2"></i>
                    <span>History</span>
                    <span class="border-anim"></span>
                </a>
            </li>
            <li>
                <a
                    class="nav-link relative  cursor-pointer flex items-center px-4 py-3 text-gray-500"
                    :class="{ 'active': isActive('ketentuan') }"
                    @click="router.push({ name: 'ketentuan' })">
                    <i class="pi pi-inbox mr-2"></i>
                    <span>Ketentuan</span>
                    <span class="border-anim"></span>
                </a>
            </li>
        </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Avatar from './Avatar.vue';
import ProfileCard from './ProfileCard.vue';

const router = useRouter();
const profileCardVisible = ref(false);

const goToHistory = () => {
    router.push({ name: 'history' });
};

const toggleProfileCard = () => {
    profileCardVisible.value = !profileCardVisible.value;
};


const isActive = (routeName) => {
    return router.currentRoute.value.name === routeName;
};
</script>


<style>
.nav-link {
    position: relative;
}

.border-anim {
    content: "";
    position: absolute;
    top: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: var(--primary-color, #007bff);
    transition: width 0.4s ease-in-out, left 0.4s ease-in-out;
}

.nav-link.active {
    color: #111827; /* Warna teks saat aktif */
}

.nav-link.active .border-anim {
    width: 100%;
    left: 0;
}
</style>