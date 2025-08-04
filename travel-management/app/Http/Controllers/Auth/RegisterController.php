<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Show the registration form with a math CAPTCHA.
     */
    public function showRegistrationForm()
    {
        $captcha = $this->generateMathQuestion();

        Session::put('math_captcha_answer', $captcha['answer']);

        return view('auth.register', [
            'math_question' => $captcha['question']
        ]);
    }

    public function register(Request $request)
    {
        $messages = [
            'password.regex' => 'Password must contain at least one special character (e.g. @, #, $, etc.).',
        ];

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[@$!%*#?&]/'
            ],
            'captcha_answer' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validate CAPTCHA
        $expected = Session::get('math_captcha_answer');
        $given = (int) $request->captcha_answer;

        if ($given !== $expected) {
            return back()->withErrors([
                'captcha_answer' => 'Incorrect answer to the math question.'
            ])->withInput($request->except('captcha_answer'));
        }

        // Create user
        User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
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
                    [$a, $b] = [$b, $a]; // Swap to avoid negative
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