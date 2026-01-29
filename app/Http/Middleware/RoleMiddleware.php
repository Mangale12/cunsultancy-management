<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        // Super admin has access to everything - check multiple ways
        if ($user->hasRole('super_admin') || $user->isSuperadmin()) {
            return $next($request);
        }

        foreach ($roles as $role) {
            $role = trim((string) $role);

            if ($role === 'super_admin' && ($user->hasRole('super_admin') || $user->isSuperadmin())) {
                return $next($request);
            }

            if ($role === 'superadmin' && ($user->hasRole('super_admin') || $user->isSuperadmin())) {
                return $next($request);
            }

            if ($role === 'branch_admin' && $user->isBranchAdmin()) {
                return $next($request);
            }

            if ($role === 'employee' && $user->employee) {
                return $next($request);
            }

            if ($role === 'agent' && $user->agent) {
                return $next($request);
            }

            if ($role === 'student' && $user->student) {
                return $next($request);
            }
        }

        abort(403);
    }
}
