// src/pages/Admin/Dashboard/DashboardPage.js
import React, { useEffect, useState } from 'react';
import { userAPI } from '../../../services/api';
import { showAlert } from '../../../features/alert/alertSlice';
import { useDispatch } from 'react-redux';

const DashboardPage = () => {
  const dispatch = useDispatch();
  const [stats, setStats] = useState({
    totalUsers: 0,
    activeUsers: 0,
    blockedUsers: 0,
    totalProjects: 0
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      // في تطبيق حقيقي، سيكون لديك نقطة نهاية خاصة للإحصائيات
      const usersResponse = await userAPI.getAllUsers();
      const users = usersResponse.data;
      
      setStats({
        totalUsers: users.length,
        activeUsers: users.filter(u => u.status === 'active').length,
        blockedUsers: users.filter(u => u.status === 'blocked').length,
        totalProjects: 125 // قيمة افتراضية
      });
    } catch (error) {
      dispatch(showAlert('فشل في تحميل الإحصائيات', 'error'));
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="container mx-auto px-4 py-8">
        <div className="flex justify-center items-center h-64">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">لوحة تحكم المسؤول</h1>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div className="bg-white rounded-lg shadow-md p-6">
          <div className="flex items-center">
            <div className="p-3 rounded-full bg-blue-100 text-blue-500">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <div className="mr-4">
              <h2 className="text-2xl font-bold">{stats.totalUsers}</h2>
              <p className="text-gray-600">إجمالي المستخدمين</p>
            </div>
          </div>
        </div>
        
        <div className="bg-white rounded-lg shadow-md p-6">
          <div className="flex items-center">
            <div className="p-3 rounded-full bg-green-100 text-green-500">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div className="mr-4">
              <h2 className="text-2xl font-bold">{stats.activeUsers}</h2>
              <p className="text-gray-600">المستخدمين النشطين</p>
            </div>
          </div>
        </div>
        
        <div className="bg-white rounded-lg shadow-md p-6">
          <div className="flex items-center">
            <div className="p-3 rounded-full bg-red-100 text-red-500">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <div className="mr-4">
              <h2 className="text-2xl font-bold">{stats.blockedUsers}</h2>
              <p className="text-gray-600">المستخدمين المحظورين</p>
            </div>
          </div>
        </div>
        
        <div className="bg-white rounded-lg shadow-md p-6">
          <div className="flex items-center">
            <div className="p-3 rounded-full bg-purple-100 text-purple-500">
              <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
              </svg>
            </div>
            <div className="mr-4">
              <h2 className="text-2xl font-bold">{stats.totalProjects}</h2>
              <p className="text-gray-600">إجمالي المشاريع</p>
            </div>
          </div>
        </div>
      </div>
      
      <div className="bg-white rounded-lg shadow-md p-6">
        <h2 className="text-xl font-bold mb-4">الإجراءات السريعة</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a
            href="/admin/users"
            className="bg-blue-500 text-white px-4 py-3 rounded-md hover:bg-blue-600 flex items-center"
          >
            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            إدارة المستخدمين
          </a>
          <a
            href="/admin/projects"
            className="bg-green-500 text-white px-4 py-3 rounded-md hover:bg-green-600 flex items-center"
          >
            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
            </svg>
            إدارة المشاريع
          </a>
        </div>
      </div>
    </div>
  );
};

export default DashboardPage;