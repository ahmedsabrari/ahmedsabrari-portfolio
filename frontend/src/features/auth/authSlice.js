import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/axios";

// تسجيل الدخول
export const login = createAsyncThunk(
  "auth/login",
  async (credentials, { rejectWithValue }) => {
    try {
      const response = await api.post("/login", credentials);
      return {
        access_token: response.data.access_token,
        user: response.data.user
      };
    } catch (error) {
      return rejectWithValue(
        error.response?.data || { message: "حدث خطأ ما" }
      );
    }
  }
);

// تسجيل مستخدم جديد
export const register = createAsyncThunk(
  "auth/register",
  async (userData, { rejectWithValue }) => {
    try {
      const response = await api.post("/register", userData);
      return {
        access_token: response.data.access_token,
        user: response.data.user
      };
    } catch (error) {
      return rejectWithValue(
        error.response?.data || { message: "حدث خطأ ما" }
      );
    }
  }
);

// جلب بيانات المستخدم
export const fetchUser = createAsyncThunk(
  "auth/fetchUser",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/user");
      return response.data;
    } catch (error) {
      return rejectWithValue(
        error.response?.data || { message: "حدث خطأ ما" }
      );
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
        state.token = action.payload.access_token;
        state.user = action.payload.user;
        state.isAuthenticated = true;
        localStorage.setItem("token", action.payload.access_token);
      })
      .addCase(login.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload.message;
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
        state.error = action.payload.message;
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
        state.error = action.payload.message;
        state.isAuthenticated = false;
        state.token = null;
        localStorage.removeItem("token");
      });
  },
});

export const { logout, clearError } = authSlice.actions;
export default authSlice.reducer;