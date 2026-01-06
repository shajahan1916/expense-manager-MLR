<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyJwt
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                'message' => 'Authorization token missing'
            ], 401);
        }

        return $next($request);
    }
}
