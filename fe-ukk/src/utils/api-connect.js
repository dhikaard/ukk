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

  console.group('API Request');
  console.trace({
    endpoint: api.path,
    method: api.method,
    body: body || 'No body',
    params: params || 'No params'
  });

  try {
    const response = await callApiConnect({
      url: `${api.path}`,
      method: api.method,
      data: body || null,
      params: params || {},
    });

    console.group('API Response');
    console.trace({
      status: response.status,
      message: response.data.message || 'Success',
      data: response.data
    });
    console.groupEnd();

    if (response.status === 200) {
      console.groupEnd();
      return { isOk: true, data: response.data };
    }

    if (toast) {
      showError(toast, response.data.message);
    }
    console.groupEnd();
    return { isOk: false, error: response.data };

  } catch (error) {
    console.group('API Error');
    console.trace({
      status: error.response?.status,
      message: error.response?.data?.message || error.message,
      error: error.response?.data || error
    });
    console.groupEnd();
    console.groupEnd();

    if (error.response?.status === 401) {
      if (toast) {
        showSessionExp(toast);
      }
    } else if (toast) {
      showError(toast, error.response?.data?.message || 'An error occurred');
    }
    return { isOk: false, error: error.response?.data || error };
  }
};

export default callApi;