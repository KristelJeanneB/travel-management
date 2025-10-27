<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    protected function authenticated(Request $request, $user)
{
    if ($user->role === 'poso') {
        return redirect()->intended('/poso/dashboard');
    } elseif ($user->role === 'superadmin') {
        return redirect()->intended('/admin/sensor-locations');
    } elseif ($user->role === 'admin') {
        return redirect()->intended('/map');
    }
    return redirect()->intended('/home');
}
}
