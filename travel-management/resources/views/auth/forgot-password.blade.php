<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body>

<div class="background-image"></div>


<div class="form-container">
    <h2>Forgot Password</h2>

    @if(session('status'))
        <div class="alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="btn">Send Reset Link</button>
    </form>

    
    <div class="back-to-login">
        <a href="{{ route('login') }}">← Back to Login</a>
    </div>
</div>

</body>
</html>