// src/pages/Portfolio/Profile/ProfilePage.js
import React, { useState } from 'react';
import { useAuth } from '../../../hooks/useAuth';
import { useDispatch } from 'react-redux';
import { deleteAccount } from '../../../features/auth/authSlice';
import { useNavigate, Link } from 'react-router-dom';

const ProfilePage = () => {
  const { user } = useAuth();
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const [deleteLoading, setDeleteLoading] = useState(false);

  const handleDeleteAccount = async () => {
    if (window.confirm('هل أنت متأكد من رغبتك في حذف حسابك؟ هذا الإجراء لا يمكن التراجع عنه.')) {
      setDeleteLoading(true);
      try {
        await dispatch(deleteAccount()).unwrap();
        navigate('/');
      } catch (error) {
        console.error('Delete failed:', error);
      } finally {
        setDeleteLoading(false);
      }
    }
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="max-w-2xl mx-auto">
        <h1 className="text-2xl font-bold mb-6">الملف الشخصي</h1>
        
        <div className="bg-white rounded-lg shadow-md p-6 mb-6">
          <div className="flex items-center mb-6">
            <div className="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
              {user?.name?.charAt(0)}
            </div>
            <div className="mr-4">
              <h2 className="text-xl font-semibold">{user?.name}</h2>
              <p className="text-gray-600">{user?.email}</p>
              <p className="text-sm text-gray-500">
                {user?.role === 'admin' ? 'مدير' : 'مستخدم'}
              </p>
            </div>
          </div>
          
          <div className="grid grid-cols-2 gap-4 mb-6">
            <div className="bg-gray-50 p-4 rounded-md">
              <h3 className="font-semibold text-gray-700">عدد المشاريع</h3>
              <p className="text-2xl font-bold text-blue-500">12</p>
            </div>
            <div className="bg-gray-50 p-4 rounded-md">
              <h3 className="font-semibold text-gray-700">تاريخ الانضمام</h3>
              <p className="text-gray-600">
                {new Date(user?.created_at).toLocaleDateString('ar-SA')}
              </p>
            </div>
          </div>
          
          <Link
            to="/profile/update"
            className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 inline-block"
          >
            تعديل الملف الشخصي
          </Link>
        </div>
        
        <div className="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
          <h2 className="text-xl font-bold text-red-600 mb-4">منطقة الخطر</h2>
          <p className="text-gray-600 mb-4">
            حذف حسابك هو عملية لا يمكن التراجع عنها. سيتم إزالة جميع بياناتك بشكل نهائي.
          </p>
          <button
            onClick={handleDeleteAccount}
            disabled={deleteLoading}
            className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 disabled:opacity-50"
          >
            {deleteLoading ? 'جاري حذف الحساب...' : 'حذف الحساب'}
          </button>
        </div>
      </div>
    </div>
  );
};

export default ProfilePage;