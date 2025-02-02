import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { ApiConstant } from '@/utils/api-constant';
import { useToast } from 'primevue/usetoast';
import { showSuccessRegister, showSuccessLogin } from '@/utils/toast-service';
import { useRouter } from 'vue-router';
import local  from '@/utils/local-storage';

export const useAuthStore = defineStore(
  'auth.store', {
  state: () => ({
    api: ApiConstant.LOGIN,
    registerApi: ApiConstant.REGISTER,
    toast: useToast(),
    email: '',
    password: '',
    name: '',
    role_id: 2,
    address: '',
    phone: '',
    token: '',
    password_confirmation: '',
    router: useRouter(),
    loading: {},
  }),

  actions: {
    async login() {
      this.loading['login'] = true;
      const payload = {
        api: this.api,
        body: {
          email: this.email,
          password: this.password,
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        showSuccessLogin(this.toast);
        this.token = result.data.token;
        const user = result.data.user;
        local.set('token', this.token);
        local.set('user', JSON.stringify(user));
        this.router.push({ name: 'home' });
        showSuccessLogin(toast);
        return user;
      }
      this.loading['login'] = false;
    },

    async register() {
      this.loading['register'] = true;
      try {
        const payload = {
          api: this.registerApi,
          body: {
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
            name: this.name,
            role_id: this.role_id,
            address: this.address,
            phone: String(this.phone)
          },
        };
        
        const result = await callApi(payload);
        if (result.isOk) {
          showSuccessRegister(this.toast);
          this.router.push({ name: 'login' });
          return result.data;
        }
        throw result;
      } finally {
        this.loading['register'] = false;
      }
    },
  },
});
