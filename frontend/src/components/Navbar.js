// src/components/Navbar.js
import { Link } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";
import { useDispatch } from "react-redux";
import { logoutUser } from "../features/auth/authSlice";

const Navbar = () => {
  const { isAuthenticated, user, loading } = useAuth();
  const dispatch = useDispatch();

  const handleLogout = () => {
    dispatch(logoutUser());
  };

  return (
    <nav className="bg-gray-800 text-white px-6 py-3 flex justify-between items-center">
      <div className="font-bold text-lg">
        <Link to="/">{process.env.REACT_APP_APP_NAME || "Portfolio"}</Link>
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
            {user?.role === 'admin' ? (
              <>
                <Link to="/admin" className="hover:text-gray-300">
                  لوحة التحكم
                </Link>
                <Link to="/admin/profile" className="hover:text-gray-300">
                  الملف الشخصي
                </Link>
              </>
            ) : (
              <>
                <Link to="/profile" className="hover:text-gray-300">
                  الملف الشخصي
                </Link>
              </>
            )}
            <button
              onClick={handleLogout}
              disabled={loading}
              className="bg-red-500 px-3 py-1 rounded hover:bg-red-600 disabled:opacity-50"
            >
              {loading ? "جاري تسجيل الخروج..." : "تسجيل الخروج"}
            </button>
          </>
        )}
      </div>
    </nav>
  );
};

export default Navbar;