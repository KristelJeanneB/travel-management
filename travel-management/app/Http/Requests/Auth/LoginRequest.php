use Illuminate\Support\Facades\Http;

public function rules()
{
    return [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
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