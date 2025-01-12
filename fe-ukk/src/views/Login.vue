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
                <a class="font-medium no-underline text-blue-500 cursor-pointer hover:underline">Yuk buat!</a>
            </div>
            <div>
                <label for="email2" class="block text-900 font-medium mb-2">Email</label>
                <InputText id="email2" type="text" placeholder="Alamat Email" class="w-full mb-3 p-3" />
            
                <label for="password2" class="block text-900 font-medium mb-2">Password</label>
                <InputText id="password2" type="password" placeholder="Password" class="w-full mb-3 p-3" />
            
                <div class="flex align-items-center justify-content-between mb-6">
                    <div class="flex align-items-center">
                        <Checkbox id="rememberme2" :binary="true" v-model="checked2" class="mr-2"></Checkbox>
                        <label for="rememberme2">Ingat saya</label>
                    </div>
                    <a class="font-medium no-underline ml-2 text-blue-500 text-right cursor-pointer hover:underline">Lupa password?</a>
                </div>
            
                <Button label="Masuk" icon="pi pi-sign-in" class="w-full p-3"></Button>
            </div>
        </div>
        <div class="hidden md:block w-6 bg-no-repeat bg-cover" style="background-image: url('http://semar.taskhub.id:4444/images/blocks/signin/signin.jpg')"></div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const checked2 = ref(false);
</script>
