import { defineStore } from 'pinia';
import { ApiConstant } from '@/utils/api-constant';
import { useToast } from 'primevue/usetoast';
import { showError } from '@/utils/toast-service';
import callApi from '@/utils/api-connect';

export const useSearchStore = defineStore('search.store', {
    state: () => ({
        loading: {},
        toast: useToast(),
        api: ApiConstant.SEARCH,
        results: {
            items: [],
            transactions: []
        }
    }),

actions: {
    async search(query) {
        if (!query || query.length < 2) {
            this.results = {
                items: [],
                transactions: []
            };
            return;
        }

        this.loading['search'] = true;
        try {
            const result = await callApi({
                api: this.api,
                params: { query }
            });

            if (result.isOk && result.data?.data) {
                this.results = result.data.data;
            }
        } catch (error) {
            showError(this.toast, error?.message);
            this.results = {
                items: [],
                transactions: []
            };
        } finally {
            this.loading['search'] = false;
        }
    }
}});