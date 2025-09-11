import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  alerts: [],
};

const alertSlice = createSlice({
  name: 'alert',
  initialState,
  reducers: {
    setAlert: (state, action) => {
      state.alerts.push(action.payload);
    },
    removeAlert: (state, action) => {
      state.alerts = state.alerts.filter(alert => alert.id !== action.payload);
    },
    clearAlerts: (state) => {
      state.alerts = [];
    },
  },
});

export const { setAlert, removeAlert, clearAlerts } = alertSlice.actions;

// Helper function to show alerts with auto-removal
export const showAlert = (message, type = 'info', timeout = 5000) => (dispatch) => {
  const id = Date.now();
  dispatch(setAlert({ id, type, message }));
  
  // Auto remove alert after timeout
  setTimeout(() => {
    dispatch(removeAlert(id));
  }, timeout);
};

export default alertSlice.reducer;