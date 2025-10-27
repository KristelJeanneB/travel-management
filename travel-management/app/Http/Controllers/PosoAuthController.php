<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PosoAuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.poso-register');
    }

    public function register(Request $request)
    {
        // Validate: only official Poso emails allowed
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

        // Create user with 'poso' role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'poso', // 👮 Assign Poso role
        ]);

        // Auto-login (optional)
        auth()->login($user);

        return redirect()->route('poso.dashboard')
            ->with('success', 'Welcome, Poso personnel! Your account is verified.');
    }
}