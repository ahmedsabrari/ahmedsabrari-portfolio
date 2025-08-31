<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Register API
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:admin,visitor'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    // Login API
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::warning('Login attempt for non-existent user', ['email' => $request->email]);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Invalid password attempt', ['email' => $request->email]);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Login failed', 'error' => $e->getMessage()], 500);
        }
    }

// Logout API
public function logout(Request $request)
{
    try {
        // تحقق من وجود مستخدم مصادق
        if (!$request->user()) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        // حذف token المستخدم الحالي
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Logout failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
}