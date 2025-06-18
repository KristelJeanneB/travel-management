use Illuminate\Support\Facades\Http;

public function rules()
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',  [
                'secret' => config('services.recaptcha.secret'),
                'response' => $value,
            ]);

            if (! $response->json('success')) {
                $fail('The CAPTCHA was not verified.');
            }
        }],
    ];
}