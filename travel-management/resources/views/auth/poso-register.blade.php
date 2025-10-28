<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register Poso</title>

  <!-- Font Awesome -->
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
      /* 🔁 REPLACE THIS WITH YOUR IMAGE PATH: e.g., 'images/background.png' */
      background-size: cover;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }
    
    /* Two-column layout with frosted glass effect */
    .container {
      display: flex;
      width: 90%;
      max-width: 1000px;
      height: 85vh;
      max-height: 90vh;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
    
    /* Left side - Branding with frosted glass */
    .branding {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-right: 1px solid rgba(255, 255, 255, 0.3);
      text-align: center;
    }
    
    .logo-container {
      width: 180px;
      height: 180px;
      margin-bottom: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .logo {
      width: 140px;
      height: 140px;
      background: url('/images/Logo.png') no-repeat center;
      /* 🔁 REPLACE WITH YOUR LOGO: e.g., 'images/Logo.png' */
      background-size: contain;
    }
    
    .brand-name {
      font-size: 3.2rem;
      font-weight: bold;
      margin-bottom: 20px;
      /* Ombre effect for RouteLink */
      background: linear-gradient(to right, #6a8bb0, #c9b5c3);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      background-size: 300% 300%;
      animation: gradientAnimation 8s ease infinite;
    }
    
    @keyframes gradientAnimation {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    
    .brand-slogan {
      color: white;
      font-size: 1.2rem;
      line-height: 1.6;
      max-width: 85%;
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    
    /* Right side - Form with frosted glass */
    .form-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      overflow: hidden;
    }
    
    .form-header {
      padding: 30px 40px 20px;
      text-align: center;
    }
    
    .form-header h2 {
      color: white;
      font-size: 2.2rem;
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    
    .form-content {
      flex: 1;
      padding: 0 40px 20px;
      overflow-y: auto;
      padding-bottom: 30px;
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
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
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
    
    .password-hint {
      display: block;
      margin-top: 8px;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.9);
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    .captcha-container {
      display: flex;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
    }
    
    .captcha-container label {
      font-weight: 500;
      color: white;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
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
    
    .form-footer {
      padding: 0 40px 30px;
    }
    
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
    
    .login-link {
      text-align: center;
      margin-top: 25px;
      color: rgba(255, 255, 255, 0.9);
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    .login-link a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    
    .login-link a:hover {
      text-decoration: underline;
    }
    
    /* Scrollbar styling for form content */
    .form-content::-webkit-scrollbar {
      width: 8px;
    }
    
    .form-content::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
    }
    
    .form-content::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 4px;
    }
    
    .form-content::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.5);
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        height: 90vh;
        width: 95%;
      }
      
      .branding {
        padding: 30px 20px;
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      }
      
      .logo-container {
        width: 150px;
        height: 150px;
      }
      
      .logo {
        width: 120px;
        height: 120px;
      }
      
      .brand-name {
        font-size: 2.5rem;
      }
      
      .form-header, .form-content, .form-footer {
        padding-left: 25px;
        padding-right: 25px;
      }
      
      .form-header {
        padding-top: 25px;
        padding-bottom: 15px;
      }
      
      .form-footer {
        padding-bottom: 25px;
      }
      
      .form-header h2 {
        font-size: 1.8rem;
      }
    }
    
    @media (max-height: 700px) {
      .container {
        height: 95vh;
      }
      
      .branding, .form-wrapper {
        padding-top: 20px;
        padding-bottom: 20px;
      }
      
      .form-header {
        padding: 20px 30px 15px;
      }
      
      .form-content {
        padding: 0 30px 15px;
      }
      
      .form-footer {
        padding: 0 30px 20px;
      }
    }
    
    @media (max-width: 480px) {
      .logo-container {
        width: 120px;
        height: 120px;
      }
      
      .logo {
        width: 100px;
        height: 100px;
      }
      
      .brand-name {
        font-size: 2rem;
      }
      
      .brand-slogan {
        font-size: 1rem;
      }
      
      .form-header h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>
  <!-- ✅ Correctly use .background-image -->
  <div class="background-image"></div>

  <!-- ✅ Use your original .container layout -->
  <div class="container">
    <!-- Left Side -->
    <div class="branding">
      <div class="logo-container">
        <div class="logo"></div>
      </div>
      <h1 class="brand-name">Poso Personnel</h1>
      <p class="brand-slogan">Official Use Only • Verified Credentials</p>
    </div>

    <!-- Right Side -->
    <div class="form-wrapper">
      <div class="form-header">
        <h2>Register</h2>
      </div>

      <div class="form-content">
        <form action="{{ route('poso.register') }}" method="POST">
        @csrf
        <!-- Error placeholder -->
        <!-- <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 1rem;">Invalid captcha</div> -->

        <form id="registerForm" action="#" method="POST">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your full name">
          </div>

          <div class="form-group">
            <label for="email">Official Email (@poso.gov.ph)</label>
            <input type="email" id="email" name="email" required placeholder="Enter your official email">
          </div>

          <div class="form-group position-relative">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Create a password">
            <span class="eye-icon" onclick="togglePassword('password', this)">
              <i class="far fa-eye"></i>
            </span>
            <small class="password-hint">Must be at least 8 characters with 1 special character.</small>
          </div>

          <div class="form-group position-relative">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
            <span class="eye-icon" onclick="togglePassword('password_confirmation', this)">
              <i class="far fa-eye"></i>
            </span>
          </div>

      <div class="form-footer">
        <button type="submit" class="btn" onclick="document.getElementById('registerForm').submit()">
          Register as Poso Personnel
        </button>
        <div class="login-link">
          Already have an account? <a href="{{ route('login') }}">Log In</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePassword(fieldId, icon) {
      const field = document.getElementById(fieldId);
      const eyeIcon = icon.querySelector('i');
      if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        field.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
    document.getElementById('registerForm').addEventListener('submit', function(e) {
  e.preventDefault(); 
  alert('Registration successful! Redirecting to login...');
  window.location.href = "{{ route('login') }}"; 
    });
  </script>
</body>
</html>