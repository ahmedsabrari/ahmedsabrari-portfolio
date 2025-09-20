<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // التحقق من وجود مستخدم مصادق
        if (!Auth::check()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول. يرجى تسجيل الدخول أولاً.'
            ], 401);
        }

        $user = Auth::user();

        // التحقق من حالة المستخدم
        if ($user->status === 'blocked') {
            return response()->json([
                'message' => 'الحساب معطل. لا يمكنك الوصول إلى هذا المورد.'
            ], 403);
        }

        // التحقق من الصلاحيات إذا تم تحديد أدوار
        if (!empty($roles)) {
            // استخدام Spatie Permission إذا كان مثبتاً
            if (method_exists($user, 'hasAnyRole')) {
                if (!$user->hasAnyRole($roles)) {
                    return $this->denyAccess();
                }
            } 
            // استخدام النظام البسيط إذا لم يكن Spatie مثبتاً
            else {
                if (!in_array($user->role, $roles)) {
                    return $this->denyAccess();
                }
            }
        }

        return $next($request);
    }

    /**
     * Handle access denial.
     */
    protected function denyAccess(): Response
    {
        return response()->json([
            'message' => 'غير مصرح بالوصول. ليس لديك الصلاحية الكافية.',
            'hint' => 'يجب أن تمتلك الدور المناسب للوصول إلى هذا المورد.'
        ], 403);
    }

    /**
     * التحقق من صلاحية محددة باستخدام Spatie Permission
     */
    public function checkPermission(Request $request, Closure $next, $permission): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول. يرجى تسجيل الدخول أولاً.'
            ], 401);
        }

        $user = Auth::user();

        // التحقق من الصلاحية إذا كان Spatie مثبتاً
        if (method_exists($user, 'can')) {
            if (!$user->can($permission)) {
                return $this->denyAccess();
            }
        } else {
            return response()->json([
                'message' => 'نظام الصلاحيات غير مثبت بشكل صحيح.'
            ], 500);
        }

        return $next($request);
    }
}