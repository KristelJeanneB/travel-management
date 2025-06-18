<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">    
</head>
<body>
<div class="background-image"></div>

    <div class="form-container">
        <h2>Log in</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <div class="form-group remember-me">
                <input type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember_me">Remember me</label>
            </div>

            <p class="or-text">or</p>

            <button type="submit" class="btn">Log In</button>

            <div class="social-logins">
                <a href="#" class="social-login"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-login"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-login"><i class="fas fa-globe"></i></a>
                <a href="#" class="social-login"><i class="fab fa-google"></i></a>
            </div>
        </form>

        <!-- reCAPTCHA -->
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js"  async defer></script>
</body>
</html>