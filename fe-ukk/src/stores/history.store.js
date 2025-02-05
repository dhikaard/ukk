import { defineStore } from 'pinia';
import { ApiConstant } from '@/utils/api-constant';
import { useToast } from 'primevue/usetoast';
import { showSuccessCancelOrder, showError } from '@/utils/toast-service';
import callApi from '@/utils/api-connect';

export const useHistoryStore = defineStore('history.store', {
    state: () => ({
        loading: {},
        toast: useToast(),
        api: ApiConstant.GET_RENT_HISTORY,
        cancelApi: ApiConstant.CANCEL_RENT,
        items: [],
        selectedStatus: null,
        statusOptions: [
            { label: 'Semua Status', value: null },
            { label: 'Pending', value: 'P' },
            { label: 'Sedang Disewa', value: 'D' },
            { label: 'Selesai', value: 'S' },
            { label: 'Dibatalkan', value: 'B' }
        ]
    }),

    actions: {
        async getHistory() {
            this.loading['history'] = true;
            try {
                const result = await callApi({
                    api: this.api,
                    params: {
                        status: this.selectedStatus
                    }
                });

                if (result.isOk) {
                    this.items = result.data;
                }
            } finally {
                this.loading['history'] = false;
            }
        },

        async applyFilter() {
            await this.getHistory();
        },

        resetFilter() {
            this.selectedStatus = null;
            this.getHistory();
        },

        async cancelRent(id) {
            this.loading['cancelOrder'] = true;
            try {
                const result = await callApi({
                    api: this.cancelApi,
                    body: { id }
                });

                if (result.isOk) {
                    showSuccessCancelOrder(this.toast);
                    await this.getHistory();
                    return result.data;
                }
                throw result;
            } catch (error) {
                showError(this.toast, error?.message);
                throw error;
            } finally {
                this.loading['cancelOrder'] = false;
            }
        },

        getDetailForRentAgain(item) {
            // Get first item from details array
            const detail = item.details[0];
            if (!detail) return null;

            // Get product info from first item
            const product = detail.item;

            return {
                code: product.items_code,
                id: product.items_id,
                slug: product.items_name
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '')
            };
        }
    },

    getters: {
        getStatusLabel: () => (status) => {
            const labels = {
                'P': 'Pending',
                'D': 'Sedang Disewa',
                'S': 'Selesai',
                'B': 'Dibatalkan',
                'T': 'Ditolak'
            };
            return labels[status] || status;
        },

        getStatusSeverity: () => (status) => {
            const severities = {
                'P': 'warning',
                'D': 'info',
                'S': 'success',
                'B': 'danger',
                'T': 'danger'
            };
            return severities[status] || 'info';
        }
    }
});