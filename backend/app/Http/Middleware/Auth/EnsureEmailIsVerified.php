<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $redirectToRoute = null): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'غير مصرح بالوصول. يرجى تسجيل الدخول أولاً.'
            ], 401);
        }

        $user = Auth::user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'يجب تأكيد البريد الإلكتروني قبل الوصول إلى هذا المورد.',
                'verified' => false
            ], 403);
        }

        return $next($request);
    }
}