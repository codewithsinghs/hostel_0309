<?php

namespace App\Http\Controllers\Vue\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class NAuthController extends Controller
{
    // Register
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'email' => 'required|string|email|unique:users,email',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     $user = User::create([
    //         'name'     => $request->name,
    //         'email'    => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User registered successfully',
    //     ], 201);
    // }

    // // Login
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }

    //     $user = Auth::user();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login successful',
    //         'token' => $token,
    //         'user'  => $user,
    //     ]);
    // }

    // Dashboard (Protected)
    // public function dashboard(Request $request)
    // {
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Welcome to dashboard',
    //         'user' => $request->user(),
    //     ]);
    // }

    // // Logout
    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Logged out successfully',
    //     ]);
    // }

    // In NAuthController
    // public function dashboard(Request $request)
    // {
    //     $user = $request->user();

    //     return response()->json([
    //         'user' => [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //         ],
    //     ]);
    // }

    // public function dashboard(Request $request)
    // {
    //     $user = $request->user();

    //     if (!$user) {
    //         Log::error('Sanctum user not found. Token may be invalid.');
    //         return response()->json(['message' => 'Unauthenticated'], 401);
    //     }

    //     return response()->json([
    //         'user' => [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //         ],
    //     ]);
    // }


    public function dashboard(Request $request)
    {
        try {
            $user = $request->user(); // Authenticated user

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Metrics (example, can add more)
            $metrics = [
                'users' => User::count(),
                'sessions' => 120, // replace with real session count if available
                'requests' => 300, // replace with real request count if tracked
            ];

            // Recent users (latest 5)
            $recentUsers = User::latest()
                ->take(5)
                ->get(['id', 'name', 'email', 'created_at']);

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'metrics' => $metrics,
                'recentUsers' => $recentUsers,
            ]);
        } catch (\Exception $e) {
            Log::error("Dashboard load failed: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to load dashboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }






    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Logged out successfully',
    //     ]);
    // }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully',
            'token'   => $token,
            'user'    => $user,
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();

        // Optional: Revoke old tokens to enforce single session
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ], 200);
    }

    /**
     * Logout user (invalidate tokens)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
