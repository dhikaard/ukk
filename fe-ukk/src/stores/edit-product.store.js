import { defineStore } from "pinia";
import callApi from "@/utils/api-connect";
import { useToast } from 'primevue/usetoast';
import { showSuccessEdit } from '@/utils/toast-service';
import { ApiConstant } from "@/api-constant";
import { useRouter } from "vue-router";
import { useManageProductStore } from '@/stores/manage-product.store';

export const useEditProductStore = defineStore({
  id: "edit-product.store",
  state: () => ({
    editApi: ApiConstant.EDIT_PRODUCT,
    brandApi: ApiConstant.GET_BRAND_FOR_ADD_PRODUCT,
    ctgrApi: ApiConstant.GET_CTGR_FOR_ADD_PRODUCT,
    toast: useToast(),
    keyword: "",
    productId: -99,
    productName: "",
    brandId: '',
    brandName: "",
    ctgrId: '',
    ctgrName: "",
    stock: 1,
    price: 0,
    fineBill: 0,
    desc: "",
    urlImg: "",
    active: 'Y',
    statusOptions: [
      { label: 'TERSEDIA', value: 'Y' },
      { label: 'TIDAK TERSEDIA', value: 'N' }
    ],    
    router: useRouter(),
    loading: {},
    keyword: "",
    dataTable: {},
    brandOptions: [],
    ctgrOptions: [],
    priceRange: [0, 2000000],
    selectedProduct: {},
  }),
  actions: {
    async editProduct(formData) {
      this.loading["editProduct"] = true;

      const result = await callApi({
        api: this.editApi,
        body: formData,
        headers: { "Content-Type": "multipart/form-data" },
      });
      if (result.isOk) {
        const product = result.body.product;
        showSuccessEdit(this.toast);
        this.loading["editProduct"] = false;
        const context = useManageProductStore();
        context.getProducts();

        return product;
      }
      this.loading["editProduct"] = false;
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
