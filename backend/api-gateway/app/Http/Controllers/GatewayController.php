<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    public function handle(Request $request, string $service, ?string $path = null)
    {
        $serviceMap = [
            'auth'  => config('services.auth_service.url'),
            'users' => config('services.user_service.url'),
        ];

        if (!isset($serviceMap[$service])) {
            return response()->json([
                'status' => false,
                'message' => 'Service not found'
            ], 404);
        }

        // IMPORTANT: keep service prefix (auth / users)
        $url = $serviceMap[$service] . '/' . $service;

        if ($path) {
            $url .= '/' . $path;
        }

        $response = Http::withHeaders(
            collect($request->headers->all())
                ->map(fn ($v) => $v[0])
                ->toArray()
        )->send(
            $request->method(),
            $url,
            [
                'query' => $request->query(),
                'json'  => $request->all()
            ]
        );

        return response()->json(
            $response->json(),
            $response->status()
        );
    }
}
