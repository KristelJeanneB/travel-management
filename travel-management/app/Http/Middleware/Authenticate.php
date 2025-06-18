public function handle($request, $next, $guard = null)
{
    if (Auth::guard($guard)->check()) {
        return redirect()->route('home'); // ← Must match your route name
    }

    return $next($request);
}