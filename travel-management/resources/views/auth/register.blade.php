<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="background-image"></div>

    <div class="form-container">
        <h2>Create Account</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                @error('firstname')
                    <div class="error" style="color:red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                @error('lastname')
                    <div class="error" style="color:red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="username">Email:</label>
                <input type="email" id="username" name="username" value="{{ old('username') }}" required>
                @error('username')
                    <div class="error" style="color:red;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="eye-icon fas fa-eye-slash"></i>
                @error('password')
                    <div class="error" style="color:red;">{{ $message }}</div>
                @enderror
                <small>Password must be at least 8 characters and include at least one special character.</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <i class="eye-icon fas fa-eye-slash"></i>
                @error('password_confirmation')
                    <div class="error" style="color:red;">{{ $message }}</div>
                @enderror
            </div>

            <!--Math Captcha-->
            <div class="form-group">
                <label>{{ $math_question }} = ?</label>
                <input type="number" name="captcha_answer" value="{{ old('captcha_answer') }}" required>
                @error('captcha_answer')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
            
            <p style="text-align:center; margin-top:10px;">
                Already have an account?
                <a href="{{ route('login') }}">Log In</a>
            </p>
        </form>
    </div>

    <!-- reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Password Toggle Script -->
    <script>
        document.querySelectorAll('.eye-icon').forEach(icon => {
            const input = icon.previousElementSibling;

            icon.addEventListener('click', () => {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });
    </script>

</body>
</html>
