import { configureStore } from "@reduxjs/toolkit";
import authReducer from "../features/auth/authSlice";

// تهيئة الحالة الأولية من localStorage
const preloadedState = {
  auth: {
    user: null,
    token: localStorage.getItem("token"),
    loading: false,
    error: null,
  },
};

export const store = configureStore({
  reducer: {
    auth: authReducer,
  },
  preloadedState,
  devTools: process.env.NODE_ENV !== "production",
});