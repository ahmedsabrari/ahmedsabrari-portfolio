import { Link } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";
import { useDispatch } from "react-redux";
import { logout } from "../features/auth/authSlice";

const Navbar = () => {
  const { isAuthenticated, user } = useAuth();
  const dispatch = useDispatch();

  const handleLogout = () => {
    dispatch(logout());
  };

  return (
    <nav className="bg-gray-800 text-white px-6 py-3 flex justify-between items-center">
      <div className="font-bold text-lg">
        <Link to="/">{process.env.REACT_APP_APP_NAME}</Link>
      </div>

      <div className="space-x-4">
        <Link to="/" className="hover:text-gray-300">
          الرئيسية
        </Link>
        {!isAuthenticated ? (
          <>
            <Link to="/login" className="hover:text-gray-300">
              تسجيل الدخول
            </Link>
            <Link to="/register" className="hover:text-gray-300">
              إنشاء حساب
            </Link>
          </>
        ) : (
          <>
            <span>مرحباً، {user?.name}</span>
            <Link to="/dashboard" className="hover:text-gray-300">
              لوحة التحكم
            </Link>
            <button
              onClick={handleLogout}
              className="bg-red-500 px-3 py-1 rounded hover:bg-red-600"
            >
              تسجيل الخروج
            </button>
          </>
        )}
      </div>
    </nav>
  );
};

export default Navbar;