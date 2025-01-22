import { defineStore } from "pinia";
import callApi from "@/utils/api-connect";
import { ApiConstant } from "@/api-constant";
import { useRouter } from "vue-router";

export const useManageProductStore = defineStore({
  id: "manage-product.store",
  state: () => ({
    addApi: ApiConstant.ADD_PRODUCT,
    getApi: ApiConstant.GET_PRODUCTS,
    brandApi: ApiConstant.GET_BRAND_FOR_ADD_PRODUCT,
    ctgrApi: ApiConstant.GET_CTGR_FOR_ADD_PRODUCT,
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
    router: useRouter(),
    loading: {},
    visible: false,
    keyword: "",
    dataTable: {},
    brandOptions: {},
    ctgrOptions: {},
  }),
  actions: {
    async addProduct(formData) {
      this.loading["addProduct"] = true;

      const result = await callApi({
        api: this.addApi,
        body: formData,
        headers: { "Content-Type": "multipart/form-data" },
      });

      if (result.isOk) {
        const product = result.body.product.map((data) => ({
          id: data.id,
          active: data.active,
          createdAt: data.createdAt,
          ctgrProductId: data.ctgr_product_id,
          desc: data.desc,
          productBrandId: data.product_brand_id,
          productCode: data.product_code,
          productName: data.product_name,
          stock: data.stock,
          urlImg: data.url_img,
          updatedAt: data.updated_at,
        }));
        this.loading["addProduct"] = false;
        this.visible = false;
        this.getProducts();

        return product;
      }
      this.loading["addProduct"] = false;
    },
    async getProducts() {
      this.loading["getProducts"] = true;
      const payload = {
        api: this.getApi,
        body: {
          keyword: this.keyword,
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
          urlImg: data.url_img,
        }));
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
