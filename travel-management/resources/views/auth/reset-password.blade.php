<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>

<h2>Reset Password</h2>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <button type="submit">Reset Password</button>
</form>

</body>
</html>