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
        selectedData: null,
        categoryOptions: [
            { name: 'New York', code: 'NY' },
            { name: 'Rome', code: 'RM' },
            { name: 'London', code: 'LDN' },
            { name: 'Istanbul', code: 'IST' },
            { name: 'Paris', code: 'PRS' }
        ],
        sortBy: 'name-asc',
        priceSort: 'low-high',
        priceOrder: null,
        priceOrderOptions: [
            {name: 'A-Z', code : 'ASC'},
            {name: 'Z-A', code : 'DESC'},
            {name: 'Terbaru', code : 'create_datetime DESC'},
            {name: 'Termurah ke Termahal', code : 'amount ASC'},
            {name: 'Termahal ke Termurah', code : 'amount DESC'}
        ]
        
    }),
    actions: {
        toggleCategory(category) {
            const index = this.selectedCategory.indexOf(category);
            if (index === -1) {
              this.selectedCategory.push(category);
            } else {
              this.selectedCategory.splice(index, 1);
            }
          },      
        setSelectedData(data) {
            this.selectedData = data;
        },
        async getProducts() {
            this.products = [
                { id: "1", name: "Bamboo Watch", image: "bamboo-watch.jpg", price: 65, category: "Accessories", quantity: 24, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "2", name: "Blue T-Shirt", image: "bamboo-watch.jpg.", price: 35, category: "Clothing", quantity: 14, inventoryStatus: "INSTOCK", rating: 4 },
                { id: "3", name: "Gaming Mouse", image: "bamboo-watch.jpg", price: 45, category: "Electronics", quantity: 30, inventoryStatus: "INSTOCK", rating: 5 },
                { id: "4", name: "Leather Wallet", image: "bamboo-watch.jpg", price: 55, category: "Accessories", quantity: 10, inventoryStatus: "INSTOCK", rating: 4 },
            ];
            this.sortProducts();
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
                this.loading['table'] = false;
            }
        },
        sortProducts() {
            if (this.sortBy === 'name-asc') {
                this.products.sort((a, b) => a.name.localeCompare(b.name));
            } else if (this.sortBy === 'name-desc') {
                this.products.sort((a, b) => b.name.localeCompare(a.name));
            }
            
            if (this.priceSort === 'low-high') {
                this.products.sort((a, b) => a.price - b.price);
            } else if (this.priceSort === 'high-low') {
                this.products.sort((a, b) => b.price - a.price);
            }
            this.updatePaginatedProducts();
        }
    }
});
