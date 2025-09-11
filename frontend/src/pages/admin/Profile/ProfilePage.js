// src/pages/Admin/Profile/ProfilePage.js
import React from 'react';
import { useAuth } from '../../../hooks/useAuth';
import { Link } from 'react-router-dom';

const ProfilePage = () => {
  const { user } = useAuth();

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="max-w-2xl mx-auto">
        <h1 className="text-2xl font-bold mb-6">الملف الشخصي للمسؤول</h1>
        
        <div className="bg-white rounded-lg shadow-md p-6 mb-6">
          <div className="flex items-center mb-6">
            <div className="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
              {user?.name?.charAt(0)}
            </div>
            <div className="mr-4">
              <h2 className="text-xl font-semibold">{user?.name}</h2>
              <p className="text-gray-600">{user?.email}</p>
              <p className="text-sm text-blue-500 font-semibold">مدير النظام</p>
            </div>
          </div>
          
          <div className="grid grid-cols-2 gap-4 mb-6">
            <div className="bg-gray-50 p-4 rounded-md">
              <h3 className="font-semibold text-gray-700">صلاحيات المستخدم</h3>
              <p className="text-blue-500 font-bold">كاملة</p>
            </div>
            <div className="bg-gray-50 p-4 rounded-md">
              <h3 className="font-semibold text-gray-700">تاريخ الانضمام</h3>
              <p className="text-gray-600">
                {new Date(user?.created_at).toLocaleDateString('ar-SA')}
              </p>
            </div>
          </div>
          
          <div className="flex space-x-4">
            <Link
              to="/admin/profile/update"
              className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            >
              تعديل الملف الشخصي
            </Link>
            <Link
              to="/admin"
              className="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
            >
              العودة للوحة التحكم
            </Link>
          </div>
        </div>
        
        <div className="bg-white rounded-lg shadow-md p-6">
          <h2 className="text-xl font-bold mb-4">إحصائيات المسؤول</h2>
          <div className="grid grid-cols-2 gap-4">
            <div className="bg-blue-50 p-4 rounded-md">
              <h3 className="font-semibold text-blue-700">المستخدمين النشطين</h3>
              <p className="text-2xl font-bold text-blue-500">42</p>
            </div>
            <div className="bg-green-50 p-4 rounded-md">
              <h3 className="font-semibold text-green-700">المشاريع المنشورة</h3>
              <p className="text-2xl font-bold text-green-500">125</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProfilePage;