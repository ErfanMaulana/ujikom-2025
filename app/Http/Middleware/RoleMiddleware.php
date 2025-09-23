<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has a valid role
        if (!$user || !$user->role) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'User role tidak valid');
        }

        $userRole = $user->role;

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }
            
            // Redirect to appropriate dashboard based on user role
            switch ($userRole) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('error', 'Akses tidak diizinkan');
                case 'pemilik':
                    return redirect()->route('pemilik.dashboard')->with('error', 'Akses tidak diizinkan');
                case 'penyewa':
                    return redirect()->route('penyewa.dashboard')->with('error', 'Akses tidak diizinkan');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Role tidak valid');
            }
        }

        return $next($request);
    }
}
