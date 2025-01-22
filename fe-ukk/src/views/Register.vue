<template>
    <div class="flex">
        <div class="surface-section w-full md:w-6 p-6 md:p-8">
            <div class="mb-5">
                <div class="flex align-items-center gap-2 mb-3">
                    <img src="../assets/logo.jpg" alt="Image" height="50">
                    <span class="philosopher-regular text-2xl">AW KAMERA</span>
                </div>
                <div class="text-900 text-3xl font-medium mb-3">Yuk, Daftarin Akun Kamu!🤗</div>
                <span class="text-600 font-medium mr-2">Udah punya akun?</span>
                <a class="font-medium no-underline text-blue-500 cursor-pointer hover:underline" @click="goToLogin">Yuk
                    masuk!</a>
            </div>
            <div>
                <div class="flex mb-3 gap-4">
                    <div class="w-full">
                        <label for="name" class="block text-900 font-medium mb-2">Nama</label>
                        <InputText id="name" v-model="context.name" type="text" placeholder="Nama Lengkap"
                            class="w-full" />
                        <label for="email" class="block text-900 font-medium mt-3 mb-2">Email</label>
                        <InputText id="email" v-model="context.email" type="email" placeholder="Alamat Email"
                            class="w-full" />
                    </div>

                </div>
                <div class="flex mb-3 gap-4">
                    <div class="w-full">
                        <label for="name" class="block text-900 font-medium mb-2">Password</label>
                        <InputText id="name" v-model="context.password" type="password" placeholder="Password"
                            class="w-full" />
                        <label for="password" class="block text-900 font-medium mt-3 mb-2">Konfirmasi Password</label>
                        <InputText id="password" v-model="context.passwordConfirm" type="password"
                            placeholder="Konfirmasi Password" class="w-full" />
                    </div>
                </div>
                <div class="flex mb-3 gap-4">
                    <div class="w-full">
                        <label for="address" class="block text-900 font-medium mb-2">Alamat</label>
                        <InputText id="address" v-model="context.address" type="text" placeholder="Tempat Tinggal"
                            class="w-full" />
                    </div>
                    <div class="w-full">
                        <label for="phone" class="block text-900 font-medium mb-2">Telepon</label>
                        <InputText id="phone" v-model="context.phone" type="number" placeholder="Nomor Telepon"
                            class="w-full no-spinner" />
                    </div>
                </div>
                <Button label="Daftar" :loading="context.loading['regist']" @click="register" icon="pi pi-sign-in"
                    class="w-full mt-3 p-3" :disabled="context.isRegisterDisabled"></Button>
            </div>
        </div>
        <div class="hidden md:block w-6 bg-no-repeat bg-cover"
            style="background-image: url('http://semar.taskhub.id:4444/images/blocks/signin/signin.jpg')"></div>
    </div>
</template>


<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth.store';
import { useRouter } from 'vue-router';

const router = useRouter();

const goToLogin = () => {
    router.push({ name: 'login' });
}

const context = useAuthStore();

const register = async () => {
    await context.register();
};
</script>

<style scoped>
.no-spinner::-webkit-inner-spin-button,
.no-spinner::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.no-spinner {
    -moz-appearance: textfield; /* For Firefox */
}
</style>