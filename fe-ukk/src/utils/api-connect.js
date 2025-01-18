import axios from 'axios';

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
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

const callApi = async (payload) => {
  const { api, body, params } = payload;

  try {
    const response = await callApiConnect({
      url: `${api.path}`, 
      method: api.method, 
      data: body || null, 
      params: params || {},
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
      data: error.response.data,
      status: error.status,
    });
    return { isOk: false, error };
  }
};


export default callApi;
