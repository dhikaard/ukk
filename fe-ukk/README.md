# fe-ukk

This template should help get you started developing with Vue 3 in Vite.

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur).

## Customize configuration

See [Vite Configuration Reference](https://vite.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```


import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { ApiConstant } from '@/utils/api-constant';
import { useRouter } from 'vue-router';
import local from '@/utils/local-storage';

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
        const user = result.body.user;
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