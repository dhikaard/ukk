import { defineStore } from 'pinia';
import { ApiConstant } from '@/utils/api-constant';
import callApi from '@/utils/api-connect';

export const useHistoryStore = defineStore('history.store', {
    state: () => ({
        loading: {},
        api: ApiConstant.GET_RENT_HISTORY,
        cancelApi: ApiConstant.CANCEL_RENT,
        items: []
    }),

    actions: {
        async getHistory() {
            this.loading['history'] = true;
            try {
                const result = await callApi({
                    api: this.api
                });

                if (result.isOk) {
                    this.items = result.data;
                }
            } finally {
                this.loading['history'] = false;
            }
        },

        async cancelRent(id) {
            try {
                const result = await callApi({
                    api: {
                        ...this.cancelApi,
                        path: `rent/cancel/${id}`
                    }
                });

                if (result.isOk) {
                    await this.getHistory();
                    return result.data;
                }
                throw result;
            } catch (error) {
                throw error;
            }
        }
    }
});