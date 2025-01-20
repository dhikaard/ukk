import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { ApiConstant } from '@/api-constant';
import { useRouter } from 'vue-router';
import local from '@/utils/local-storage';

export const useAuthStore = defineStore({
  id: 'auth.store',
  state: () => ({
    loginApi: ApiConstant.LOGIN,
    registApi: ApiConstant.REGISTER,
    name: '',
    email: '',
    password: '',
    passwordConfirm: '',
    address: '',
    phone: '',
    accessToken: '',
    router: useRouter(),
    loading: {},
  }),
  actions: {
    async login() {
      this.loading['login'] = true;
      const payload = {
        api: this.loginApi,
        body: {
          email: this.email,
          password: this.password,
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        this.accessToken = result.body.access_token;
        const user = result.body.user.map((data) => ({
          id: data.user_id,
          userCode: data.user_code,
          address: data.address,
          phone: data.phone,
          name: data.name,
          email: data.email,
          roleName: data.role_name,
          code: data.code
        }));
        local.set('token', this.accessToken);
        local.set('user', JSON.stringify(user));
        
        this.router.push({ name: 'home' });
        this.loading['login'] = false;

        return user;
      }
      this.loading['login'] = false;
    },

    async register() {
      this.loading['regist'] = true;
      const payload = {
        api: this.registApi,
        body: {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.passwordConfirm,
          address: this.address,
          phone: this.phone,
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const user = result.body.user.map((data) => ({
          id: data.user_id,
          userCode: data.user_code,
          address: data.address,
          phone: data.phone,
          name: data.name,
          email: data.email,
          roleName: data.role_name,
          code: data.code
        }));
        
        this.router.push({ name: 'login' });
        this.loading['regist'] = false;

        return user;
      }
      this.loading['regist'] = false;
    },
  },
  getters: {
    isRegisterDisabled(state) {
        return !state.name || !state.email || !state.password || !state.passwordConfirm || !state.address || !state.phone;
    },
    isLoginDisabled(state) {
      return !state.email || !state.password;
  },
  }
});
