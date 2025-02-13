import { GeneralConstants } from '../utils/general-constant';
import moment from 'moment';
import { defineStore } from 'pinia';
import { ApiConstant } from '@/utils/api-constant';
import callApi from '@/utils/api-connect';

export const useRentViewStore = defineStore(
    'rent-view.store', {
    state: () => ({
        api: ApiConstant.GET_ITEMS,
        detailApi: ApiConstant.GET_ITEMS_DETAIL,
        dataTable: [],
        totalRecords: 0,
        loading: {},
        offset: 0,
        limit: GeneralConstants.DEFAULT_ROWS,
        dates: [moment().subtract(30, 'days')._d, moment()._d],
        dateFrom: moment().subtract(30, 'days').format('YYYYMMDD'),
        dateTo: moment().format('YYYYMMDD'),
        keyword: '',
        products: [],
        product: [],
        layout: "grid",
        rows: GeneralConstants.DEFAULT_ROWS,
        currentPage: 0,
        category: 0,
        selectedFilters: [],
        rangeValues: [1, 100000],
        categoryOptions: [],
        orderBy: null,
        orderByOptions: [
            { name: 'A-Z', code: 'items_name ASC' },
            { name: 'Z-A', code: 'items_name DESC' },
            { name: 'Terbaru', code: 'created_at DESC' },
            { name: 'Termurah', code: 'price ASC' },
            { name: 'Termahal', code: 'price DESC' }
        ],
    }),
    actions: {
        async getCategories() {
            try {
                const payload = {
                    api: ApiConstant.GET_CATEGORIES
                };

                const result = await callApi(payload);
                if (result.isOk) {
                    this.categoryOptions = result.data;
                }
            } catch (error) {
                console.error('Failed to fetch categories:', error);
            }
        },
        async getProducts() {
            this.loading['products'] = true;
                const payload = {
                    api: this.api,
                    params: {
                        limit: this.rows,
                        page: this.currentPage + 1,
                        keyword: this.keyword,
                        category: this.category ? this.category.map(cat => cat.code) : null,
                        sort: this.orderBy?.code
                    }
                };

                const result = await callApi(payload);
                if (result.isOk) {
                    this.loading['products'] = false;
                    this.products = result.data.data.map(item => ({
                        id: item.items_id,
                        name: item.items_name,
                        code: item.items_code,
                        image: item.image,
                        price: item.price,
                        category: item.category.ctgr_items_name,
                        quantity: item.stock + (item.item_stock?.reduce((sum, stock) => sum + stock.stock, 0) || 0),
                        inventoryStatus: (item.stock + (item.item_stock?.reduce((sum, stock) => sum + stock.stock, 0) || 0)) > 0 ? 'INSTOCK' : 'OUTOFSTOCK'
                    }));
                    this.totalRecords = result.data.total || 0;
                    this.updatePaginatedProducts();
            }
        },
        updatePaginatedProducts() {
            const start = this.currentPage * this.rows;
            const end = start + this.rows;
            this.product = this.products.slice(start, end);
        },
        async onPageChange(event) {
            this.currentPage = event.page;
            await this.getProducts();
        },
        async applyFilter() {
            this.currentPage = 0;
            await this.getProducts();
        },
        resetFilter() {
            this.keyword = '';
            this.category = null;
            this.orderBy = null;
            this.selectedFilters = [];
            this.getProducts();
        },
        async getProductDetail(code) {
            this.loading['productDetail'] = true;
                const payload = {
                    api: {
                        ...this.detailApi,
                        path: `items/${code}`
                    }
                };
        
                const result = await callApi(payload);
                if (result.isOk) {
                    this.loading['productDetail'] = false;
                    return {
                        id: result.data.items_id,
                        code: result.data.items_code,
                        name: result.data.items_name,
                        image: result.data.image,
                        price: result.data.price,
                        category: result.data.category.ctgr_items_name,
                        quantity: result.data.stock + (result.data.item_stock?.reduce((sum, item) => sum + item.stock, 0) || 0),
                        description: result.data.desc,
                        sizes: result.data.item_stock || [],
                        hasSize: result.data.item_stock?.length > 0,
                        inventoryStatus: (result.data.stock + (result.data.item_stock?.reduce((sum, item) => sum + item.stock, 0) || 0)) > 0 ? 'INSTOCK' : 'OUTOFSTOCK'
                    };
                }

        }
    }
});
