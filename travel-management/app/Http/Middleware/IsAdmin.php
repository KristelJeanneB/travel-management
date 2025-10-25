<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $envAdminEmail = env('ADMIN_EMAIL');

        // If not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if the user is admin in DB or matches the ENV admin
        if (!$user->is_admin && $user->email !== $envAdminEmail) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
