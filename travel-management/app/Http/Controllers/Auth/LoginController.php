<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $captcha = $this->generateMathQuestion();
        Session::put('math_captcha_answer', $captcha['answer']);

        return view('auth.login', [
            'math_question' => $captcha['question']
        ]);
    }

    public function login(Request $request)
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        // admin credentials
        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            $admin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make($adminPassword), 
                    'is_admin' => true,
                ]
            );

            Auth::login($admin);
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'captcha_answer' => 'required|integer',
        ]);

        // Validate CAPTCHA
        $expectedAnswer = Session::get('math_captcha_answer');
        $givenAnswer = (int) $request->captcha_answer;

        if ($givenAnswer !== $expectedAnswer) {
            return back()->withErrors([
                'captcha_answer' => 'Incorrect answer to the math question.'
            ])->withInput($request->except('captcha_answer'));
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->is_admin) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.'
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('register');
    }

    /**
     * Generate a random math question for CAPTCHA.
     */
    private function generateMathQuestion(): array
    {
        $a = rand(1, 10);
        $b = rand(1, 10);
        $operators = ['+', '-', '*'];
        $op = $operators[array_rand($operators)];

        switch ($op) {
            case '+':
                $result = $a + $b;
                $question = "$a + $b";
                break;
            case '-':
                if ($a < $b) [$a, $b] = [$b, $a];
                $result = $a - $b;
                $question = "$a - $b";
                break;
            case '*':
                $result = $a * $b;
                $question = "$a × $b";
                break;
            default:
                $result = $a + $b;
                $question = "$a + $b";
        }

        return [
            'question' => $question,
            'answer' => $result
        ];
    }
}