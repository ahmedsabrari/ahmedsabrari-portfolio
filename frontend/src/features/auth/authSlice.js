import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/axios";
import { showAlert } from "../alert/alertSlice";

// تسجيل الدخول
export const login = createAsyncThunk(
  "auth/login",
  async (credentials, { dispatch, rejectWithValue }) => {
    try {
      const response = await api.post("/login", credentials);

      // عرض تنبيه النجاح
      dispatch(showAlert("تم تسجيل الدخول بنجاح", "success"));

      return {
        access_token: response.data.access_token,
        user: response.data.user
      };
    } catch (error) {

      const errorMessage = error.response?.data?.message || "حدث خطأ أثناء تسجيل الدخول";
      // عرض تنبيه الخطأ
      dispatch(showAlert(errorMessage, "error"));

      return rejectWithValue(
        error.response?.data || { message: errorMessage }
      );
    }
  }
);

// تسجيل مستخدم جديد
export const register = createAsyncThunk(
  "auth/register",
  async (userData, { dispatch, rejectWithValue }) => {
    try {
      const response = await api.post("/register", userData);

      // عرض تنبيه النجاح
      dispatch(showAlert("تم إنشاء الحساب بنجاح", "success"));

      return {
        access_token: response.data.access_token,
        user: response.data.user
      };
    } catch (error) {
      const errorMessage = error.response?.data?.message || "حدث خطأ أثناء إنشاء الحساب";

      // عرض تنبيه الخطأ
      dispatch(showAlert(errorMessage, "error"));

      return rejectWithValue(
        error.response?.data || { message: errorMessage }
      );
    }
  }
);

// جلب بيانات المستخدم
export const fetchUser = createAsyncThunk(
  "auth/fetchUser",
  async (_, { dispatch, rejectWithValue }) => {
    try {
      const response = await api.get("/user");
      return response.data;
    } catch (error) {

      const errorMessage = error.response?.data?.message || "حدث خطأ أثناء جلب بيانات المستخدم";

      // عرض تنبيه الخطأ فقط إذا كان هناك token (لمنع عرض التنبيه عند الزيارة الأولى)
      const token = localStorage.getItem("token");
      if (token) {
        dispatch(showAlert(errorMessage, "error"));
      }

      return rejectWithValue(
        error.response?.data || { message: errorMessage }
      );
    }
  }
);

// تسجيل الخروج
export const logoutUser = createAsyncThunk(
  "auth/logout",
  async (_, { dispatch }) => {
    localStorage.removeItem("token");
    
    // عرض تنبيه المعلومات
    dispatch(showAlert("تم تسجيل الخروج بنجاح", "info"));
    
    return;
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
        state.token = action.payload.access_token;
        state.user = action.payload.user;
        state.isAuthenticated = true;
        localStorage.setItem("token", action.payload.access_token);
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
        state.token = action.payload.access_token;
        state.user = action.payload.user;
        state.isAuthenticated = true;
        localStorage.setItem("token", action.payload.access_token);
      })
      .addCase(register.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload?.message || "حدث خطأ ما";
        state.isAuthenticated = false;
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
        state.error = action.payload?.message || "حدث خطأ ما";
        state.isAuthenticated = false;
        state.token = null;
        localStorage.removeItem("token");
      })
      // تسجيل الخروج
      .addCase(logoutUser.fulfilled, (state) => {
        state.user = null;
        state.token = null;
        state.isAuthenticated = false;
      });
  },
});

export const { logout, clearError } = authSlice.actions;
export default authSlice.reducer;