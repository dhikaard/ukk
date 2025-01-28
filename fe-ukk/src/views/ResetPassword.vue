<template>
    <div class="flex">
        <div class="surface-section w-full md:w-6 p-6 md:p-8">
            <span
                class="mb-3 w-3rem h-3rem border-circle flex justify-content-center align-items-center bg-blue-100 mr-3">
                <i class="bi bi-fingerprint text-blue-700 text-2xl"></i>
            </span>
            <div class="text-2xl font-semibold mb-2 mt-2">Lupa Kata Sandi?</div>
            <p class="text-gray-500 mb-6">Jangan khawatir, kami akan mengirimkan instruksi reset kepada Anda.</p>

            <div v-if="!token" class="mt-5">
                <FloatLabel class="mb-3">
                    <InputText id="email" v-model="email" class="w-full" />
                    <label for="email">Email</label>
                </FloatLabel>
                <Button label="Setel Ulang" icon="bi bi-envelope-arrow-up" class="w-full" @click="sendResetLink"
                    :loading="context.loading['sendResetLink']"></Button>
                <div class="mt-3 text-center">
                    <Button label="Kembali ke login" icon="pi pi-arrow-left" class="p-button-text"
                        @click="goToLogin"></Button>
                </div>
            </div>

            <div v-else class="mt-5">
                <div class="w-full"></div>
                <label for="password" class="block text-900 font-medium mb-2">Kata Sandi</label>
                <Password id="password" v-model="password" type="password" placeholder="••••••••" toggleMask required
                    class="mb-3" style="width: 100%;" />
                <label for="passwordConfirmation" class="block text-900 font-medium mb-2">Konfirmasi Kata Sandi</label>
                <Password id="passwordConfirmation" v-model="passwordConfirmation" toggleMask :feedback="false" required
                    placeholder="••••••••" class="mb-3" style="width: 100%;" />
                <Button label="Tetapkan Kata Sandi" icon="pi pi-lock" class="w-full p-3"
                    @click="resetPassword"></Button>
            </div>
            <div class="hidden md:block w-6 bg-no-repeat bg-cover"
                style="background-image: url('http://semar.taskhub.id:4444/images/blocks/signin/signin.jpg')"></div>
        </div>
    </div>
    <DialogConfirm v-if="context.showDialogConfirm" v-model:visible="context.showDialogConfirm" :header="context.confirmHeader"
        :message="context.confirmMessage" :acceptLabel="'OK'" :onAccept="closeDialog"
        :onReject="closeDialog" :icon="context.confirmIcon" :iconColor="context.confirmIconColor"
        :bgColor="context.confirmBgColor" :acceptColor="context.confirmAcceptColor" />
</template>

<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth.store';
import DialogConfirm from '@/components/DialogConfirm.vue';

const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const route = useRoute();
const token = route.query.token || null;
const context = useAuthStore();

const router = useRouter();

const goToLogin = () => {
    router.push({ name: 'login' });
};

if (route.query.email) {
    email.value = route.query.email;
}

const sendResetLink = async () => {
    await context.sendResetLink(email.value);
};

const resetPassword = () => {
    context.resetPassword(token, email.value, password.value, passwordConfirmation.value);
};

const closeDialog = () => {
    context.showDialogConfirm = false;
};
</script>