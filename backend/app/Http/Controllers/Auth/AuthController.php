<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * تسجيل مستخدم جديد
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());

            return response()->json([
                'message' => 'تم تسجيل المستخدم بنجاح',
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => $result['token_type']
            ], 201);
        } catch (\Exception $e) {
            Log::error('AuthController register error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في عملية التسجيل',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * تسجيل الدخول
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح',
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => $result['token_type']
            ], 200);
        } catch (\Exception $e) {
            Log::error('AuthController login error: ' . $e->getMessage());
            
            $statusCode = $e->getMessage() === 'بيانات الاعتماد غير صحيحة' ? 401 : 500;
            
            return response()->json([
                'message' => $e->getMessage(),
                'error' => config('app.debug') && $statusCode === 500 ? $e->getMessage() : null
            ], $statusCode);
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return response()->json([
                'message' => 'تم تسجيل الخروج بنجاح'
            ], 200);
        } catch (\Exception $e) {
            Log::error('AuthController logout error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في عملية تسجيل الخروج',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * الحصول على بيانات المستخدم الحالي
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'لم يتم العثور على المستخدم'
                ], 404);
            }

            return response()->json([
                'user' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            Log::error('AuthController me error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في جلب بيانات المستخدم',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * تحديث token المصادقة
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $result = $this->authService->refreshToken($request->user());

            return response()->json([
                'token' => $result['token'],
                'token_type' => $result['token_type']
            ], 200);
        } catch (\Exception $e) {
            Log::error('AuthController refresh error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في تحديث التوكن',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }
}