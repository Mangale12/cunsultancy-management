<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchScopeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        if ($user->isSuperadmin()) {
            return $next($request);
        }

        if ($user->employee && $user->employee->branch_id) {
            $request->attributes->set('branch_id', $user->employee->branch_id);
            return $next($request);
        }

        abort(403);
    }
}
