import axios from 'axios';

const apiConnect = axios.create({
  baseURL: `${import.meta.env.VITE_BASE_URL}v1/rental`,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: false, // Jika menggunakan autentikasi berbasis cookie
});

// Interceptor untuk request
apiConnect.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token'); // Contoh penggunaan token
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Interceptor untuk response
apiConnect.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      console.error('Unauthorized, redirecting to login...');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default apiConnect;
