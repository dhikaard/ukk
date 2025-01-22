import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { ApiConstant } from '@/api-constant';
import { useRouter } from 'vue-router';

export const useManageProductStore = defineStore({
  id: 'manage-product.store',
  state: () => ({
    addApi: ApiConstant.ADD_PRODUCT,
    keyword: '',
    productId: -99,
    productName: '',
    brandId: -99,
    brandName: '',
    ctgrId: -99,
    ctgrName: '',
    stock: 1,
    price: 0,
    fineBill: 0,
    desc: '',
    urlImg: '',
    router: useRouter(),
    loading: {},
    visible: false,
  }),
  actions: {
    async addProduct(formData) {
        this.loading['addProduct'] = true;
    
        const result = await callApi({
            api: this.addApi,
            body: formData,
            headers: { 'Content-Type': 'multipart/form-data' },
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
            this.loading['addProduct'] = false;
            this.visible = false;

            return product;
        }
        this.loading['addProduct'] = false;
    },
      },
  });