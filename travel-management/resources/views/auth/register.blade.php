<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    
    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body>

    <div class="form-container">
        <h2>Create account</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="firstname">Firstname:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>

            <div class="form-group">
                <label for="lastname">Lastname:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="eye-icon fas fa-eye-slash"></i>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <i class="eye-icon fas fa-eye-slash"></i>
            </div>

            <!-- reCAPTCHA -->
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
            </div>

            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('login') }}'"> Sign Up</button>
        </form>
    </div>

    <!-- reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js"  async defer></script>

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