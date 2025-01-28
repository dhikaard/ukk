import { defineStore } from 'pinia';
import callApi from '@/utils/api-connect';
import { useToast } from 'primevue/usetoast';
import { showSuccessRegister, showSuccessResetPassword, showErrorOtp, showSuccessSendOtp } from '@/utils/toast-service';
import { ApiConstant } from '@/api-constant';
import { useRouter } from 'vue-router';
import local from '@/utils/local-storage';

export const useAuthStore = defineStore({
  id: 'auth.store',
  state: () => ({
    loginApi: ApiConstant.LOGIN,
    registApi: ApiConstant.REGISTER,
    passwordEmailApi: ApiConstant.PASSWORD_EMAIL,
    passwordResetApi: ApiConstant.PASSWORD_RESET,
    sendOtpApi: ApiConstant.SEND_OTP,
    verifyOtpApi: ApiConstant.VERIFY_OTP,
    toast: useToast(),
    name: '',
    email: '',
    password: '',
    passwordConfirm: '',
    address: '',
    phone: '',
    accessToken: '',
    permissions: [],
    router: useRouter(),
    loading: {},
    showDialogConfirm: false,
    confirmHeader: '',
    confirmMessage: '',
    confirmIcon: 'pi pi-info',
    confirmIconColor: 'text-blue-700',
    confirmBgColor: 'bg-blue-100',
    confirmAcceptColor: 'w-full p-button-primary',
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
        this.permissions = result.body.permissions;
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
        local.set('permissions', JSON.stringify(this.permissions));

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
        showSuccessRegister(this.toast);
        this.router.push({ name: 'login' });
        this.loading['regist'] = false;

        return user;
      }
      this.loading['regist'] = false;
    },

    async sendResetLink(email) {
      this.loading['sendResetLink'] = true;
      const payload = {
        api: this.passwordEmailApi,
        body: { email },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        this.confirmHeader = 'Email Terkirim';
        this.confirmMessage = 'Instruksi reset kata sandi telah dikirim ke email Anda.';
        this.confirmIcon = 'pi pi-info';
        this.confirmIconColor = 'text-blue-700';
        this.confirmBgColor = 'bg-blue-100';
        this.confirmAcceptColor = 'w-full p-button-primary';
      } else {
        this.confirmHeader = 'Error';
        this.confirmMessage = 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.';
        this.confirmIcon = 'pi pi-times';
        this.confirmIconColor = 'text-red-700';
        this.confirmBgColor = 'bg-red-100';
        this.confirmAcceptColor = 'w-full p-button-danger';
      }
      this.showDialogConfirm = true;
      this.loading['sendResetLink'] = false;
    },

    async resetPassword(token, email, password, passwordConfirmation) {
      this.loading['resetPassword'] = true;
      const payload = {
        api: this.passwordResetApi,
        body: {
          token,
          email,
          password,
          password_confirmation: passwordConfirmation,
        },
      };
      const result = await callApi(payload);
      if (result.isOk) {
        showSuccessResetPassword(this.toast);
        this.router.push({ name: 'login' });
      }
      this.loading['resetPassword'] = false;
    },

    async sendOtp(email) {
      this.loading['sendOtp'] = true;
      const payload = {
        api: this.sendOtpApi,
        body: { email },
      };
      const result = await callApi(payload);
      this.loading['sendOtp'] = false;
      return result;
    },

    async verifyOtp(email, otp) {
      this.loading['verifyOtp'] = true;
      const payload = {
        api: this.verifyOtpApi,
        body: { email, otp },
      };
      const result = await callApi(payload);
      if (!result.isOk) {
        showErrorOtp(this.toast);
        this.loading['verifyOtp'] = false;
        return;
      }
      this.loading['verifyOtp'] = false;
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
