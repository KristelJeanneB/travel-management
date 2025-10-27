<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PosoAuthController extends Controller
{
    /**
     * Show the Poso registration form.
     */
    public function showRegistrationForm(): \Illuminate\View\View
    {
        return view('auth.poso-register');
    }

    /**
     * Handle Poso registration request.
     */
    public function register(Request $request)
    {
        return $this->storePoso($request);
    }

    /**
     * Store a new Poso user with 'poso' role.
     */
    public function storePoso(Request $request)
    {
        // Validate input: only official @poso.gov.ph emails allowed
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|regex:/@poso\.gov\.ph$/i',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.regex' => 'Only official @poso.gov.ph emails are allowed.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Create the user with 'poso' role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'poso', // Assign Poso role
        ]);

        // Auto-login the user
        auth()->login($user);

        return redirect()->route('poso.dashboard')
                         ->with('success', 'Welcome, Poso personnel! Your account is verified.');
    }
}
