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
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

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
            </div>

        
            <div class="form-group checkbox-forgot-wrapper">
                <label class="remember-me">
                    <input type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn">Log In</button>

            <p class="or-text">or</p>

            <p style="text-align:center; margin-top:10px;">
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            </p>

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

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('alert');
        if (!alertBox) return;

        setTimeout(() => {
            alertBox.style.opacity = '0';
            setTimeout(() => alertBox.remove(), 500); 
        }, 15500); 

        
        alertBox.addEventListener('click', () => {
            alertBox.style.opacity = '0';
            setTimeout(() => alertBox.remove(), 500);
        });
    });
</script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
