import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { ApiConstant } from '@/api-constant';
import { useRouter } from 'vue-router';
import local  from '@/utils/local-storage';

export const useLoginStore = defineStore({
  id: 'login.store',
  state: () => ({
    api: ApiConstant.LOGIN,
    email: '',
    password: '',
    accessToken: '',
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
  },
});
