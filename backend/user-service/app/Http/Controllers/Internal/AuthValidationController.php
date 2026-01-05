<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthValidationController extends Controller
{
    public function validateCredentials(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $user = User::where(function ($q) use ($request) {
                $q->where('email', $request->login)
                  ->orWhere('phone', $request->login);
            })
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'user' => [
                'guid' => $user->guid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'is_deleted' => $user->is_deleted
            ]
        ]);
    }

    public function validateGuid(Request $request)
    {
        $request->validate([
            'guid' => 'required'
        ]);

        $user = User::where('guid', $request->guid)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'user' => [
                'guid' => $user->guid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'is_deleted' => $user->is_deleted
            ]
        ]);
    }
}
