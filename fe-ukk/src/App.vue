<template>
  <div class="flex flex-column justify-content-end">
    <Navbar class="z-5" v-if="!route.meta.hideNavbarFooter" />
    <router-view />
    <Toast />
    <Footer class="bottom-0" v-if="!route.meta.hideNavbarFooter" />
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

const route = useRoute()
</script>

<style>
.sticky-container {
  position: sticky;
  top: 0;
  z-index: 10;
}</style>