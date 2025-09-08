import { useAuth } from "../hooks/useAuth";
import { Navigate } from "react-router-dom";

const ProtectedRoute = ({ children }) => {
  const { isAuthenticated, token } = useAuth();

  if (!isAuthenticated && !token) {
    return <Navigate to="/login" replace />;
  }

  return children;
};

export default ProtectedRoute;