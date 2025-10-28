<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PosoAuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.poso-register'); // your registration Blade file
    }

    public function storePoso(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|regex:/@poso\.gov\.ph$/|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // ✅ Create new Poso user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'poso', // if you have roles in your table
        ]);

        // ✅ Redirect to login page after successful registration
        return redirect()->route('login')
                         ->with('success', 'Registration successful! You can now log in.');
    }
}
