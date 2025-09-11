// src/pages/Portfolio/Home/HomePage.js
import React from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../../hooks/useAuth';

const HomePage = () => {
  const { isAuthenticated, user } = useAuth();

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      <div className="container mx-auto px-4 py-16">
        <div className="text-center">
          <h1 className="text-5xl font-bold text-gray-800 mb-6">
            مرحباً في Portfolio
          </h1>
          <p className="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
            منصة متكاملة لعرض مشاريعك وإبداعاتك ومشاركتها مع العالم. أنشئ ملفك الشخصي، 
            وعرض أعمالك، وادخل إلى مجتمع من المبدعين.
          </p>
          
          {isAuthenticated ? (
            <div className="space-y-4">
              <p className="text-lg text-gray-700">
                مرحباً بعودتك، {user?.name}!
              </p>
              <div className="space-x-4">
                <Link
                  to="/dashboard"
                  className="inline-block bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold"
                >
                  الانتقال إلى لوحة التحكم
                </Link>
                <Link
                  to="/profile"
                  className="inline-block border border-blue-500 text-blue-500 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold"
                >
                  الملف الشخصي
                </Link>
              </div>
            </div>
          ) : (
            <div className="space-x-4">
              <Link
                to="/register"
                className="inline-block bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold"
              >
                انضم إلينا
              </Link>
              <Link
                to="/login"
                className="inline-block border border-blue-500 text-blue-500 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold"
              >
                تسجيل الدخول
              </Link>
            </div>
          )}
        </div>

        <div className="mt-20 grid md:grid-cols-3 gap-8">
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-blue-500 text-3xl mb-4">💼</div>
            <h3 className="text-xl font-semibold mb-3">عرض المشاريع</h3>
            <p className="text-gray-600">
              أنشئ معرضاً لمشاريعك وأعمالك الإبداعية لعرض مهاراتك وإنجازاتك.
            </p>
          </div>
          
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-blue-500 text-3xl mb-4">👥</div>
            <h3 className="text-xl font-semibold mb-3">التواصل مع الآخرين</h3>
            <p className="text-gray-600">
              تواصل مع مبدعين آخرين وابنِ شبكة علاقات مهنية قوية.
            </p>
          </div>
          
          <div className="bg-white p-6 rounded-lg shadow-md">
            <div className="text-blue-500 text-3xl mb-4">🚀</div>
            <h3 className="text-xl font-semibold mb-3">تطوير مسارك المهني</h3>
            <p className="text-gray-600">
              طوّر مهاراتك وابنِ سمعة رقمية قوية تساعدك في مسارك المهني.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default HomePage;