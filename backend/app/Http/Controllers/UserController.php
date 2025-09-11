<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
       // Get Profile API
public function profile(Request $request): JsonResponse
{
    try{
        if(!$request->user()){
            return response()->json([
                'message' => 'Not authenticated'
            ], 401);
        }
        return response()->json([
            'user' => new UserResource($request->user())
        ], 200);
    } catch(\Exception $e){
        Log::error('Profile retrieval error', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Failed to retrieve profile',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
        ], 500);
    }
}

    // الحصول على جميع المستخدمين (للادمن فقط)
    public function index()
    {
        try {
            $users = User::all();
            return UserResource::collection($users);
        } catch (\Exception $e) {
            Log::error('Get users error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to get users'], 500);
        }
    }

    // تحديث معلومات المستخدم
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            ]);

            $user->update($validated);

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            Log::error('Update profile error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update profile'], 500);
        }
    }

    // حذف الحساب (المستخدم نفسه)
    public function deleteAccount(Request $request)
    {
        try {
            $user = $request->user();
            $user->delete();

            return response()->json([
                'message' => 'Account deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete account error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete account'], 500);
        }
    }

    // حظر مستخدم (للادمن فقط)
    public function blockUser(User $user)
    {
        try {
            $user->update(['status' => 'blocked']);
            
            return response()->json([
                'message' => 'User blocked successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Block user error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to block user'], 500);
        }
    }

    // فك حظر مستخدم (للادمن فقط)
    public function unblockUser(User $user)
    {
        try {
            $user->update(['status' => 'active']);
            
            return response()->json([
                'message' => 'User unblocked successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Unblock user error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to unblock user'], 500);
        }
    }
}
