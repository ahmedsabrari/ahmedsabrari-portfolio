import { useAuth } from "../hooks/useAuth";
import { Navigate } from "react-router-dom";

const ProtectedRoute = ({ children }) => {
  const { isAuthenticated, loading } = useAuth();

  // عرض loading أثناء التحقق من المصادقة
  if (loading) {
    return <div>جاري التحميل...</div>;
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  return children;
};

export default ProtectedRoute;