<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If the user is not logged in or not a super admin, abort
        if (!$user || !$user->is_superadmin) {
            abort(403, 'Unauthorized access.');
        }

        // Allow the request to proceed
        return $next($request);
    }
}
