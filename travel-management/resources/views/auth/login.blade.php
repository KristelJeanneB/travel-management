<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>.error { color: red; }</style>
</head>
<body>
    <h2>Login</h2>

    @if(session('status'))
        <p style="color:green;">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>

<div class="mt-4">
    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
</div>
<script src="https://www.google.com/recaptcha/api.js"  async defer></script>