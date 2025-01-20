<template>
  <div class="flex flex-column justify-content-end">
    <div class="sticky-container">
      <Navbar class="z-5" v-if="!route.meta.hideNavbarFooter" />
    </div>
    <router-view />
    <Footer v-if="!route.meta.hideNavbarFooter" />
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