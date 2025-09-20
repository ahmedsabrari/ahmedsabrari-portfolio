<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        try {
            // محاولة المصادقة
            $this->authenticate($request, $guards);
            
            // التحقق من حالة المستخدم
            $user = Auth::user();
            
            if ($user && $user->status === 'blocked') {
                Auth::logout();
                return response()->json([
                    'message' => 'الحساب معطل. يرجى التواصل مع المسؤول.'
                ], 403);
            }
            
            return $next($request);
            
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // معالجة استثناءات المصادقة
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'غير مصرح بالوصول. يرجى تسجيل الدخول.'
                ], 401);
            }
            
            return redirect()->guest(route('login'));
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
        
        return null;
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     */
    protected function authenticate($request, array $guards): void
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);
                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }
}