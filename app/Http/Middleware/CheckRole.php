<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        
        // Nếu là admin thì cho phép truy cập tất cả
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        // Kiểm tra các role khác
        foreach ($roles as $role) {
            if ($user->role->title === $role) {
                return $next($request);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Forbidden - You do not have the required role'
        ], 403);
    }
}
