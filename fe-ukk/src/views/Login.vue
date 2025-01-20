<template>
    <div class="flex">
        <div class="surface-section w-full md:w-6 p-6 md:p-8">
            <div class="mb-5">
                <div class="flex align-items-center gap-2 mb-3">
                    <img src="../assets/logo.jpg" alt="Image" height="50">
                    <span class="philosopher-regular text-2xl">AW KAMERA</span>
                </div>
                <div class="text-900 text-3xl font-medium mb-3">Selamat Datang!</div>
                <span class="text-600 font-medium mr-2">Udah punya akun belum?</span>
                <a class="font-medium no-underline text-blue-500 cursor-pointer hover:underline"
                    @click="goToRegister">Yuk buat!</a>
            </div>
            <div>
                <div class="mb-3">
                    <label for="email" class="block text-900 font-medium mb-2">Email</label>
                    <InputText id="email" v-model="context.email" type="text" placeholder="Alamat Email"
                        :invalid="loginFailed" class="w-full" />
                    <small v-if="loginFailed" class="p-error">
                        {{ 'Email Anda tidak sesuai.' }}
                    </small>
                </div>
                <div class="mb-3">
                    <label for="password" class="block text-900 font-medium mb-2">Password</label>
                    <InputText id="name" v-model="context.password" type="password" placeholder="Password"
                        class="w-full" :invalid="loginFailed" />
                    <small v-if="loginFailed" class="p-error">
                        {{ 'Password Anda tidak sesuai.' }}
                    </small>
                </div>
                <div class="flex align-items-center justify-content-between mb-6">
                    <div class="flex align-items-center">
                        <Checkbox inputId="rememberme" :binary="true" v-model="checked" class="mr-2"></Checkbox>
                        <label for="rememberme" class="cursor-pointer">Ingat saya</label>
                    </div>
                    <a class="font-medium no-underline ml-2 text-blue-500 text-right cursor-pointer hover:underline">Lupa
                        password?</a>
                </div>
                <Button label="Masuk" :disabled="context.isLoginDisabled" :loading="context.loading['login']"
                    @click="handleLogin" icon="pi pi-sign-in" class="w-full p-3"></Button>
            </div>
        </div>
        <div class="hidden md:block w-6 bg-no-repeat bg-cover"
            style="background-image: url('http://semar.taskhub.id:4444/images/blocks/signin/signin.jpg')"></div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useAuthStore } from '@/stores/auth.store';
import { useRouter } from 'vue-router';

const router = useRouter();
const context = useAuthStore();
const checked = ref(false);
const loginFailed = ref(false);

const goToRegister = () => {
    router.push({ name: 'register' });
};

const handleLogin = async () => {
    loginFailed.value = false;

    await context.login();

    if (!context.accessToken) {
        loginFailed.value = true;
    }
};

watch([() => context.email, () => context.password], () => {
    loginFailed.value = false;
});
</script>
