<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $this->userService->getAllUsers()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch users'
            ], 500);
        }
    }

    public function show(string $guid)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $this->userService->getUserByGuid($guid)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function update(UpdateUserRequest $request, string $guid)
    {
        $user = $this->userService->updateUser($guid, $request->validated());

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function changePassword(ChangePasswordRequest $request, string $guid)
    {
        $this->userService->updateUser($guid, [
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully'
        ]);
    }

    public function updateStatus(Request $request, string $guid)
    {
        $request->validate([
            'status' => 'required|in:active,blocked'
        ]);

        $this->userService->updateUser($guid, [
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User status updated successfully'
        ]);
    }

    public function destroy(string $guid)
    {
        $this->userService->deleteUser($guid);

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
