import { defineStore } from "pinia";
import { GeneralConstants } from '@/utils/general-constants';
import { useToast } from 'primevue/usetoast';
import { showSuccessRemove } from '@/utils/toast-service';
import callApi from "@/utils/api-connect";
import { ApiConstant } from "@/api-constant";

export const useProductRentStore = defineStore({
  id: "product-rent.store",
  state: () => ({
    getApi: ApiConstant.GET_PRODUCTS,
    brandApi: ApiConstant.GET_BRAND_FOR_ADD_PRODUCT,
    ctgrApi: ApiConstant.GET_CTGR_FOR_ADD_PRODUCT,
    toast: useToast(),
    keyword: "",
    loading: {},
    dataTable: [],
    totalRecords: 0,
    rowPerPageOptions: GeneralConstants.ROW_PERPAGE_OPTION,
    defaultRows: GeneralConstants.DEFAULT_ROWS,
    offset: 0,
    limit: GeneralConstants.DEFAULT_ROWS,
    brandOptions: [],
    ctgrOptions: [],
    priceRange: [0, 2000000],
    selectedProduct: [],
  }),
  actions: {
    onPage(event) {
      this.offset = event.page * event.rows;
      this.limit = event.rows;
      this.getProducts();
  },
    async getProducts() {
      this.loading["getProducts"] = true;
      const payload = {
        api: this.getApi,
        body: {
          keyword: this.keyword,
          ctgrId: this.ctgrId,
          brandId: this.brandId,
          priceRange: this.priceRange,
          limit: this.limit,
          offset:this.offset
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const products = result.body.products.map((data) => ({
          id: data.product_id,
          productName: data.product_name,
          productCode: data.product_code,
          brandId: data.product_brand_id,
          brandName: data.brand_name,
          ctgrId: data.ctgr_product_id,
          ctgrName: data.ctgr_product_name,
          price: data.price,
          stock: data.stock,
          fineBill: data.fine_bill,
          createdAt: data.created_at,
          updatedAt: data.updated_at,
          active: data.active,
          desc: data.desc,
          urlImg: data.url_img,
        }));
        this.totalRecords = result.body.totalRecords;
        this.loading["getProducts"] = false;
        this.dataTable = products;

        return products;
      }
      this.loading["getProducts"] = false;
    },
    async getBrand() {
      this.loading["getBrand"] = true;
      const payload = {
        api: this.brandApi,
        body: {},
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const brands = result.body.brands.map((data) => ({
          id: data.product_brand_id,
          brandName: data.brand_name,
        }));
        this.loading["getBrand"] = false;
        this.brandOptions = brands;

        return brands;
      }
      this.loading["getBrand"] = false;
    },
    async getCtgr() {
      this.loading["getCtgr"] = true;
      const payload = {
        api: this.ctgrApi,
        body: {},
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const categories = result.body.categories.map((data) => ({
          id: data.ctgr_product_id,
          ctgrName: data.ctgr_product_name
        }));
        this.loading["getCtgr"] = false;
        this.ctgrOptions = categories;

        return categories;
      }
      this.loading["getCtgr"] = false;
    },
  },
});
