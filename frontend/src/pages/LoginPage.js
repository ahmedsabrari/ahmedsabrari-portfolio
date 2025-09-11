import { useState, useEffect } from "react";
import { useDispatch } from "react-redux";
import { Link, useNavigate } from "react-router-dom";
import { login, clearError } from "../features/auth/authSlice";
import { showAlert } from "../features/alert/alertSlice";
import { useAuth } from "../hooks/useAuth";

const LoginPage = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const { isAuthenticated, loading, error } = useAuth();

  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });

  useEffect(() => {
    if (isAuthenticated) {
      navigate("/dashboard", { replace: true });
    }
  }, [isAuthenticated, navigate]);

  useEffect(() => {
    dispatch(clearError());
  }, [dispatch]);

  useEffect(() => {
    // عرض رسائل الخطأ كتنبيهات إذا كانت موجودة
    if (error) {
      dispatch(showAlert(error, "error"));
    }
  }, [error, dispatch]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    
    // التحقق من صحة البيانات المدخلة
    if (!formData.email || !formData.password) {
      dispatch(showAlert("يرجى ملء جميع الحقول", "error"));
      return;
    }
    
    // التحقق من صحة البريد الإلكتروني
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
      dispatch(showAlert("يرجى إدخال بريد إلكتروني صحيح", "error"));
      return;
    }
    
    dispatch(login(formData));
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            تسجيل الدخول إلى حسابك
          </h2>
        </div>
        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          <div className="rounded-md shadow-sm -space-y-px">
            <div>
              <input
                name="email"
                type="email"
                required
                className="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="البريد الإلكتروني"
                value={formData.email}
                onChange={handleChange}
              />
            </div>
            <div>
              <input
                name="password"
                type="password"
                required
                className="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="كلمة المرور"
                value={formData.password}
                onChange={handleChange}
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              disabled={loading}
              className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
              {loading ? "جاري المعالجة..." : "تسجيل الدخول"}
            </button>
          </div>

          <div className="text-center">
            <Link
              to="/register"
              className="font-medium text-blue-600 hover:text-blue-500"
            >
              إنشاء حساب جديد
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
};

export default LoginPage;