import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../services/api";
import { userAPI } from "../../services/api";
import { showAlert } from "../alert/alertSlice";

// تسجيل الدخول
export const login = createAsyncThunk(
  "auth/login",
  async (credentials, { dispatch, rejectWithValue }) => {
    try {
      const response = await api.post("/login", credentials);
      dispatch(showAlert("تم تسجيل الدخول بنجاح", "success"));
      return {
        token: response.data.token,
        user: response.data.user
      };
    } catch (error) {
      const errorMessage = error.response?.data?.message || "حدث خطأ أثناء تسجيل الدخول";
      dispatch(showAlert(errorMessage, "error"));
      return rejectWithValue(error.response?.data || { message: errorMessage });
    }
  }
);

// تسجيل مستخدم جديد
export const register = createAsyncThunk(
  "auth/register",
  async (userData, { dispatch, rejectWithValue }) => {
    try {
      const response = await api.post("/register", userData);
      dispatch(showAlert("تم إنشاء الحساب بنجاح", "success"));
      return {
        token: response.data.token,
        user: response.data.user
      };
    } catch (error) {
      const errorMessage = error.response?.data?.message || "حدث خطأ أثناء إنشاء الحساب";
      dispatch(showAlert(errorMessage, "error"));
      return rejectWithValue(error.response?.data || { message: errorMessage });
    }
  }
);

// تسجيل الخروج
export const logoutUser = createAsyncThunk(
  "auth/logout",
  async (_, { dispatch }) => {
    try {
      await api.post("/logout");
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      localStorage.removeItem("token");
      dispatch(showAlert("تم تسجيل الخروج بنجاح", "info"));
    }
  }
);

// جلب بيانات المستخدم
export const fetchUser = createAsyncThunk(
  "auth/fetchUser",
  async (_, { dispatch, rejectWithValue }) => {
    try {
      const response = await userAPI.getProfile();
      return response.data.user;
    } catch (error) {
      const errorMessage = error.response?.data?.message || "فشل في تحميل بيانات المستخدم";
      dispatch(showAlert(errorMessage, "error"));
      
      // تسجيل الخروج إذا كان التوكن غير صالح
      if (error.response?.status === 401) {
        dispatch(logout());
      }
      
      return rejectWithValue(error.response?.data || { message: errorMessage });
    }
  }
);

// تحديث الملف الشخصي
export const updateProfile = createAsyncThunk(
  "auth/updateProfile",
  async (userData, { dispatch, rejectWithValue }) => {
    try {
      const response = await userAPI.updateProfile(userData);
      dispatch(showAlert("تم تحديث الملف الشخصي بنجاح", "success"));
      return response.data.user;
    } catch (error) {
      const errorMessage = error.response?.data?.message || "فشل في تحديث الملف الشخصي";
      dispatch(showAlert(errorMessage, "error"));
      return rejectWithValue(error.response?.data || { message: errorMessage });
    }
  }
);

// حذف الحساب
export const deleteAccount = createAsyncThunk(
  "auth/deleteAccount",
  async (_, { dispatch, rejectWithValue }) => {
    try {
      await userAPI.deleteAccount();
      dispatch(showAlert("تم حذف الحساب بنجاح", "success"));
    } catch (error) {
      const errorMessage = error.response?.data?.message || "فشل في حذف الحساب";
      dispatch(showAlert(errorMessage, "error"));
      return rejectWithValue(error.response?.data || { message: errorMessage });
    }
  }
);

const authSlice = createSlice({
  name: "auth",
  initialState: {
    user: null,
    token: null,
    loading: false,
    error: null,
    isAuthenticated: false,
  },
  reducers: {
    logout: (state) => {
      state.user = null;
      state.token = null;
      state.isAuthenticated = false;
      localStorage.removeItem("token");
    },
    clearError: (state) => {
      state.error = null;
    },
  },
  extraReducers: (builder) => {
    builder
      // تسجيل الدخول
      .addCase(login.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(login.fulfilled, (state, action) => {
        state.loading = false;
        state.token = action.payload.token;
        state.user = action.payload.user;
        state.isAuthenticated = true;
        localStorage.setItem("token", action.payload.token);
      })
      .addCase(login.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload?.message || "حدث خطأ ما";
        state.isAuthenticated = false;
      })
      // تسجيل مستخدم جديد
      .addCase(register.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(register.fulfilled, (state, action) => {
        state.loading = false;
        state.token = action.payload.token;
        state.user = action.payload.user;
        state.isAuthenticated = true;
        localStorage.setItem("token", action.payload.token);
      })
      .addCase(register.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload?.message || "حدث خطأ ما";
        state.isAuthenticated = false;
      })
      // تسجيل الخروج
      .addCase(logoutUser.pending, (state) => {
        state.loading = true;
      })
      .addCase(logoutUser.fulfilled, (state) => {
        state.loading = false;
        state.user = null;
        state.token = null;
        state.isAuthenticated = false;
      })
      .addCase(logoutUser.rejected, (state) => {
        state.loading = false;
        // حتى لو فشل تسجيل الخروج على الخادم، نقوم بتنظيف التخزين المحلي
        state.user = null;
        state.token = null;
        state.isAuthenticated = false;
        localStorage.removeItem("token");
      })
      // جلب بيانات المستخدم
      .addCase(fetchUser.pending, (state) => {
        state.loading = true;
      })
      .addCase(fetchUser.fulfilled, (state, action) => {
        state.loading = false;
        state.user = action.payload;
        state.isAuthenticated = true;
      })
      .addCase(fetchUser.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload?.message;
        state.isAuthenticated = false;
        state.token = null;
        localStorage.removeItem("token");
      })
      // تحديث الملف الشخصي
      .addCase(updateProfile.pending, (state) => {
        state.loading = true;
      })
      .addCase(updateProfile.fulfilled, (state, action) => {
        state.loading = false;
        state.user = action.payload;
      })
      .addCase(updateProfile.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload?.message;
      })
      // حذف الحساب
      .addCase(deleteAccount.fulfilled, (state) => {
        state.user = null;
        state.token = null;
        state.isAuthenticated = false;
        localStorage.removeItem("token");
      });
  },
});

export const { logout, clearError } = authSlice.actions;
export default authSlice.reducer;