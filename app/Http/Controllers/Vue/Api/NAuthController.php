<?php

namespace App\Http\Controllers\Vue\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NAuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully',
        ], 201);
    }

    // Login
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }

    //     // Delete old tokens (optional for security)
    //     $user->tokens()->delete();

    //     // Create new token
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login successful',
    //         'token'  => $token,
    //         'user'   => [
    //             'id'    => $user->id,
    //             'name'  => $user->name,
    //             'email' => $user->email,
    //         ],
    //     ]);
    // }

    // // Logout
    // public function logout(Request $request)
    // {
    //     $user = $request->user();

    //     if ($user) {
    //         $user->currentAccessToken()->delete(); // delete only current token
    //     }

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Logged out successfully',
    //     ]);
    // }

    // // Dashboard
    // public function dashboard(Request $request)
    // {
    //     $user = $request->user();
    //     Log::info('user found');
    //     return response()->json([
    //         'user' => [
    //             'id'    => $user->id,
    //             'name'  => $user->name,
    //             'email' => $user->email,
    //         ],
    //     ]);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function dashboard(Request $request)
    {
        try {
            $user = $request->user(); // authenticated user
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            // Metrics
            $metrics = [
                'users' => User::count(),
                'sessions' => 120, // Example, replace with real session count
                'requests' => 300, // Example, replace with real request count
            ];

            // Recent 5 users
            $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'status', 'created_at']);

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status,
                ],
                'metrics' => $metrics,
                'recentUsers' => $recentUsers,
            ]);
        } catch (\Exception $e) {
            Log::error("Dashboard error: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to load dashboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function toggleStatus(User $user, Request $request)
    {
        // $this->authorize('update', $user); // optional if policies

        Log::info('status');
        Log::alert('request', $request->all());
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);

        Log::alert('validated', $validated);
        $user->status = $validated['status'];
        $user->save();

        Log::alert('validated', $user->toArray());
        return response()->json([
            'message' => 'Status updated',
            'user' => $user,
        ]);
    }

    // public function destroy(User $user)
    // {
    //     if (auth()->id() === $user->id) {
    //         return response()->json(['message' => 'You cannot delete yourself'], 403);
    //     }

    //     $user->delete();

    //     return response()->json(['message' => 'User deleted']);
    // }


    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }

    public function index()
    {
        return User::latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'status'   => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json(['message' => 'User created', 'user' => $user], 201);
    }

    // public function show(User $user)
    // {
    //     return response()->json(['user' => $user]);
    // }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'status'   => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    public function destroy(User $user, Request $request)
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'You cannot delete yourself'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}

