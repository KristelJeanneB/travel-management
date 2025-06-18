<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Register</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form action="{{ url('register') }}" method="POST">
        @csrf

        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Password:</label>
            <input type="password" name="password">
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation">
        </div>

        <div>
            <button type="submit">SignUp</button>
        </div>
    </form>
</body>
</html>

<div class="mt-4">
    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
</div>
<script src="https://www.google.com/recaptcha/api.js"  async defer></script>