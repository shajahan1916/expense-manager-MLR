<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    public function handle(Request $request, string $service, ?string $path = null)
    {
        // Map logical service names to internal URLs
        $serviceMap = [
            'auth'  => config('services.auth_service.url'),
            'users' => config('services.user_service.url'),
        ];

        if (!isset($serviceMap[$service])) {
            return response()->json([
                'status'  => false,
                'message' => 'Service not found'
            ], 404);
        }

        /*
         |--------------------------------------------------------------------------
         | Build Internal Service URL
         |--------------------------------------------------------------------------
         | Example:
         | Gateway:  /api/users
         | Internal: http://localhost:8002/users
         */
        $url = rtrim($serviceMap[$service], '/') . '/' . $service;

        if ($path) {
            $url .= '/' . $path;
        }

        /*
         |--------------------------------------------------------------------------
         | Forward Request with Headers
         |--------------------------------------------------------------------------
         | - Authorization header preserved
         | - Content-Type preserved
         | - Query params preserved
         | - JSON body preserved
         */
        $headers = collect($request->headers->all())
            ->map(fn ($value) => $value[0])
            ->toArray();

        $response = Http::withHeaders($headers)->send(
            $request->method(),
            $url,
            [
                'query' => $request->query(),
                'json'  => $request->all(),
            ]
        );

        /*
         |--------------------------------------------------------------------------
         | Return Response As-Is
         |--------------------------------------------------------------------------
         */
        return response()->json(
            $response->json(),
            $response->status()
        );
    }
}
