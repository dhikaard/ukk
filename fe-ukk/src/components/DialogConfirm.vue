<template>
    <Dialog :lazy="true" v-model="context.visible" :modal="true" :closable="false" :showHeader="false" dismissableMask
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        :style="{ width: '20vw', maxWidth: '90vw', minWidth: '400px' }">
        <div class="flex align-items-center justify-content-center p-4">
            <span :class="['w-3rem h-3rem border-circle flex justify-content-center align-items-center mr-2', bgColor]">
                <i :class="[icon, iconColor, 'text-2xl']"></i>
            </span>
            <span class="font-bold text-2xl">{{ header }}</span>
        </div>
        <p class="mb-0 text-center">{{ message }}</p>
        <template #footer>
            <div class="pt-2 flex border-top-1 surface-border gap-2 w-full">
                <Button class="p-button-text w-full p-button-secondary" icon="pi pi-times" label="Batal" outlined
                    @click="rejectCallback"></Button>
                <Button :class="acceptColor" :label="acceptLabel" :icon="acceptIcon"
                    @click="acceptCallback" :loading="acceptLoading"></Button>
            </div>
        </template>
    </Dialog>
</template>

<script>
export default {
    props: {
        acceptLabel: {
            type: String,
            default: 'Save'
        },
        acceptIcon: {
            type: String,
            default: 'pi pi-check'
        },
        acceptColor: {
            type: String,
            default: 'w-full p-button-warning'
        },
        acceptLoading: {
            type: Boolean,
            default: false
        },
        onAccept: {
            type: Function,
            required: true
        },
        onReject: {
            type: Function,
            required: true
        },
        header: {
            type: String,
            default: ''
        },
        message: {
            type: String,
            default: ''
        },
        icon: {
            type: String,
            default: 'bi bi-exclamation-triangle'
        },
        iconColor: {
            type: String,
            default: 'text-orange-700'
        },
        bgColor: {
            type: String,
            default: 'bg-orange-100'
        }
    },
    data() {
        return {
            context: {
                visible: false
            }
        };
    },
    methods: {
        acceptCallback() {
            this.onAccept();
        },
        rejectCallback() {
            this.context.visible = false;
            this.onReject();
        }
    }
};
</script>