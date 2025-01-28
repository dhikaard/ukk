<template>
    <Dialog closeOnEscape v-model:visible="visible5" :modal="true" :closable="true" :showHeader="true"
        :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        :style="{ width: '40vw', maxWidth: '90vw', minWidth: '380px', height: '24rem' }">
        <template #header>
            <div class="flex w-full justify-content-between align-items-center">
                <div class="flex align-items-center">
                    <span
                        class="w-3rem h-3rem border-circle flex justify-content-center align-items-center bg-blue-100 mr-3">
                        <i class="pi pi-lock text-blue-700 text-2xl"></i>
                    </span>
                </div>
            </div>
        </template>
        <section class="flex flex-column w-full">
            <Divider class="w-full"></Divider>
            <div class="flex flex-column align-items-center">
                <div class="font-bold text-xl mb-2">Verifikasi Email</div>
                <p class="text-color-secondary text-center block mb-5">Kami sudah mengirimkan kode OTP ke email kamu,
                    coba cek
                    ya!</p>
                <InputOtp v-model="otp" :length="6" style="gap: 0">
                    <template #default="{ attrs, events, index }">
                        <input type="text" v-bind="attrs" v-on="events" class="custom-otp-input" />
                        <div v-if="index === 3" class="px-3">
                            <i class="pi pi-minus" />
                        </div>
                    </template>
                </InputOtp>
                <div class="flex justify-content-between mt-5 align-self-stretch gap-2">
                    <Button icon="bi bi-envelope-arrow-up" label="Kirim Ulang" class="w-full" @click="resendOtp"
                        :loading="context.loading['sendOtp']" outlined></Button>
                    <Button icon="pi pi-check" label="Verifikasi" @click="submitOtp"
                        :loading="context.loading['verifyOtp']" class="w-full"></Button>
                </div>
            </div>
        </section>
    </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth.store';
import { showSuccessSendOtp } from '@/utils/toast-service';

const emit = defineEmits(['update:visible', 'otp-verified']);
const context = useAuthStore();
const otp = ref('');

const resendOtp = async () => {
    const result = await context.sendOtp(context.email);
    if (result.isOk) {
        showSuccessSendOtp(context.toast)
    }
};

const submitOtp = async () => {
    const result = await context.verifyOtp(context.email, otp.value);
    if (result.isOk) {
        emit('update:visible', false);
        emit('otp-verified');
    }
};
</script>

<style scoped>
.custom-otp-input {
    width: 48px;
    height: 48px;
    font-size: 24px;
    appearance: none;
    text-align: center;
    transition: all 0.2s;
    border-radius: 0;
    border: 1px solid var(--surface-400);
    background: transparent;
    outline-offset: -2px;
    outline-color: transparent;
    border-right: 0 none;
    transition: outline-color 0.3s;
    color: var(--text-color);
}

.custom-otp-input:focus {
    outline: 2px solid var(--primary-color);
}

.custom-otp-input:first-child,
.custom-otp-input:nth-child(5) {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

.custom-otp-input:nth-child(3),
.custom-otp-input:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    border-right-width: 1px;
    border-right-style: solid;
    border-color: var(--surface-400);
}
</style>
