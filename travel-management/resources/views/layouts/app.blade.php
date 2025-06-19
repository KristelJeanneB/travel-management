<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    {{-- Base Styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Page-specific styles (like settings.css or home.css) --}}
    @stack('styles')

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Leaflet (if used on this page) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
</head>
<body>
    @yield('content')

    {{-- Base Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Leaflet (optional, will work if map is used) --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    {{-- Page-specific scripts --}}
    @yield('scripts')
</body>
</html>
