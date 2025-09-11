import { useState, useEffect } from "react";
import { useDispatch } from "react-redux";
import { Link, useNavigate } from "react-router-dom";
import { register, clearError } from "../features/auth/authSlice";
import { showAlert } from "../features/alert/alertSlice";
import { useAuth } from "../hooks/useAuth";

const RegisterPage = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const { isAuthenticated, loading} = useAuth();

  const [ formData, setFormData ] = useState( {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "visitor" // إضافة قيمة افتراضية
  } );

  useEffect( () => {
    if ( isAuthenticated ) {
      navigate( "/dashboard", { replace: true } );
    }
  }, [ isAuthenticated, navigate ] );

  useEffect( () => {
    dispatch( clearError() );
  }, [ dispatch ] );

  const handleChange = ( e ) => {
    setFormData( { ...formData, [ e.target.name ]: e.target.value } );
  };

  const validateForm = () => {
    if (!formData.name.trim()) {
      dispatch(showAlert("الاسم مطلوب", "error"));
      return false;
    }
    
    if (!formData.email.trim()) {
      dispatch(showAlert("البريد الإلكتروني مطلوب", "error"));
      return false;
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      dispatch(showAlert("البريد الإلكتروني غير صالح", "error"));
      return false;
    }
    
    if (!formData.password) {
      dispatch(showAlert("كلمة المرور مطلوبة", "error"));
      return false;
    } else if (formData.password.length < 6) {
      dispatch(showAlert("كلمة المرور يجب أن تكون على الأقل 6 أحرف", "error"));
      return false;
    }
    
    if (formData.password !== formData.password_confirmation) {
      dispatch(showAlert("كلمة المرور غير متطابقة", "error"));
      return false;
    }
    
    return true;
  };

  const handleSubmit = ( e ) => {
    e.preventDefault();

    if ( validateForm() ) {
      dispatch( register( formData ) );
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8">
        <div>
          <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
            إنشاء حساب جديد
          </h2>
          <p className="mt-2 text-center text-sm text-gray-600">
            انضم إلينا وابدأ رحلتك
          </p>
        </div>
        <form className="mt-8 space-y-6" onSubmit={ handleSubmit }>
          <div className="space-y-4">
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                الاسم الكامل
              </label>
              <input
                id="name"
                name="name"
                type="text"
                required
                className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="أدخل اسمك الكامل"
                value={ formData.name }
                onChange={ handleChange }
              />
            </div>

            <div>
              <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                البريد الإلكتروني
              </label>
              <input
                id="email"
                name="email"
                type="email"
                required
                className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="أدخل بريدك الإلكتروني"
                value={ formData.email }
                onChange={ handleChange }
              />
            </div>

            <div>
              <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                كلمة المرور
              </label>
              <input
                id="password"
                name="password"
                type="password"
                required
                className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="أنشئ كلمة مرور قوية"
                value={ formData.password }
                onChange={ handleChange }
              />
            </div>

            <div>
              <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700">
                تأكيد كلمة المرور
              </label>
              <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                className="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                placeholder="أعد إدخال كلمة المرور"
                value={ formData.password_confirmation }
                onChange={ handleChange }
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              disabled={ loading }
              className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
            >
              { loading ? "جاري إنشاء الحساب..." : "إنشاء حساب" }
            </button>
          </div>

          <div className="text-center">
            <p className="text-sm text-gray-600">
              لديك حساب بالفعل؟{ " " }
              <Link
                to="/login"
                className="font-medium text-blue-600 hover:text-blue-500"
              >
                تسجيل الدخول
              </Link>
            </p>
          </div>
        </form>
      </div>
    </div>
  );
};

export default RegisterPage;