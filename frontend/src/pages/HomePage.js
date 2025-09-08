import { Link } from "react-router-dom";
import { useAuth } from "../hooks/useAuth";

const HomePage = () => {
  const { isAuthenticated, user } = useAuth();

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      {/* هيدر */}
      <header className="container mx-auto px-4 py-8">
        <div className="flex justify-between items-center">
          <h1 className="text-3xl font-bold text-blue-800">
            {process.env.REACT_APP_APP_NAME}
          </h1>
          <nav className="space-x-4">
            {isAuthenticated ? (
              <Link
                to="/dashboard"
                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
              >
                لوحة التحكم
              </Link>
            ) : (
              <>
                <Link
                  to="/login"
                  className="text-blue-600 hover:text-blue-800 transition"
                >
                  تسجيل الدخول
                </Link>
                <Link
                  to="/register"
                  className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                >
                  إنشاء حساب
                </Link>
              </>
            )}
          </nav>
        </div>
      </header>

      {/* محتوى رئيسي */}
      <main className="container mx-auto px-4 py-16">
        <section className="text-center mb-16">
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            مرحباً بك في {process.env.REACT_APP_APP_NAME}
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto mb-10">
            منصة متكاملة لإدارة مشاريعك وتنمية مهاراتك بشكل احترافي
          </p>
          
          {isAuthenticated ? (
            <div className="space-y-4">
              <p className="text-lg text-gray-700">
                مرحباً بعودتك، {user?.name}!
              </p>
              <Link
                to="/dashboard"
                className="inline-block bg-green-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-green-700 transition"
              >
                الانتقال إلى لوحة التحكم
              </Link>
            </div>
          ) : (
            <div className="space-x-4">
              <Link
                to="/register"
                className="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition"
              >
                البدء الآن
              </Link>
              <Link
                to="/login"
                className="inline-block border border-blue-600 text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-50 transition"
              >
                تسجيل الدخول
              </Link>
            </div>
          )}
        </section>

        {/* ميزات */}
        <section className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
          <div className="bg-white p-6 rounded-xl shadow-md">
            <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 className="text-xl font-semibold mb-2">آمن ومحمي</h3>
            <p className="text-gray-600">
              بياناتك محمية بأفضل تقنيات التشفير للحفاظ على خصوصيتك وأمانك.
            </p>
          </div>
          
          <div className="bg-white p-6 rounded-xl shadow-md">
            <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
              <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 className="text-xl font-semibold mb-2">سريع وسلس</h3>
            <p className="text-gray-600">
              واجهة مستخدم بديهية وسريعة تجعل تجربتك أكثر سلاسة وإنتاجية.
            </p>
          </div>
          
          <div className="bg-white p-6 rounded-xl shadow-md">
            <div className="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
              <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 className="text-xl font-semibold mb-2">للجميع</h3>
            <p className="text-gray-600">
              مصمم ليناسب احتياجاتك سواء كنت فرداً أو جزءاً من فريق عمل.
            </p>
          </div>
        </section>

        {/* دعوة للعمل */}
        <section className="text-center bg-white p-8 rounded-xl shadow-md">
          <h3 className="text-2xl font-bold mb-4">مستعد للبدء؟</h3>
          <p className="text-gray-600 mb-6">
            انضم إلى آلاف المستخدمين الذين يستفيدون من منصتنا يومياً
          </p>
          {!isAuthenticated && (
            <Link
              to="/register"
              className="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition"
            >
              إنشاء حساب مجاني
            </Link>
          )}
        </section>
      </main>

      {/* تذييل */}
      <footer className="bg-gray-800 text-white py-8">
        <div className="container mx-auto px-4 text-center">
          <p>&copy; {new Date().getFullYear()} {process.env.REACT_APP_APP_NAME}. جميع الحقوق محفوظة.</p>
        </div>
      </footer>
    </div>
  );
};

export default HomePage;