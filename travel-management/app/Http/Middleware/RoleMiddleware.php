<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware{
    public function handle($request, Closure $next, $role)
{
    if (auth()->check() && auth()->user()->role === $role) {
        return $next($request);
    }
    abort(403, 'Unauthorized');
}
}

