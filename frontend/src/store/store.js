import { configureStore } from "@reduxjs/toolkit";
import authReducer from "../features/auth/authSlice";
import alertReducer from "../features/alert/alertSlice";

// الحصول على token من localStorage
const token = localStorage.getItem("token");

// تهيئة الحالة الأولية من localStorage
const preloadedState = {
  auth: {
    user: null,
    token: token,
    loading: false,
    error: null,
    isAuthenticated: !!token, // إضافة isAuthenticated بناءً على وجود token
  },
  alert:{
    alerts: [],
  },
};

export const store = configureStore({
  reducer: {
    auth: authReducer,
    alert: alertReducer,
  },
  preloadedState,
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware({
      serializableCheck: {
        // تجاهل التحقق من التسلسل للقيم غير القابلة للتسلسل
        ignoredActions: ['auth/register/fulfilled', 'auth/login/fulfilled'],
        ignoredPaths: ['payload.headers'],
      },
    }),
  devTools: process.env.NODE_ENV !== "production",
});