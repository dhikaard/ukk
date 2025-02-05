import { defineStore } from 'pinia';
import { ApiConstant } from '@/utils/api-constant';
import callApi from '@/utils/api-connect';

export const useNotificationStore = defineStore('notification.store', {
    state: () => ({
        loading: false,
        items: []
    }),

    getters: {
        overdueCount: (state) => state.items.length
    },

    actions: {
        async getOverdueRentals() {
            this.loading = true;
            try {
                const payload = {
                    api: ApiConstant.GET_RENT_HISTORY
                };
                const result = await callApi(payload);
                if (result.isOk) {
                    const now = new Date();
                    this.items = result.data.filter(item => 
                        item.status === 'D' && 
                        new Date(item.rent_end_date) < now
                    );
                } else  {
                    this.loading = false
                }
            } finally {
                this.loading = false;
            }
        }
    }
});