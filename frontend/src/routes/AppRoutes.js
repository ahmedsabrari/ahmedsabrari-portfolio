import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import Navbar from "../components/Navbar";
import Alert from "../components/Alert";
import LoginPage from "../pages/LoginPage";
import RegisterPage from "../pages/RegisterPage";
import HomePage from "../pages/HomePage";
import DashboardPage from "../pages/DashboardPage";
import { fetchUser } from "../features/auth/authSlice";
import { useAuth } from "../hooks/useAuth";
import ProtectedRoute from "./ProtectedRoutes";

const AppRouter = () => {
  const dispatch = useDispatch();
  const { token, isAuthenticated } = useAuth();

  useEffect(() => {
    if (token && !isAuthenticated) {
      dispatch(fetchUser());
    }
  }, [token, isAuthenticated, dispatch]);

  return (
    <Router>
      <Navbar />
      <Alert />
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route
          path="/dashboard"
          element={
            <ProtectedRoute>
              <DashboardPage />
            </ProtectedRoute>
          }
        />
      </Routes>
    </Router>
  );
};

export default AppRouter;