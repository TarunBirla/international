<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (empty($roles)) {
            return $next($request);
        }

        // Super admin has access to everything
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Admin has access to admin role and lower
        if ($user->role === 'admin' && in_array('admin', $roles)) {
            return $next($request);
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access to this portal.');
    }
}
