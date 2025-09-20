<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Resources\Auth\UserResource;
use App\Http\Services\Auth\UserService;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\BlockUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * عرض جميع المستخدمين (للمسؤولين فقط)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('viewAny', User::class);

            $users = $this->userService->getAllUsers($request->all());

            return response()->json([
                'users' => UserResource::collection($users),
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                ]
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بالوصول إلى قائمة المستخدمين'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController index error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في جلب المستخدمين',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * عرض مستخدم معين
     */
    public function show(User $user): JsonResponse
    {
        try {
            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('view', $user);

            return response()->json([
                'user' => new UserResource($user)
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بالوصول إلى بيانات هذا المستخدم'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController show error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في جلب بيانات المستخدم',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * تحديث الملف الشخصي للمستخدم الحالي
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $validated = $request->validate();

            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('update', $request->user());

            $user = $this->userService->updateUser($request->user(), $validated);

            return response()->json([
                'message' => 'تم تحديث الملف الشخصي بنجاح',
                'user' => new UserResource($user)
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بتحديث الملف الشخصي'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController updateProfile error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في تحديث الملف الشخصي',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * حذف حساب المستخدم الحالي
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        try {
            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('delete', $request->user());

            $this->userService->deleteUser($request->user());

            return response()->json([
                'message' => 'تم حذف الحساب بنجاح'
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بحذف الحساب'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController deleteAccount error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في حذف الحساب',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * حظر مستخدم (للمسؤولين فقط)
     */
    public function blockUser(BlockUserRequest $request, User $user): JsonResponse
    {
        try {
            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('block', $user);

            $reason = $request->input('reason');
            $updatedUser = $this->userService->blockUser($user, $reason);

            return response()->json([
                'message' => 'تم حظر المستخدم بنجاح',
                'user' => new UserResource($updatedUser)
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بحظر المستخدمين'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController blockUser error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في حظر المستخدم',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * فك حظر مستخدم (للمسؤولين فقط)
     */
    public function unblockUser(User $user): JsonResponse
    {
        try {
            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('unblock', $user);

            $updatedUser = $this->userService->unblockUser($user);

            return response()->json([
                'message' => 'تم فك حظر المستخدم بنجاح',
                'user' => new UserResource($updatedUser)
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بفك حظر المستخدمين'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController unblockUser error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في فك حظر المستخدم',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(UpdateProfileRequest  $request): JsonResponse
    {
        try {
            $validated = $request->validate();

            // التحقق من الصلاحية - يمكن للمستخدم تغيير كلمة مروره الخاصة فقط
            $this->authorize('update', $request->user());

            $this->userService->changePassword(
                $request->user(),
                $validated['current_password'],
                $validated['new_password']
            );

            return response()->json([
                'message' => 'تم تغيير كلمة المرور بنجاح'
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بتغيير كلمة المرور'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController changePassword error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في تغيير كلمة المرور',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * الحصول على إحصائيات المستخدمين (للمسؤولين فقط)
     */
    public function stats(): JsonResponse
    {
        try {
            // التحقق من الصلاحية باستخدام السياسة
            $this->authorize('viewStats', User::class);

            $stats = $this->userService->getUsersStats();

            return response()->json([
                'stats' => $stats
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'message' => 'غير مصرح بالوصول إلى إحصائيات المستخدمين'
            ], 403);
        } catch (\Exception $e) {
            Log::error('UserController stats error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'فشل في جلب الإحصائيات',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }
}