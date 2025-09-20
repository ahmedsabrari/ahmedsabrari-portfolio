<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\Auth\AuthRepositoryInterface;
use App\Notifications\Auth\WelcomeNotification;
use App\Mail\Auth\WelcomeEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * تسجيل مستخدم جديد مع إرسال إشعار ترحيب
     */
    public function register(array $data): array
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $user = $this->authRepository->create($data);

            // إنشاء توكن المصادقة
            $token = $user->createToken('authToken');

            // إرسال إشعار الترحيب
            $user->notify(new WelcomeNotification());
            // إرسال بريد الترحيب
            Mail::to($user->email)->send(new WelcomeEmail($user));

            return [
                'user' => $user,
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer'
            ];
        } catch (\Exception $e) {
            Log::error('AuthService register error: ' . $e->getMessage());
            throw new \Exception('فشل في عملية التسجيل: ' . $e->getMessage());
        }
    }

    /**
     * تسجيل الدخول
     */
    public function login(array $credentials): array
    {
        try {
            // البحث عن المستخدم بالبريد الإلكتروني
            $user = $this->authRepository->findByEmail($credentials['email']);

            // التحقق من وجود المستخدم وصحة كلمة المرور
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw new \Exception('بيانات الاعتماد غير صحيحة');
            }

            // التحقق من حالة الحساب
            if ($user->status !== 'active') {
                throw new \Exception('الحساب معطل أو محظور');
            }

            // إنشاء توكن جديد
            $token = $user->createToken('authToken');

            return [
                'user' => $user,
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer'
            ];
        } catch (\Exception $e) {
            Log::error('AuthService login error: ' . $e->getMessage());
            throw new \Exception('فشل في عملية تسجيل الدخول: ' . $e->getMessage());
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(User $user): bool
    {
        try {
            // حذف جميع توكنات المستخدم
            $user->tokens()->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('AuthService logout error: ' . $e->getMessage());
            throw new \Exception('فشل في عملية تسجيل الخروج: ' . $e->getMessage());
        }
    }

    /**
     * تحديث توكن المصادقة
     */
    public function refreshToken(User $user): array
    {
        try {
            // حذف التوكن الحالي
            $user->currentAccessToken()->delete();

            // إنشاء توكن جديد
            $token = $user->createToken('authToken');

            return [
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer'
            ];
        } catch (\Exception $e) {
            Log::error('AuthService refreshToken error: ' . $e->getMessage());
            throw new \Exception('فشل في تحديث التوكن: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من صحة التوكن
     */
    public function validateToken(User $user): bool
    {
        try {
            return $user->currentAccessToken() !== null;
        } catch (\Exception $e) {
            Log::error('AuthService validateToken error: ' . $e->getMessage());
            return false;
        }
    }
}