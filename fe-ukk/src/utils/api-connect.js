import axios from 'axios';
import local from '../utils/local-storage';

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
    const token = local.get('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

callApiConnect.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      if (error.response.status === 401) {
        local.remove('user');
        local.remove('token');
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

const callApi = async (payload) => {
  const { api, body, params } = payload;

  try {
    const headers = body instanceof FormData ? { 'Content-Type': 'multipart/form-data' } : undefined;

    const response = await callApiConnect({
      url: `${api.path}`, 
      method: api.method, 
      data: body || null, 
      params: params || {},
      headers,
    });

    console.trace({
      body: body,
      data: response.data,
      status: response.status,
    });
    return { isOk: true, body: response.data };
  } catch (error) {
    console.trace({
      body: body,
      data: error.response?.data || error.message,
      status: error.status,
    });
    return { isOk: false, error };
  }
};

export default callApi;
