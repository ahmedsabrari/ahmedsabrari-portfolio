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
    isAuthenticated: !!token,
  },
  alert: {
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
        ignoredActions: [
          'auth/register/fulfilled', 
          'auth/login/fulfilled',
          'auth/logout/fulfilled'
        ],
        ignoredPaths: [
          'payload.headers',
          'payload.config',
          'payload.request'
        ],
      },
    }),
  devTools: process.env.NODE_ENV !== "production",
});