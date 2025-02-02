<template>
    <footer class="surface-ground px-4 py-6 md:px-6 lg:px-8">
        <div class="grid">
            <!-- Company Info -->
            <div class="col-12 md:col-4 text-center md:text-left">
                <img src="/logo.webp" alt="Logo" class="border-circle h-5rem mb-3">
                <h3 class="text-900 font-bold text-xl mb-2">Amfibi Outdoor</h3>
                <p class="text-700 line-height-3 mb-3">
                    Outdoor & Sporting Goods Company
                    Melayani penyewaan alat outdoor berkualitas
                    dengan harga terjangkau.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-12 md:col-4">
                <h4 class="text-900 font-semibold mb-3">Menu</h4>
                <ul class="list-none p-0">
                    <li class="mb-2">
                        <a class="text-700 cursor-pointer hover:text-primary transition-colors transition-duration-150">Beranda</a>
                    </li>
                    <li class="mb-2">
                        <a class="text-700 cursor-pointer hover:text-primary transition-colors transition-duration-150">Produk</a>
                    </li>
                    <li class="mb-2">
                        <a class="text-700 cursor-pointer hover:text-primary transition-colors transition-duration-150">Tentang Kami</a>
                    </li>
                    <li class="mb-2">
                        <a class="text-700 cursor-pointer hover:text-primary transition-colors transition-duration-150">Kontak</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-12 md:col-4">
                <h4 class="text-900 font-semibold mb-3">Hubungi Kami</h4>
                <div class="flex align-items-center mb-3">
                    <i class="pi pi-map-marker text-primary mr-2"></i>
                    <span class="text-700 line-height-3">
                        Jl. Bukit Beringin Bar., Tambakaji, Kec. Ngaliyan, 
                        Kota Semarang, Jawa Tengah 50181
                    </span>
                </div>
                <div class="flex align-items-center mb-3">
                    <i class="pi pi-phone text-primary mr-2"></i>
                    <span class="text-700">+62 123 4567 890</span>
                </div>
                <div class="flex align-items-center mb-4">
                    <i class="pi pi-envelope text-primary mr-2"></i>
                    <span class="text-700">info@amfibioutdoor.com</span>
                </div>

                <!-- Social Media -->
                <div class="flex gap-3">
                    <a class="flex align-items-center justify-content-center border-circle cursor-pointer">
                        <i class="pi pi-facebook text-black text-xl"></i>
                    </a>
                    <a class="flex align-items-center justify-content-center border-circle cursor-pointer">
                        <i class="pi pi-instagram text-black text-xl"></i>
                    </a>
                    <a class="flex align-items-center justify-content-center border-circle cursor-pointer">
                        <i class="pi pi-whatsapp text-black text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-top-1 surface-border mt-4 pt-4 text-center">
            <span class="text-600">&copy; {{ currentYear }} Amfibi Outdoor. All rights reserved.</span>
        </div>
    </footer>
</template>

<script setup>
import { ref } from "vue";

const currentYear = ref(new Date().getFullYear());
</script>