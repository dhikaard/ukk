<template>
<div class="surface-section px-4 py-8 md:px-6 lg:px-8">
    <div style="background: radial-gradient(50% 109137.91% at 50% 50%, rgba(233, 30, 99, 0.1) 0%, rgba(254, 244, 247, 0) 100%);" class="text-center">
        <span class="bg-white text-pink-500 font-bold text-2xl inline-block px-3">404</span>
    </div>
    <div class="mt-6 mb-5 font-bold text-6xl text-900 text-center">Halaman Tidak Ditemukan</div>
    <p class="text-700 text-3xl mt-0 mb-6 text-center">Maaf ya, sepertinya kamu salah url😔</p>
    <div class="text-center">
        <Button class="p-button-text mr-2" label="Kembali" icon="pi pi-arrow-left" @click="goHome"></Button>
    </div>
</div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();
const goHome = () => {
    router.push({ name: 'home' });
};
</script>