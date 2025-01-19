import { GeneralConstants } from '../utils/general-constant';
import moment from 'moment';
import { defineStore } from 'pinia';

export const useHomeViewStore = defineStore({
    id: 'home-view.store',
    state: () => ({
        api: '',
        dataTable: [],
        totalRecords: 0,
        rowPerPageOptions: GeneralConstants.ROW_PERPAGE_OPTION,
        defaultRows: GeneralConstants.DEFAULT_ROWS,
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
        rows: 10,
        currentPage: 0,
        selectedCategory: 0,
        selectedFilters: [],
        rangeValues: [1, 100000],
    }),
    actions: {
        async getProducts() {
            this.products = [
                { id: "1", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "2", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "3", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "4", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "5", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "6", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "7", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "8", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "9", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "10", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "11", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
            ];
            this.totalRecords = this.products.length;
            this.updatePaginatedProducts();
        },
        updatePaginatedProducts() {
            const start = this.currentPage * this.rows;
            const end = start + this.rows;
            this.product = this.products.slice(start, end);
        },
        onPageChange(event) {
            this.currentPage = event.page;
            this.updatePaginatedProducts();
        },
        async getList() {
            this.loading['table'] = true;
            const payload = {
                api: this.api,
                body: {
                    dateFrom: this.dateFrom,
                    dateTo: this.dateTo,
                    keyword: this.keyword,
                    limit: this.limit,
                    offset: this.offset,
                }
            };

            const result = await callApiTask(payload);
            if (result.isOk) {
                const unmigratedVisitList = result.body.unmigratedVisitList.map((data) => ({
                    docDate: data.docDate,
                    noAdmission: data.noAdmission,
                    startDate: data.startDate,
                    finishDate: data.finishDate,
                    patient: data.patient,
                    rmId: data.rmId,
                    jnsPelayananName: data.jnsPelayananName,
                    jnsPembayaranName: data.jnsPembayaranName,
                    totalBill: data.totalBill,
                    errorMessage: data.errorMessage,
                    statusDoc: data.statusDoc
                }));
                this.dataTable = unmigratedVisitList;
                this.countList();
                this.loading['table'] = false;
                return unmigratedVisitList;
            }
        },
    }
});
