<template>
<div class="surface-ground px-4 py-8 md:px-6 lg:px-8 ">
    <div class="flex flex-wrap shadow-5 justify-content-center">
        <div class="w-full lg:w-6 px-0 py-4 lg:p-7 bg-blue-50">
            <Carousel :value="features" :autoplayInterval="3000">
                <template #item="slotProps">
                    <div class="text-center mb-8">
                        <img :src="slotProps.data.image" alt="Feature" class="mb-6 feature-image">
                        <div class="mx-auto font-medium text-xl mb-4 text-blue-900">{{slotProps.data.title}}</div>
                        <p class="m-0 text-blue-700 line-height-3">{{slotProps.data.text}}</p>
                    </div>
                </template>
            </Carousel>
        </div>
        <div class="w-full lg:w-6 p-4 lg:p-7 surface-card">
            <div class="flex align-items-center justify-content-between mb-7">
                <span class="text-2xl font-medium text-900">Masuk</span>
                <RouterLink to="/register" class="font-medium text-blue-500 hover:text-blue-700 no-underline cursor-pointer transition-colors transition-duration-150">
                    Daftar
                </RouterLink>
            </div>
            <div class="flex justify-content-between">
                <Button class="ml-2 w-12 font-medium border-1 surface-border surface-100 py-3 px-2 p-component hover:surface-200 active:surface-300 text-900 cursor-pointer transition-colors transition-duration-150 inline-flex align-items-center justify-content-center">
                    <i class="pi pi-google text-red-500 mr-2"></i>
                    <span>Masuk dengan Google</span>
                </Button>
            </div>

            <Divider align="center" class="my-4">
                <span class="text-600 font-normal text-sm">OR</span>
            </Divider>

            <div class="field">
                <label for="email" class="block text-900 font-medium mb-2">Email</label>
                <InputText id="email" 
                    type="email" 
                    v-model="store.email" 
                    placeholder="Alamat email" 
                    :class="{'p-invalid': validationErrors?.email, 'w-full': true, 'mb-3': !validationErrors?.email, 'p-3': true}" />
                <small v-if="validationErrors?.email" class="p-error">{{ validationErrors.email[0] }}</small>
            </div>

            <div class="field">
                <label for="password" class="block text-900 font-medium mb-2">Password</label>
                <InputText id="password"  
                    type="password" 
                    v-model="store.password" 
                    placeholder="Password" 
                    :class="{'p-invalid': validationErrors?.password, 'w-full': true, 'mb-3': !validationErrors?.password, 'p-3': true}" />
                <small v-if="validationErrors?.password" class="p-error">{{ validationErrors.password[0] }}</small>
            </div>

            <Button label="Masuk" 
                class="w-full p-3 text-xl" 
                :loading="store.loading['login']" 
                @click="handleLogin" />
        </div>
    </div>
</div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth.store';
import { ref } from 'vue';

const store = useAuthStore();
const validationErrors = ref(null);

const handleLogin = async () => {
  try {
    validationErrors.value = null;
    await store.login();
  } catch (error) {
    if (error.error?.errors) {
      validationErrors.value = error.error.errors;
    }
  }
};

const features = ref([
  {
    title: "Sistem Penyewaan Mudah",
    text: "Proses penyewaan yang simpel dan cepat untuk kenyamanan Anda",
    image: "/easy-management.png"
  },
  {
    title: "Pengelolaan Denda",
    text: "Kami menyediakan sistem pengelolaan denda yang dapat diatur sesuai kebutuhan Anda",
    image: "/fines-management.png"
  }
]);

</script>
<style scoped>
.feature-image {
    width: 200px;  /* atau ukuran yang Anda inginkan */
    height: 200px; /* atau ukuran yang Anda inginkan */
    object-fit: contain; /* ini akan menjaga aspek ratio gambar */
}
</style>