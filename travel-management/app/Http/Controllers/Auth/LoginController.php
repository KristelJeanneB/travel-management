<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //show LogIn
    public function showLoginForm()
    {
        $captcha = $this->generateMathQuestion();

        // Store correct answer in session
        Session::put('math_captcha_answer', $captcha['answer']);

        return view('auth.login', [
            'math_question' => $captcha['question']
        ]);
    }

    /**
     * Handle login request with CAPTCHA validation.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'captcha_answer' => 'required|integer',
        ]);

        // Validate CAPTCHA first
        $expectedAnswer = Session::get('math_captcha_answer');
        $givenAnswer = (int) $request->captcha_answer;

        if ($givenAnswer !== $expectedAnswer) {
            return back()->withErrors([
                'captcha_answer' => 'Incorrect answer to the math question.'
            ])->withInput($request->except('captcha_answer'));
        }

        // Attempt login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
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
     *
     * @return array ['question' => '5 + 3', 'answer' => 8]
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
                if ($a < $b) {
                    [$a, $b] = [$b, $a]; 
                }
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