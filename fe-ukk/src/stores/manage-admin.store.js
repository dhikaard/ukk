import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { ApiConstant } from '@/api-constant';
import { useRouter } from 'vue-router';

export const useManageAdminStore = defineStore({
  id: 'manage-admin.store',
  state: () => ({
    adminApi: ApiConstant.GET_MANAGE_ADMIN,
    roleApi: ApiConstant.GET_ROLE_PERMISSION,
    userForAddApi: ApiConstant.GET_USER_FOR_ADD_ADMIN,
    keyword: '',
    dataTableAdmin: {},
    dataTableRoles: {},
    userOptions: {},
    selectedMember: {},
    router: useRouter(),
    loading: {},
  }),
  actions: {
    async getManageAdmin() {
      this.loading['getAdmin'] = true;
      const payload = {
        api: this.adminApi,
        body: {
          keyword: this.keyword
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const users = result.body.users.map((data) => ({
          id: data.user_id,
          userCode: data.user_code,
          address: data.address,
          phone: data.phone,
          name: data.name,
          email: data.email,
          roleName: data.role_name,
          code: data.code,
          updatedAt: data.updated_at
        }));
        this.loading['getAdmin'] = false;
        this.dataTableAdmin = users

        return users;
      }
      this.loading['getAdmin'] = false;
    },

    async getRolePermission() {
      this.loading['getRole'] = true;
      const payload = {
        api: this.roleApi,
        body: {
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const roles = result.body.roles.map((data) => ({
          id: data.role_id,
          roleName: data.role_name,
          updatedAt: data.updated_at,
          permissionName: data.permission_name,
          userCount: data.user_count
        }));
        this.loading['getRole'] = false;
        this.dataTableRoles = roles

        return roles;
      }
      this.loading['getRole'] = false;
    },

    async getUserForAddAdmin() {
      this.loading['getUser'] = true;
      const payload = {
        api: this.userForAddApi,
        body: {
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        const users = result.body.users.map((data) => ({
          id: data.user_id,
          userCode: data.user_code,
          address: data.address,
          phone: data.phone,
          name: data.name,
          email: data.email,
          roleName: data.role_name,
          code: data.code,
          updatedAt: data.updated_at
        }));
        this.loading['getUser'] = false;
        this.userOptions = users

        return users;
      }
      this.loading['getUser'] = false;
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
