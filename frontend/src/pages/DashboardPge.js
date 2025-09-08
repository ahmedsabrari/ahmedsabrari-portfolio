import { useState, useEffect } from "react";
import { useDispatch } from "react-redux";
import { logout } from "../features/auth/authSlice";
import { useAuth } from "../hooks/useAuth";

const DashboardPage = () => {
  const dispatch = useDispatch();
  const { user } = useAuth();
  const [stats, setStats] = useState({
    projects: 0,
    tasks: 0,
    completed: 0
  });

  // محاكاة لجلب الإحصائيات (في تطبيق حقيقي، ستأتي هذه البيانات من API)
  useEffect(() => {
    // هذه مجرد بيانات وهمية لأغراض العرض
    setStats({
      projects: 5,
      tasks: 12,
      completed: 8
    });
  }, []);

  const handleLogout = () => {
    dispatch(logout());
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container mx-auto px-4 py-8">
        {/* رأس الصفحة */}
        <div className="flex justify-between items-center mb-8">
          <h1 className="text-2xl font-bold text-gray-900">لوحة التحكم</h1>
          <button
            onClick={handleLogout}
            className="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition"
          >
            تسجيل الخروج
          </button>
        </div>

        {/* رسالة ترحيب */}
        <div className="bg-white p-6 rounded-xl shadow-md mb-8">
          <h2 className="text-xl font-semibold mb-2">
            مرحباً بعودتك، {user?.name}!
          </h2>
          <p className="text-gray-600">
            هنا يمكنك إدارة مشاريعك ومتابعة تقدمك.
          </p>
        </div>

        {/* إحصائيات */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div className="bg-white p-6 rounded-xl shadow-md">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <div>
                <p className="text-sm text-gray-500">المشاريع</p>
                <p className="text-2xl font-bold">{stats.projects}</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white p-6 rounded-xl shadow-md">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
              </div>
              <div>
                <p className="text-sm text-gray-500">المهام المكتملة</p>
                <p className="text-2xl font-bold">{stats.completed}</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white p-6 rounded-xl shadow-md">
            <div className="flex items-center">
              <div className="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p className="text-sm text-gray-500">المهام الجارية</p>
                <p className="text-2xl font-bold">{stats.tasks - stats.completed}</p>
              </div>
            </div>
          </div>
        </div>

        {/* محتوى إضافي */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* المشاريع الحديثة */}
          <div className="bg-white p-6 rounded-xl shadow-md">
            <h3 className="text-lg font-semibold mb-4">مشاريعك الأخيرة</h3>
            <div className="space-y-4">
              <div className="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                <div>
                  <h4 className="font-medium">مشروع تطوير الويب</h4>
                  <p className="text-sm text-gray-500">75% مكتمل</p>
                </div>
                <span className="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">قيد التنفيذ</span>
              </div>
              
              <div className="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div>
                  <h4 className="font-medium">تصميم الشعار</h4>
                  <p className="text-sm text-gray-500">100% مكتمل</p>
                </div>
                <span className="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">مكتمل</span>
              </div>
              
              <div className="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <div>
                  <h4 className="font-medium">دراسة الجدوى</h4>
                  <p className="text-sm text-gray-500">30% مكتمل</p>
                </div>
                <span className="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">معلق</span>
              </div>
            </div>
          </div>
          
          {/* نشاط حديث */}
          <div className="bg-white p-6 rounded-xl shadow-md">
            <h3 className="text-lg font-semibold mb-4">النشاط الحديث</h3>
            <div className="space-y-4">
              <div className="flex items-start">
                <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                  <svg className="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </div>
                <div>
                  <p className="font-medium">أضفت مهمة جديدة</p>
                  <p className="text-sm text-gray-500">منذ ساعتين</p>
                </div>
              </div>
              
              <div className="flex items-start">
                <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                  <svg className="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <p className="font-medium">أكملت مهمة "تصميم الشعار"</p>
                  <p className="text-sm text-gray-500">منذ 5 ساعات</p>
                </div>
              </div>
              
              <div className="flex items-start">
                <div className="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-1">
                  <svg className="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </div>
                <div>
                  <p className="font-medium">قمت بتحديث مشروع "تطوير الويب"</p>
                  <p className="text-sm text-gray-500">منذ يومين</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* دعوة للعمل */}
        <div className="mt-8 bg-blue-50 p-6 rounded-xl text-center">
          <h3 className="text-xl font-semibold mb-2">ابدأ مشروعاً جديداً</h3>
          <p className="text-gray-600 mb-4">استفد من إمكانيات منصتنا لبدء مشروعك القادم</p>
          <button className="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            إنشاء مشروع جديد
          </button>
        </div>
      </div>
    </div>
  );
};

export default DashboardPage;