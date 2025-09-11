// src/routes/AppRoutes.js
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { useEffect } from "react";
import { useDispatch } from "react-redux";
import Navbar from "../components/Navbar";
import Alert from "../components/Alert";
import Footer from "../components/Footer";
import HomePage from "../pages/portfolio/Home/HomePage";
import LoginPage from "../pages/Auth/LoginPage";
import RegisterPage from "../pages/Auth/RegisterPage";
import ProfilePage from "../pages/portfolio/Profile/ProfilePage";
import UpdateProfilePage from "../pages/portfolio/Profile/UpdateProfilePage";
import DashboardPage from "../pages/admin/Dashboard/DashboardPage";
import AdminProfilePage from "../pages/admin/Profile/ProfilePage";
import AdminUpdateProfilePage from "../pages/admin/Profile/UpdateProfilePage";
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
      <div className="min-h-screen flex flex-col">
        <Navbar />
        <main className="flex-grow">
          <Alert />
          <Routes>
            <Route path="/" element={<HomePage />} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/register" element={<RegisterPage />} />
            
            {/* صفحات المستخدمين العاديين */}
            <Route
              path="/profile"
              element={
                <ProtectedRoute>
                  <ProfilePage />
                </ProtectedRoute>
              }
            />
            <Route
              path="/profile/update"
              element={
                <ProtectedRoute>
                  <UpdateProfilePage />
                </ProtectedRoute>
              }
            />
            
            {/* صفحات المسؤولين */}
            <Route
              path="/admin"
              element={
                <ProtectedRoute adminOnly={true}>
                  <DashboardPage />
                </ProtectedRoute>
              }
            />
            <Route
              path="/admin/profile"
              element={
                <ProtectedRoute adminOnly={true}>
                  <AdminProfilePage />
                </ProtectedRoute>
              }
            />
            <Route
              path="/admin/profile/update"
              element={
                <ProtectedRoute adminOnly={true}>
                  <AdminUpdateProfilePage />
                </ProtectedRoute>
              }
            />
          </Routes>
        </main>
        <Footer />
      </div>
    </Router>
  );
};

export default AppRouter;