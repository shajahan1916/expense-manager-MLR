<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\AuthUser;


class AuthController extends Controller
{
    /**
     * LOGIN
     * email OR mobile + password
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required',
            'password' => 'required'
        ]);

        // Call user-service to validate credentials
        $response = Http::post(
            config('services.user_service.url') . '/internal/auth/validate',
            $request->only('login', 'password')
        );

        if (!$response->ok() || !$response['status']) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = $response['user'];

        // Check user status
        if ($user['status'] !== 'active' || $user['is_deleted'] == 1) {
            return response()->json([
                'status'  => false,
                'message' => 'User blocked or deleted'
            ], 403);
        }

        // Generate JWT
        $authUser = new AuthUser(
            $user['guid'],
            $user['role']
        );

        $token = JWTAuth::fromUser($authUser);

        return response()->json([
            'status' => true,
            'token'  => $token,
            'user'   => [
                'guid'       => $user['guid'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'email'      => $user['email'],
                'phone'      => $user['phone'],
                'role'       => $user['role']
            ]
        ]);
    }

    /**
     * SPLASH LOGIN
     * Used when mobile app reopens
     */
    public function splashLogin(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        try {
            // Decode existing token
            $payload = JWTAuth::setToken($request->token)->getPayload();
            $guid = $payload->get('guid');
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Session expired'
            ], 401);
        }

        // Validate user status again via user-service
        $response = Http::post(
            config('services.user_service.url') . '/internal/auth/validate-guid',
            ['guid' => $guid]
        );

        if (!$response->ok() || !$response['status']) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found'
            ], 401);
        }

        $user = $response['user'];

        // If user blocked or deleted → force logout
        if ($user['status'] !== 'active' || $user['is_deleted'] == 1) {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'status'  => false,
                'message' => 'User blocked or deleted'
            ], 403);
        }

        // Invalidate old token
        JWTAuth::invalidate($request->token);

        // Issue new token
        $authUser = new AuthUser(
            $user['guid'],
            $user['role']
        );

        $newToken = JWTAuth::fromUser($authUser);

        return response()->json([
            'status' => true,
            'token'  => $newToken,
            'user'   => [
                'guid'       => $user['guid'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'email'      => $user['email'],
                'phone'      => $user['phone'],
                'role'       => $user['role']
            ]
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        try {
            JWTAuth::setToken($request->token)->invalidate();
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid token'
            ], 400);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
