<template>
    <div class="surface-section shadow-3 relative z-5">
        <CommandMenu :visible="commandMenuVisible" @update:visible="commandMenuVisible = $event" />
        <div class="px-3 md:px-3 py-2 w-full flex align-items-center justify-content-between ">
            <img src=" /logo.webp" alt="Logo" class="md:hidden block border-circle h-3rem border-1 cursor-pointer"
                @click="goToHome" />
            <div class="flex w-full justify-content-end md:justify-content-between align-items-center gap-3">
                <img src=" /logo.webp" alt="Logo" class="hidden md:block border-circle h-3rem  cursor-pointer"
                    @click="goToHome" />
                <IconField iconPosition="left" class="w-7 hidden md:block">
                    <InputIcon class="pi pi-search"> </InputIcon>
                    <InputText v-model="value1" placeholder="Cari menu, pintasan, tema, dan yang lainnya.." class="w-full border-round-3xl  " @click="showCommandMenu" />
                </IconField>
                <span class="pi pi-bars text-4xl cursor-pointer block md:hidden text-700 p-ripple"
                    v-styleclass="{ selector: '#slideover-3', enterClass: 'hidden', enterActiveClass: 'fadeinright', leaveToClass: 'hidden', leaveActiveClass: 'fadeoutright', hideOnOutsideClick: true }"></span>
                <div
                    class="hidden md:block absolute md:static md:w-auto w-full left-0 top-100 z-1 shadow-2 md:shadow-none">
                    <ul class="list-none p-0 m-0 flex gap-2">
                        <template v-if="!isAuthenticated">
                            <li class="inline-flex">
                                <Button 
                                    label="Masuk" 
                                    class="p-button-text"
                                    @click="router.push('/login')" 
                                />
                            </li>
                            <li class="inline-flex">
                                <Button 
                                    label="Daftar" 
                                    @click="router.push('/register')" 
                                />
                            </li>
                        </template>
                        <template v-else>
                            <li class="inline-flex relative">
                                <a v-ripple
                                   data-notification-toggle
                                   class="flex px-6 p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer"
                                   @click.stop="toggleNotifications">
                                    <i class="pi pi-bell text-base md:text-2xl mr-2 md:mr-0"
                                        v-badge="notificationStore.overdueCount > 0 ? notificationStore.overdueCount : undefined">
                                    </i>
                                    <span class="block md:hidden font-medium">Notifikasi</span>
                                </a>

                                <!-- Notification Panel -->
                                <div v-if="notificationsVisible" 
                                    class="notification-panel surface-overlay border-1 border-round surface-border absolute right-0 top-100 w-full md:w-25rem shadow-2 origin-top"
                                    style="z-index: 1000;">
                                    <div class="p-3 surface-50 font-medium text-700 border-bottom-1 surface-border">
                                        Notifikasi Keterlambatan
                                    </div>
                                    
                                    <div v-if="notificationStore.loading" class="p-4">
                                        <Skeleton height="4rem" class="mb-2" />
                                        <Skeleton height="4rem" class="mb-2" />
                                        <Skeleton height="4rem" />
                                    </div>
                                    
                                    <div v-else-if="notificationStore.items.length === 0" 
                                        class="p-4 text-center text-600">
                                        <i class="pi pi-check-circle text-xl mb-2"></i>
                                        <p class="m-0">Tidak ada keterlambatan</p>
                                    </div>
                                    
                                    <div v-else class="max-h-25rem overflow-auto">
                                        <div v-for="item in notificationStore.items" 
                                            :key="item.trx_rent_items_id"
                                            class="p-3 border-bottom-1 surface-border hover:surface-100 cursor-pointer transition-colors transition-duration-150"
                                            @click="goToHistory">
                                            <div class="flex align-items-center mb-2">
                                                <i class="pi pi-clock text-orange-500 mr-2"></i>
                                                <span class="font-medium text-900">Keterlambatan Pengembalian</span>
                                            </div>
                                            <div class="text-600 mb-2">{{ item.trx_code }}</div>
                                            <div class="text-sm text-red-500">
                                                Jatuh tempo: {{ formatDate(item.rent_end_date) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="inline-flex relative">
                                <a v-ripple
                                    class="flex px-6 p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer"
                                    @click="toggleCart">
                                    <i class="pi pi-shopping-cart text-base md:text-2xl mr-2 md:mr-0" 
                                    v-badge="cartStore.itemCount > 0 ? cartStore.itemCount : ''"></i>
                                    <span class="block md:hidden font-medium">Keranjang</span>
                                </a>
                                <Cart v-model:visible="cartVisible" />
                            </li>
                            <li class="inline-flex relative">
                                <a v-ripple
                                    class="text-900 font-medium inline-flex align-items-center cursor-pointer px-1 md:px-3 mr-2 md:mr-0 border-bottom-2 border-transparent select-none p-ripple"
                                    v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein', leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true }">
                                    <Avatar name="Dhika" alt="User Avatar" style="height: 2.5rem;" />
                                    <span class="hidden">My Account</span>
                                </a>
                                <div
                                    :class="['absolute right-0 top-100 z-1 w-16rem origin-top', { 'hidden': !profileCardVisible }]">
                                    <ProfileCard />
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <div id="slideover-3"
                class="card hidden surface-overlay absolute top-0 right-0 shadow-2 w-full md:w-6 md:hidden"
                style="height: 100vh;">
                <div class="flex flex-column">
                    <div class="flex align-items-center justify-content-between px-3 py-3">
                        <span class="text-900 text-xl font-medium">Menu</span>
                        <Button icon="pi pi-times text-xl" class="p-button-rounded p-button-text p-button-plain"
                            v-styleclass="{ selector: '#slideover-3', leaveToClass: 'hidden', leaveActiveClass: 'fadeoutright' }"></Button>
                    </div>
                    <div class="surface-overlay border-top-1 surface-border">
                        <ul class="list-none p-0 m-0 flex md:align-items-center select-none flex-column md:flex-row">
                            <template v-if="!isAuthenticated">
                                <li>
                                    <a v-ripple
                                        class="flex px-4 p-3 md:px-3 md:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        @click="router.push('/login')">
                                        <i class="text-lg pi pi-sign-in mr-2"></i>
                                        <span>Masuk</span>
                                    </a>
                                </li>
                                <li>
                                    <a v-ripple
                                        class="flex px-4 p-3 md:px-3 md:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        @click="router.push('/register')">
                                        <i class="text-lg pi pi-user-plus mr-2"></i>
                                        <span>Daftar</span>
                                    </a>
                                </li>
                            </template>
                            <template v-else>
                                <li>
                                    <a v-ripple
                                        class="flex px-4 p-3 md:px-3 md:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        @click="goToHome">
                                        <i class="text-lg pi pi-home mr-2"></i>
                                        <span>Beranda</span>
                                    </a>
                                </li>
                                <li>
                                    <a v-ripple
                                        class="flex px-4 p-3 md:px-3 md:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        @click="goToRent">
                                        <i class="text-lg pi pi-home mr-2"></i>
                                        <span>Sewa</span>
                                    </a>
                                </li>
                                <li>
                                    <a v-ripple
                                        class="flex px-4 p-3 md:px-3 md:py-2 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        @click="goToHistory">
                                        <i class="text-lg pi pi-inbox mr-2"></i>
                                        <span>Riwayat Sewa</span>
                                    </a>
                                </li>
                                <li>
                                    <a v-ripple
                                        class="flex px-4 p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        @click="goToTerms">
                                        <i class="text-lg pi pi-bell text-base md:text-2xl mr-2 md:mr-0"></i>
                                        <span class="block md:hidden font-medium">Ketentuan</span>
                                    </a>
                                </li>
                                <li>
                                    <a v-ripple
                                        class="flex px- p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple"
                                        v-styleclass="{ selector: '#pb_profile', enterClass: 'hidden', enterActiveClass: 'slidedown', leaveToClass: 'hidden', leaveActiveClass: 'slideup' }">
                                        <span class="mr-3 inline-flex">
                                            <Avatar name="Dhika" alt="User Avatar" style="height: 2.5rem;" />
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
                                            <a v-ripple
                                                class="flex px-4 p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                                                <i class="text-lg pi pi-cog text-500 mr-3 text-xl"></i>
                                                <span>
                                                    <span class="block text-700 font-medium">Pengaturan</span>
                                                    <p class="mt-1 mb-0 text-600 text-sm">Ubah data pribadi</p>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a v-ripple
                                                class="flex px-4 p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                                                <i class="pi pi-question-circle mr-2"></i>
                                                <span>Bantuan</span>
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a v-ripple
                                                class="flex px-4 p-3 md:px-3 align-items-center text-600 hover:text-900 hover:surface-100 font-medium border-round cursor-pointer transition-colors transition-duration-150 p-ripple">
                                                <i class="pi pi-sign-out mr-2"></i>
                                                <span>Keluar</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="navbar-7"
            class="px-3 md:px-3 hidden md:block absolute md:static md:w-auto w-full surface-overlay left-0 top-100 z-1 border-top-1 surface-border">
            <ul class="list-none p-0 m-0 flex md:align-items-center flex-column md:flex-row select-none">
                <li>
                    <a class="nav-link relative  cursor-pointer flex align-items-center px-4 py-3 text-gray-500"
                        :class="{ 'active': isActive('home') }" @click="goToHome">
                        <i class="pi pi-home mr-2"></i>
                        <span>Beranda</span>
                        <span class="border-anim"></span>
                    </a>
                </li>
                <li>
                    <a class="nav-link relative  cursor-pointer flex align-items-center px-4 py-3 text-gray-500"
                        :class="{ 'active': isActive('rent') }" @click="goToRent">
                        <i class="pi pi-home mr-2"></i>
                        <span>Sewa</span>
                        <span class="border-anim"></span>
                    </a>
                </li>
                <li>
                    <a class="nav-link relative  cursor-pointer flex align-items-center px-4 py-3 text-gray-500"
                        :class="{ 'active': isActive('history') }" @click="goToHistory">
                        <i class="pi pi-inbox mr-2"></i>
                        <span>Riwayat Sewa</span>
                        <span class="border-anim"></span>
                    </a>
                </li>
                <li>
                    <a class="nav-link relative  cursor-pointer flex align-items-center px-4 py-3 text-gray-500"
                        :class="{ 'active': isActive('terms') }" @click="goToTerms">
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
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import Avatar from './Avatar.vue';
import CommandMenu from './CommandMenu.vue';
import ProfileCard from './ProfileCard.vue';
import { useCartStore } from '@/stores/cart.store';
import Cart from './Cart.vue';
import { useNotificationStore } from '@/stores/notification.store';
import local from '@/utils/local-storage';

const router = useRouter();
const profileCardVisible = ref(false);
const cartVisible = ref(false);
const commandMenuVisible = ref(false);
const cartStore = useCartStore();
const notificationStore = useNotificationStore();
const notificationsVisible = ref(false);

const isAuthenticated = computed(() => {
    return !!local.get('token');
});

onMounted(() => {
    window.addEventListener('keydown', handleShortcut);
    notificationStore.getOverdueRentals();
    checkInterval = setInterval(() => {
        notificationStore.getOverdueRentals();
    }, 300000); // Check every 5 minutes
    document.addEventListener('click', closeNotifications);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleShortcut);
    clearInterval(checkInterval);
    document.removeEventListener('click', closeNotifications);
});

const showCommandMenu = () => {
    commandMenuVisible.value = true;
};

const handleShortcut = (event) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();
        showCommandMenu();
    }
};

const goToRent = () => {
    router.push({ name: 'rent' });
};

const goToHistory = () => {
    router.push({ name: 'history' });
};

const goToHome = () => {
    router.push({ name: 'home' });
};

const goToTerms = () => {
    router.push({ name: 'terms' });
};

const isActive = (routeName) => {
    return router.currentRoute.value.name === routeName;
};

const toggleCart = () => {
    cartVisible.value = !cartVisible.value;
};

const closeCart = () => {
    cartVisible.value = false;
};

const toggleNotifications = (event) => {
    event.stopPropagation();
    notificationsVisible.value = !notificationsVisible.value;
};

const closeNotifications = (e) => {
    if (!e.target.closest('.notification-panel') && 
        !e.target.closest('[data-notification-toggle]')) {
        notificationsVisible.value = false;
    }
};

// Format date helper
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Check for overdue rentals periodically
let checkInterval;
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
    color: #111827;
    /* Warna teks saat aktif */
}

.nav-link.active .border-anim {
    width: 100%;
    left: 0;
}

.notification-panel {
    transform-origin: top;
    animation: slideDown 0.2s ease-out;
    z-index: 1000;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>