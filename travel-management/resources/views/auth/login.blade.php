<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .background-image {
            background-image: url("/images/background.png"); 
            background-size: cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Alert styling */
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            animation: fadeInOut 15s ease-in-out;
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.95);
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.95);
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Frosted glass form container */
        .form-container {
            position: relative;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            font-size: 2.2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .form-group input {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-group input:focus {
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }

        /* Password toggle */
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s ease;
            font-size: 1.2rem;
        }

        .eye-icon:hover {
            color: white;
        }

        /* Remember me and forgot password */
        .checkbox-forgot-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .remember-me {
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #3498db;
            cursor: pointer;
        }

        .forgot-password {
            color: white;
            text-decoration: none;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #3498db;
            text-decoration: underline;
        }

        /* Captcha styling */
        .captcha-container {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .captcha-container label {
            font-weight: 500;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .captcha-container input {
            width: 100px;
            padding: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        /* Button styling */
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #6a8bb0, #c9b5c3);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(to right, #6a8bb0, #c9b5c3);
        }

        /* Divider text */
        .or-text {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            margin: 25px 0;
            position: relative;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .or-text::before,
        .or-text::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: rgba(255, 255, 255, 0.5);
        }

        .or-text::before {
            left: 0;
        }

        .or-text::after {
            right: 0;
        }

        /* Registration links */
        .form-container p {
            text-align: center;
            margin-top: 15px;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .form-container a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            transition: color 0.3s ease;
        }

        .form-container a:hover {
            color: #3498db;
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .form-container {
                padding: 30px 20px;
                width: 95%;
            }
            
            h2 {
                font-size: 1.8rem;
                margin-bottom: 25px;
            }
            
            .checkbox-forgot-wrapper {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .or-text {
                margin: 20px 0;
            }
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <div class="alert alert-danger" id="alert">
            <ul style="list-style: none; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success" id="alert">
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
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
                <i class="eye-icon fas fa-eye-slash"></i>
            </div>
        
            <div class="form-group checkbox-forgot-wrapper">
                <label class="remember-me">
                    <input type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
            </div>

            <div class="form-group">
                <div class="captcha-container">
                    <label>{{ $math_question }} = ?</label>
                    <input type="number" name="captcha_answer" value="{{ old('captcha_answer') }}" required placeholder="Answer">
                </div>
                @error('captcha_answer')
                    <span style="color: #ff6b6b; display: block; margin-top: 8px; font-size: 0.9rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn">Log In</button>

            <p class="or-text">or</p>

            <p>
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            </p>
            <p>Are you a POSO Officer? <a href="{{ route('poso.register') }}">Register</a></p>
        </form>
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

        // Password toggle functionality
        document.querySelector('.eye-icon').addEventListener('click', function() {
            const input = document.getElementById('password');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    </script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>