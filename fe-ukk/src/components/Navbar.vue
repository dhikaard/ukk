<template>
  <div class="px-3 md:px-3 surface-overlay relative z-5">
    <div class="py-3 lg:py-3 w-full flex align-items-center justify-content-between ">
        <img src="https://api.dicebear.com/9.x/fun-emoji/svg?seed=Kimberly" alt="Image" height="40" class="lg:hidden block border-round" />
        <div class="flex w-full justify-content-end lg:justify-content-between align-items-center gap-3">
            <img src="https://api.dicebear.com/9.x/fun-emoji/svg?seed=Kimberly" alt="Image" height="40" class="hidden lg:block border-round" />
            <!-- <a v-ripple class="cursor-pointer block lg:hidden text-700 p-ripple"
            v-styleclass="{ selector: '#navbar-7', enterClass: 'hidden', leaveToClass: 'hidden', hideOnOutsideClick: true }">
            <i class="pi pi-bars text-4xl"></i>
            </a> -->
            <IconField iconPosition="right" class="w-6 hidden lg:block">
                <InputIcon class="pi pi-search"> </InputIcon>
                <InputText v-model="value1" placeholder="Search" class="w-full" />
            </IconField>
            <!-- <a v-ripple class="cursor-pointer block lg:hidden text-700 mt-1 p-ripple"
            v-styleclass="{ selector: '#navbar-sub-7', enterClass: 'hidden', leaveToClass: 'hidden', hideOnOutsideClick: true }">
            <i class="pi pi-objects-column text-3xl "></i>
            </a> -->
            <span class="pi pi-bars text-3xl cursor-pointer block lg:hidden text-700 mt-1 p-ripple"
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
                        <Avatar name="Alexander Agung" alt="User Avatar" style="height: 2.5rem;"/>
                        <span class="hidden">My Account</span>
                    </a>
                    <div :class="['absolute right-0 top-100 z-1 w-16rem origin-top', { 'hidden': !profileCardVisible }]">
                        <ProfileCard />
                    </div>
                </li>
                </ul>
            </div>
        </div>
        <div id="navbar-sub-7"
            class="hidden lg:hidden absolute lg:static lg:w-auto w-full surface-overlay left-0 top-100 z-1 shadow-2 lg:shadow-none">
            <ul class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row">
                <li>
                    <a v-ripple class="flex px-6 p-3 lg:px-3 lg:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="pi pi-home mr-2"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a v-ripple class="flex px-6 p-3 lg:px-3 lg:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="pi pi-inbox mr-2"></i>
                        <span>History</span>
                    </a>
                </li>
            </ul>
            <ul
                class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row border-top-1 surface-border lg:border-top-none">
                <li>
                    <a v-ripple
                        class="flex px-6 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="pi pi-bell text-base lg:text-2xl mr-2 lg:mr-0" v-badge.danger></i>
                        <span class="block lg:hidden font-medium">Notifications</span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="slideover-3" class="card hidden surface-overlay absolute top-0 right-0 shadow-2 w-full lg:w-6" style="height: 100vh;">
        <div class="flex flex-column">
            <div class="flex align-items-center justify-content-between px-4 py-4">
                <span class="text-900 text-xl font-medium">Menu</span>
                <Button icon="pi pi-times" class="p-button-rounded p-button-text p-button-plain"
                    v-styleclass="{ selector: '#slideover-3', leaveToClass: 'hidden', leaveActiveClass: 'fadeoutright' }"></Button>
            </div>
            <div class="surface-overlay border-top-1 surface-border">
                <ul class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row">
                <li>
                    <a v-ripple class="flex px-4 p-3 lg:px-3 lg:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="pi pi-home mr-2"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a v-ripple class="flex px-4 p-3 lg:px-3 lg:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="pi pi-inbox mr-2"></i>
                        <span>History</span>
                    </a>
                </li>
            </ul>
            <ul
                class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row border-top-1 surface-border lg:border-top-none">
                <li>
                    <a v-ripple
                        class="flex px-4 p-3 lg:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                        <i class="pi pi-bell text-base lg:text-2xl mr-2 lg:mr-0" v-badge.danger></i>
                        <span class="block lg:hidden font-medium">Notifications</span>
                    </a>
                </li>
            </ul>
            </div>
        </div>
    </div>
    </div>
    <div id="navbar-7"
        class="py-3 hidden lg:block absolute lg:static lg:w-auto w-full surface-overlay left-0 top-100 z-1 border-top-1 surface-border">
        <ul class="list-none p-0 m-0 flex lg:align-items-center flex-column lg:flex-row select-none">
            <li>
                <a v-ripple
                    class="flex p-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                    <i class="pi pi-home mr-2"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a v-ripple
                    class="flex p-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                    <i class="pi pi-inbox mr-2"></i>
                    <span>History</span>
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
const profileCardVisible1 = ref(false);
const profileCardVisible2 = ref(false);

const goToHistory = () => {
    router.push({ name: 'history' });
};

const toggleProfileCard1 = () => {
    profileCardVisible1.value = !profileCardVisible1.value;
};

const toggleProfileCard2 = () => {
    profileCardVisible2.value = !profileCardVisible2.value;
};


const isActive = (routeName) => {
    return router.currentRoute.value.name === routeName;
};
</script>
