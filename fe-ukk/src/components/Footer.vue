<template>
    <div class="surface-100 bottom-0 px-4 py-5 md:px-6 lg:px-8 text-center">
        <img src=" /logo.webp" alt="Logo" class="border-circle h-4rem">
        <div class="font-medium text-900 mt-4 mb-3">
            &copy;{{ currentYear }} Amfibi Outdoor
        </div>
        <div class="flex flex-column ">
            <p class="text-800 font-semibold m-0">
                RENTAL ALAT OUTDOOR SEMARANG
            </p>
            <p class="text-600 mt-0">
                Outdoor & Sporting Goods Company
            </p>
        </div>
        <p class="text-600 line-height-3 mt-0 mb-4">
            Jl. Bukit Beringin Bar., Tambakaji, Kec. Ngaliyan, Kota Semarang, Jawa Tengah 50181
        </p>
        <div class="flex align-items-center justify-content-center">
            <a class="cursor-pointer text-700 mr-5">
                <i class="pi pi-twitter"></i>
            </a>
            <a class="cursor-pointer text-700 mr-5">
                <i class="pi pi-facebook"></i>
            </a>
            <a class="cursor-pointer text-700">
                <i class="pi pi-github"></i>
            </a>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const currentYear = ref(new Date().getFullYear());
</script>