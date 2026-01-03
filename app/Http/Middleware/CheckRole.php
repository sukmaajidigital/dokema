<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Supports both single role and multiple roles separated by comma
     * Usage: middleware('role:hr') or middleware('role:pembimbing,hr')
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Support multiple roles separated by comma
        $allowedRoles = explode(',', $roles);

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Unauthorized access. Your role does not have permission to access this resource.');
        }

        return $next($request);
    }
}
