<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\FailedLogin;

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
        // Load admin and superadmin credentials from .env
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        $superAdminEmail = env('SUPERADMIN_EMAIL');
        $superAdminPassword = env('SUPERADMIN_PASSWORD');

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'captcha_answer' => 'required|integer',
        ]);

        // CAPTCHA validation
        $expectedAnswer = Session::get('math_captcha_answer');
        $givenAnswer = (int) $request->captcha_answer;

        if ($givenAnswer !== $expectedAnswer) {
            return back()->withErrors([
                'captcha_answer' => 'Incorrect answer to the math question.'
            ])->withInput($request->except('captcha_answer'));
        }

        // Check Super Admin credentials
        if ($request->email === $superAdminEmail && $request->password === $superAdminPassword) {
            $superAdmin = User::firstOrCreate(
                ['email' => $superAdminEmail],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make($superAdminPassword),
                    'is_admin' => true,
                    'is_superadmin' => true,
                ]
            );

            Auth::login($superAdmin);
            $request->session()->regenerate();
            return redirect()->route('superadmin.dashboard');
        }

        // Check Admin credentials
        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            $admin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make($adminPassword),
                    'is_admin' => true,
                    'is_superadmin' => false,
                ]
            );

            Auth::login($admin);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Default login attempt for regular users
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_superadmin) {
                return redirect()->route('superadmin.dashboard');
            }

            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }
             if ($user->role === 'poso') { // ✅ check POSO role
                return redirect()->route('poso.dashboard');
    }

            return redirect()->intended('/home');
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        FailedLogin::create([
            'email' => $request->input('email'),
            'ip_address' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('register');
    }

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
