import axios from 'axios';
import { showError, showSessionExp } from '@/utils/toast-service';

const callApiConnect = axios.create({
  baseURL: `${import.meta.env.VITE_BASE_URL}v1/rent/`,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: false,
});

callApiConnect.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`; // JWT format
    }
    return config;
  },
  (error) => Promise.reject(error)
);

callApiConnect.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      // window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
  const callApi = async (payload) => {
  const { api, body, params, toast } = payload;

  try {
    const response = await callApiConnect({
      url: `${api.path}`,
      method: api.method,
      data: body || null,
      params: params || {},
    });

    const apiName = api.path.split('/').pop();

    // Simplified success logging
    console.log(`API [${apiName}]:`, {
      status: response.status,
      isOk: true,
      data: response.data,
      body: body || 'No body',
    });

    if (response.status === 200) {
      return { isOk: true, data: response.data };
    }

    if (toast) {
      showError(toast, response.data.message);
    }
    return { isOk: false, error: response.data };

  } catch (error) {
    const apiName = api.path.split('/').pop();
    console.error(`API [${apiName}] Error:`, {
      status: error.response?.status,
      isOk: false,
      message: error.response?.data?.message,
      error: error.response?.data
    });
    return { isOk: false, error: error.response?.data };
  }
};

export default callApi;