import { useSelector } from "react-redux";

export const useAuth = () => {
  const auth = useSelector((state) => state.auth);
  
  return {
    isAuthenticated: auth.isAuthenticated,
    user: auth.user,
    token: auth.token,
    loading: auth.loading,
    error: auth.error
  };
};