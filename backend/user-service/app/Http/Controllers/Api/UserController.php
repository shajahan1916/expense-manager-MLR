<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users (not deleted)
     */
    public function index()
    {
        $users = User::where('is_deleted', 0)->get();

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    /**
     * Get single user by GUID
     */
    public function show(string $guid)
    {
        $user = User::where('guid', $guid)
            ->where('is_deleted', 0)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    /**
     * Create new user
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Update user details
     */
    public function update(UpdateUserRequest $request, string $guid)
    {
        $user = User::where('guid', $guid)
            ->where('is_deleted', 0)
            ->firstOrFail();

        $user->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(ChangePasswordRequest $request, string $guid)
    {
        $user = User::where('guid', $guid)
            ->where('is_deleted', 0)
            ->firstOrFail();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Block or activate user
     */
    public function updateStatus(Request $request, string $guid)
    {
        $request->validate([
            'status' => 'required|in:active,blocked'
        ]);

        $user = User::where('guid', $guid)
            ->where('is_deleted', 0)
            ->firstOrFail();

        $user->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User status updated successfully'
        ]);
    }

    /**
     * Soft delete user
     */
    public function destroy(string $guid)
    {
        $user = User::where('guid', $guid)
            ->where('is_deleted', 0)
            ->firstOrFail();

        $user->update([
            'is_deleted' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
