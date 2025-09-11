import axios from "axios";
import { store } from "../store/store";
import { logout } from "../features/auth/authSlice";

const BASE_URL = process.env.REACT_APP_API_URL || "http://localhost:8000";

// إنشاء instance منفصل لطلبات CSRF
const csrfAxios = axios.create({
  baseURL: BASE_URL,
  withCredentials: true,
});

const api = axios.create({
  baseURL: `${BASE_URL}/api`,
  withCredentials: true,
});

// متغير لتخزين حالة CSRF token
let csrfTokenRequest = null;

// وظيفة للحصول على CSRF token مع ضمان عدم تكرار الطلبات
const getCsrfToken = async () => {
  if (!csrfTokenRequest) {
    csrfTokenRequest = csrfAxios.get("/sanctum/csrf-cookie")
      .then(response => {
        console.log("CSRF token obtained successfully");
        return response;
      })
      .catch(error => {
        console.error("Failed to get CSRF token:", error);
        throw error;
      })
      .finally(() => {
        // إعادة تعيين الطلب بعد اكتماله
        setTimeout(() => {
          csrfTokenRequest = null;
        }, 1000);
      });
  }
  return csrfTokenRequest;
};

// إضافة التوكن تلقائياً للطلبات
api.interceptors.request.use(
  async (config) => {
    // للحصول على CSRF token للطلبات التي تحتاجه
    if (['post', 'put', 'patch', 'delete'].includes(config.method.toLowerCase())) {
      try {
        await getCsrfToken();
        
        // إضافة XSRF-Token header يدوياً
        const cookies = document.cookie.split(';');
        const xsrfToken = cookies.find(cookie => cookie.trim().startsWith('XSRF-TOKEN='));
        
        if (xsrfToken) {
          const tokenValue = decodeURIComponent(xsrfToken.split('=')[1]);
          config.headers['X-XSRF-TOKEN'] = tokenValue;
        }
      } catch (error) {
        console.error("Failed to get CSRF token:", error);
        return Promise.reject(error);
      }
    }

    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    
    // إضافة headers مهمة
    config.headers.Accept = 'application/json';
    config.headers['X-Requested-With'] = 'XMLHttpRequest';
    
    if (config.data instanceof FormData) {
      // لا تقم بتعيين Content-Type لـ FormData - سيقوم axios بتعيينه تلقائياً مع الحدود الصحيحة
      delete config.headers['Content-Type'];
    } else if (config.data) {
      config.headers['Content-Type'] = 'application/json';
    }
    
    return config;
  },
  (error) => Promise.reject(error)
);

// معالجة الأخطاء العامة
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;
    
    if (error.response?.status === 401) {
      store.dispatch(logout());
      window.location.href = "/login";
    } else if (error.response?.status === 419 && !originalRequest._retry) {
      // إذا كان خطأ CSRF، نحاول الحصول على token جديد وإعادة الطلب
      originalRequest._retry = true;
      csrfTokenRequest = null; // إعادة تعيين لضمان الحصول على token جديد
      try {
        await getCsrfToken();
        return api(originalRequest);
      } catch (csrfError) {
        return Promise.reject(csrfError);
      }
    } else if (error.response?.status === 403) {
      console.error("Forbidden:", error.response.data);
    }
    
    return Promise.reject(error);
  }
);

// دوال API للمستخدمين
export const userAPI = {
  getProfile: () => api.get("/profile"),
  updateProfile: (userData) => api.put("/profile", userData),
  deleteAccount: () => api.delete("/account"),
  getAllUsers: () => api.get("/users"),
  blockUser: (userId) => api.post(`/users/${userId}/block`),
  unblockUser: (userId) => api.post(`/users/${userId}/unblock`),
};

export default api;