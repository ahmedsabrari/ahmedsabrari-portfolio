<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Notifications\Auth\UserBlockedNotification;
use App\Notifications\Auth\UserUnblockedNotification;
use App\Mail\Auth\AccountStatusEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * الحصول على جميع المستخدمين
     */
    public function getAllUsers(array $filters = [])
    {
        try {
            $query = $this->userRepository->getQuery();
            
            // تطبيق الفلاتر إذا وجدت
            if (!empty($filters['role'])) {
                $query->where('role', $filters['role']);
            }
            
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                });
            }
            
            return $query->orderBy('created_at', 'desc')->paginate(10);
        } catch (\Exception $e) {
            Log::error('UserService getAllUsers error: ' . $e->getMessage());
            throw new \Exception('فشل في جلب المستخدمين: ' . $e->getMessage());
        }
    }

    /**
     * الحصول على مستخدم بواسطة ID
     */
    public function getUserById(int $id): User
    {
        try {
            $user = $this->userRepository->find($id);
            
            if (!$user) {
                throw new \Exception('المستخدم غير موجود');
            }
            
            return $user;
        } catch (\Exception $e) {
            Log::error('UserService getUserById error: ' . $e->getMessage());
            throw new \Exception('فشل في جلب بيانات المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function updateUser(User $user, array $data): User
    {
        try {
            // إذا كانت هناك كلمة مرور جديدة، نقوم بتشفيرها
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            
            return $this->userRepository->update($user, $data);
        } catch (\Exception $e) {
            Log::error('UserService updateUser error: ' . $e->getMessage());
            throw new \Exception('فشل في تحديث بيانات المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * حذف المستخدم
     */
    public function deleteUser(User $user): bool
    {
        try {
            // لا يمكن حذف المستخدم المسؤول الرئيسي
            if ($user->email === 'admin@gmail.com') {
                throw new \Exception('لا يمكن حذف المستخدم المسؤول الرئيسي');
            }
            
            return $this->userRepository->delete($user);
        } catch (\Exception $e) {
            Log::error('UserService deleteUser error: ' . $e->getMessage());
            throw new \Exception('فشل في حذف المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * حظر المستخدم مع إرسال إشعار
     */
    public function blockUser(User $user, string $reason = null): User
    {
        try {
            // لا يمكن حظر المستخدم المسؤول الرئيسي
            if ($user->email === 'admin@gmail.com') {
                throw new \Exception('لا يمكن حظر المستخدم المسؤول الرئيسي');
            }
            
            $updatedUser = $this->userRepository->update($user, ['status' => 'blocked']);
            
            // إرسال إشعار الحظر
            $user->notify(new UserBlockedNotification($reason));
            // إرسال بريد الحظر
            Mail::to($user->email)->send(new AccountStatusEmail($user, 'blocked', $reason));

            return $updatedUser;
        } catch (\Exception $e) {
            Log::error('UserService blockUser error: ' . $e->getMessage());
            throw new \Exception('فشل في حظر المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * فك حظر المستخدم مع إرسال إشعار
     */
    public function unblockUser(User $user): User
    {
        try {
            $updatedUser = $this->userRepository->update($user, ['status' => 'active']);
            
            // إرسال إشعار فك الحظر
            $user->notify(new UserUnblockedNotification());
            // إرسال بريد فك الحظر
            Mail::to($user->email)->send(new AccountStatusEmail($user, 'active'));
            
            return $updatedUser;
        } catch (\Exception $e) {
            Log::error('UserService unblockUser error: ' . $e->getMessage());
            throw new \Exception('فشل في فك حظر المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        try {
            // التحقق من كلمة المرور الحالية
            if (!Hash::check($currentPassword, $user->password)) {
                throw new \Exception('كلمة المرور الحالية غير صحيحة');
            }
            
            // تحديث كلمة المرور
            $this->userRepository->update($user, [
                'password' => Hash::make($newPassword)
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('UserService changePassword error: ' . $e->getMessage());
            throw new \Exception('فشل في تغيير كلمة المرور: ' . $e->getMessage());
        }
    }

    /**
     * الحصول على إحصائيات المستخدمين
     */
    public function getUsersStats(): array
    {
        try {
            $totalUsers = $this->userRepository->count();
            $activeUsers = $this->userRepository->countByStatus('active');
            $blockedUsers = $this->userRepository->countByStatus('blocked');
            $adminUsers = $this->userRepository->countByRole('admin');
            $visitorUsers = $this->userRepository->countByRole('visitor');
            
            return [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'blocked' => $blockedUsers,
                'admins' => $adminUsers,
                'visitors' => $visitorUsers,
            ];
        } catch (\Exception $e) {
            Log::error('UserService getUsersStats error: ' . $e->getMessage());
            throw new \Exception('فشل في جلب إحصائيات المستخدمين: ' . $e->getMessage());
        }
    }
}