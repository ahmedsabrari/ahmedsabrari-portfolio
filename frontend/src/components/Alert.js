import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { removeAlert } from '../features/alert/alertSlice';
import {
  CheckCircleIcon,
  ExclamationTriangleIcon,
  ExclamationCircleIcon,
  InformationCircleIcon,
  XMarkIcon,
} from '@heroicons/react/24/outline';

const Alert = () => {
  const dispatch = useDispatch();
  const alerts = useSelector((state) => state.alert.alerts);
  const [visibleAlerts, setVisibleAlerts] = useState({});

  useEffect(() => {
    // إضافة تنبيهات جديدة إلى الحالة المحلية
    const newAlerts = {};
    alerts.forEach(alert => {
      if (!visibleAlerts[alert.id]) {
        newAlerts[alert.id] = true;
      }
    });
    
    if (Object.keys(newAlerts).length > 0) {
      setVisibleAlerts(prev => ({ ...prev, ...newAlerts }));
    }
  }, [alerts]); // إزالة visibleAlerts من dependencies لتجنب التكرار

  const handleClose = (id) => {
    // إخفاء التنبيه ثم إزالته بعد انتهاء الرسوم المتحركة
    setVisibleAlerts(prev => ({ ...prev, [id]: false }));
    setTimeout(() => {
      dispatch(removeAlert(id));
      setVisibleAlerts(prev => {
        const newState = { ...prev };
        delete newState[id];
        return newState;
      });
    }, 300);
  };

  if (!alerts.length) return null;

  const getAlertConfig = (type) => {
    const configs = {
      success: {
        bg: 'bg-green-50 border-green-400',
        text: 'text-green-800',
        icon: <CheckCircleIcon className="h-5 w-5 text-green-400" />,
        focusRing: 'focus:ring-green-600 focus:ring-offset-green-50'
      },
      error: {
        bg: 'bg-red-50 border-red-400',
        text: 'text-red-800',
        icon: <ExclamationCircleIcon className="h-5 w-5 text-red-400" />,
        focusRing: 'focus:ring-red-600 focus:ring-offset-red-50'
      },
      warning: {
        bg: 'bg-yellow-50 border-yellow-400',
        text: 'text-yellow-800',
        icon: <ExclamationTriangleIcon className="h-5 w-5 text-yellow-400" />,
        focusRing: 'focus:ring-yellow-600 focus:ring-offset-yellow-50'
      },
      info: {
        bg: 'bg-blue-50 border-blue-400',
        text: 'text-blue-800',
        icon: <InformationCircleIcon className="h-5 w-5 text-blue-400" />,
        focusRing: 'focus:ring-blue-600 focus:ring-offset-blue-50'
      }
    };
    
    return configs[type] || configs.info;
  };

  return (
    <div className="fixed top-4 right-4 z-50 space-y-2 w-80 max-w-full">
      {alerts.map((alert) => {
        const config = getAlertConfig(alert.type);
        const isVisible = visibleAlerts[alert.id];
        
        return (
          <div
            key={alert.id}
            className={`${config.bg} border rounded-md p-4 transition-all duration-300 ease-in-out transform ${
              isVisible ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0'
            }`}
            role="alert"
          >
            <div className="flex">
              <div className="flex-shrink-0">{config.icon}</div>
              <div className="ml-3">
                <p className={`text-sm font-medium ${config.text}`}>
                  {alert.message}
                </p>
              </div>
              <div className="ml-auto pl-3">
                <div className="-mx-1.5 -my-1.5">
                  <button
                    type="button"
                    className={`inline-flex rounded-md p-1.5 ${config.bg} hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 ${config.focusRing}`}
                    onClick={() => handleClose(alert.id)}
                  >
                    <span className="sr-only">إغلاق</span>
                    <XMarkIcon className="h-5 w-5" aria-hidden="true" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
};

export default Alert;