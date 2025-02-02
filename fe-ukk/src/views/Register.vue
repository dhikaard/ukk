<template>
<div class="surface-ground px-4 py-8 md:px-6 lg:px-8">
    <div class="flex flex-wrap shadow-5 justify-content-center">
        <!-- Carousel section -->
        <div class="w-full lg:w-6 px-0 py-4 lg:p-7 bg-blue-50">
            <Carousel :value="features" :autoplayInterval="3000">
                <template #item="slotProps">
                    <div class="text-center mb-8">
                        <img :src="slotProps.data.image" alt="Feature" class="mb-6 feature-image">
                        <div class="mx-auto font-medium text-xl mb-3 text-blue-900">{{slotProps.data.title}}</div>
                        <p class="m-0 text-blue-700 line-height-3">{{slotProps.data.text}}</p>
                    </div>
                </template>
            </Carousel>
        </div>

        <!-- Form section -->
        <div class="w-full lg:w-6 p-4 lg:p-7 surface-card">
            <div class="mb-4">
                <h2 class="text-2xl font-medium text-900 mb-2">Ayo Bergabung! 👋</h2>
                <p class="text-600 mt-0">Lengkapi data dirimu untuk memulai petualangan</p>
            </div>

            <!-- Name field -->
            <div class="field ">
                <label for="name" class="block text-900 font-medium mb-2">Siapa namamu?</label>
                <InputText id="name" type="text" v-model="store.name" placeholder="Masukkan nama lengkapmu" 
                    :class="{'p-invalid': validationErrors?.name, 'w-full': true}" />
                <small v-if="validationErrors?.name" class="p-error">{{ validationErrors.name[0] }}</small>
            </div>

            <!-- Email and Phone in one row -->
            <div class="grid ">
                <div class="col-12 md:col-6">
                    <label for="email" class="block text-900 font-medium mb-2">Email kamu</label>
                    <InputText id="email" type="email" v-model="store.email" placeholder="contoh@email.com"
                        :class="{'p-invalid': validationErrors?.email, 'w-full': true}" />
                    <small v-if="validationErrors?.email" class="p-error">{{ validationErrors.email[0] }}</small>
                </div>
                <div class="col-12 md:col-6">
                    <label for="phone" class="block text-900 font-medium mb-2">Nomor WhatsApp</label>
                    <InputText id="phone" v-model="store.phone" placeholder="Cth: 08123456789"
                        :class="{'p-invalid': validationErrors?.phone, 'w-full': true}" />
                    <small v-if="validationErrors?.phone" class="p-error">{{ validationErrors.phone[0] }}</small>
                </div>
            </div>

            <!-- Address field -->
            <div class="field ">
                <label for="address" class="block text-900 font-medium mb-2">Dimana kamu tinggal?</label>
                <Textarea id="address" v-model="store.address" rows="3" autoResize
                    placeholder="Masukkan alamat lengkapmu"
                    :class="{'p-invalid': validationErrors?.address, 'w-full': true}" />
                <small v-if="validationErrors?.address" class="p-error">{{ validationErrors.address[0] }}</small>
            </div>

            <!-- Password fields -->
            <div class="grid ">
                <div class="col-12">
                    <label for="password" class="block text-900 font-medium mb-2">Password</label>
                    <InputText id="password" type="password" v-model="store.password" 
                        placeholder="Password"
                        :class="{'p-invalid': validationErrors?.password, 'w-full': true}" />
                    <small v-if="validationErrors?.password" class="p-error">{{ validationErrors.password[0] }}</small>
                </div>
                <div class="col-12" v-if="store.password">
                    <label for="password_confirmation" class="block text-900 font-medium mb-2">Konfirmasi Password</label>
                    <InputText id="password_confirmation" type="password" 
                        v-model="store.password_confirmation" 
                        placeholder="Konfirmasi password"
                        :class="{'p-invalid': validationErrors?.password_confirmation, 'w-full': true}" />
                    <small v-if="validationErrors?.password_confirmation" class="p-error">
                        {{ validationErrors.password_confirmation[0] }}
                    </small>
                </div>
            </div>


            <!-- Register button -->
            <Button label="Daftar Sekarang" class="w-full text-xl" 
                :loading="store.loading['register']" 
                @click="handleRegister()" />
            
            <div class="text-center mt-4">
                <span class="text-600">Sudah punya akun? </span>
                <RouterLink to="/login" class="font-medium text-blue-500 hover:text-blue-700 no-underline">
                    Masuk di sini
                </RouterLink>
            </div>
        </div>
    </div>
</div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth.store';
import { ref } from 'vue';

const store = useAuthStore();
const validationErrors = ref(null);

const handleRegister = async () => {
  try {
    validationErrors.value = null;
    await store.register();
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
        text: "Sistem pengelolaan denda yang transparan dan terstruktur",
        image: "/fines-management.png"
    }
]);

</script>

<style scoped>
.feature-image {
    width: 200px;
    height: 200px;
    object-fit: contain;
}
</style>