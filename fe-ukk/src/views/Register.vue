<template>
    <div class="flex">
        <div class="surface-section w-full md:w-6 p-6 md:p-8">
            <div class="mb-5">
                <div class="flex align-items-center gap-2 mb-3">
                    <img src="../assets/logo.jpg" alt="Image" height="50">
                    <span class="philosopher-regular text-2xl">AW KAMERA</span>
                </div>
                <div class="text-2xl font-semibold mb-2 mt-2">Yuk, Daftarin Akun Kamu!🤗</div>
                <span class="text-gray-500 mb-6 mr-1">Udah punya akun?</span>
                <a class="font-medium no-underline text-blue-500 cursor-pointer hover:underline" @click="goToLogin">Yuk
                    masuk!</a>
            </div>
            <div>
                <div class="flex mb-3 gap-4">
                    <div class="w-full">
                        <label for="name" class="block text-900 font-medium mb-2">Nama Lengkap</label>
                        <InputText id="name" v-model="context.name" type="text" placeholder="Alexander Agung"
                            class="w-full" />
                        <label for="email" class="block text-900 font-medium mt-3 mb-2">Email</label>
                        <InputText id="email" v-model="context.email" type="email" placeholder="example@gmail.com"
                            class="w-full" />
                    </div>

                </div>
                <div class="flex mb-3 gap-4">
                    <div class="w-full">
                        <label for="name" class="block text-900 font-medium mb-2">Kata Sandi</label>
                        <InputText id="name" v-model="context.password" type="password" placeholder="••••••••"
                            class="w-full" />
                        <label for="password" class="block text-900 font-medium mt-3 mb-2">Konfirmasi Kata Sandi</label>
                        <InputText id="password" v-model="context.passwordConfirm" type="password"
                            placeholder="••••••••" class="w-full" />
                    </div>
                </div>
                <div class="flex mb-3 gap-4">
                    <div class="w-full">
                        <label for="address" class="block text-900 font-medium mb-2">Alamat</label>
                        <InputText id="address" v-model="context.address" type="text"
                            placeholder="Jl. Cinde Dalam I No. 24" class="w-full" />
                    </div>
                    <div class="w-full">
                        <label for="phone" class="block text-900 font-medium mb-2">Telepon</label>
                        <InputText id="phone" v-model="context.phone" type="number" placeholder="08123456789"
                            class="w-full no-spinner" />
                    </div>
                </div>
                <Button label="Daftar" :loading="context.loading['sendOtp']" @click="register" icon="pi pi-sign-in"
                    class="w-full mt-3 p-3" :disabled="context.isRegisterDisabled"></Button>
            </div>
        </div>
        <div class="hidden md:block w-6 bg-no-repeat bg-cover"
            style="background-image: url('http://semar.taskhub.id:4444/images/blocks/signin/signin.jpg')"></div>
    </div>
    <VerifOtp v-if="showOtpDialog" v-model:visible="showOtpDialog" @otp-verified="completeRegistration" />
</template>


<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { useAuthStore } from '@/stores/auth.store';
import { useRouter } from 'vue-router';
import VerifOtp from '@/components/VerifOtp.vue';

const router = useRouter();

const goToLogin = () => {
    router.push({ name: 'login' });
}

const context = useAuthStore();
const showOtpDialog = ref(false);

const register = async () => {
    const result = await context.sendOtp(context.email);
    if (result.isOk) {
        showOtpDialog.value = true;
    }
};

const completeRegistration = async () => {
    await context.register();
    router.push({ name: 'login' });
};
</script>