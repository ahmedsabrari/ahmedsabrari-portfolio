<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // Register API
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message'=>'User registered successfully',
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Registration failed', 
                'error' => config('app.debug')? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    // Login API
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                Log::warning('Invalid login attempt', ['email' => $validated['email']]);
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => new UserResource($user),
                'token' => $token,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Login failed', 
                'error' => config('app.debug')? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

// Logout API
public function logout(Request $request): JsonResponse
{
    try {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Not authenticated'
            ], 401);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Logout error', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Logout failed',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
        ], 500);
    }
}

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
}