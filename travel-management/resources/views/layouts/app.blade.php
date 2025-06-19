<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body class="antialiased">

    <nav class="navbar">
        <div class="container">
            <a href="{{ url('/') }}" class="logo">MyApp</a>

            <div class="nav-links">
                @auth
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('settings') }}">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span>© {{ date('Y') }} My Laravel App</span>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

</body>
</html>