<template>
  <div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <div class="flex flex-column justify-content-end">
      <div class="sticky-container">
        <!-- <BannerAccount class="z-5 h-full" v-if="!route.meta.hideNavbarFooter" /> -->
        <Navbar class="z-5" v-if="!route.meta.hideNavbarFooter" />
      </div>
      <Toast />
      <router-view />
      <Footer v-if="!route.meta.hideNavbarFooter" />
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

const route = useRoute()
</script>

<style scoped>
.sticky-container {
  position: sticky;
  top: 0;
  z-index: 10;
}
</style>