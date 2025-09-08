import axios from "axios";
import { store } from "../store/store";
import { logout } from "../features/auth/authSlice";

const api = axios.create({
  baseURL: process.env.NODE_ENV === 'development' ? '/api' : process.env.REACT_APP_API_URL,
  withCredentials: true,
});

// إضافة التوكن تلقائياً للطلبات
api.interceptors.request.use(
  (config) => {
    const token = store.getState().auth.token || localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// معالجة الأخطاء العامة
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      store.dispatch(logout());
      window.location.href = "/login";
    }
    return Promise.reject(error);
  }
);

export default api;