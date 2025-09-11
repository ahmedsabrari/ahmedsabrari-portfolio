// utils/constants.js
export const API_BASE_URL = process.env.REACT_APP_API_URL || "http://localhost:8000";
export const APP_NAME = process.env.REACT_APP_APP_NAME || "Portfolio App";

// أدوار المستخدمين
export const USER_ROLES = {
  ADMIN: 'admin',
  VISITOR: 'visitor'
};

// رسائل التنبيه
export const ALERT_TYPES = {
  SUCCESS: 'success',
  ERROR: 'error',
  WARNING: 'warning',
  INFO: 'info'
};

// مسارات التطبيق
export const ROUTES = {
  HOME: '/',
  LOGIN: '/login',
  REGISTER: '/register',
  DASHBOARD: '/dashboard',
  PROFILE: '/profile',
  ADMIN: '/admin'
};