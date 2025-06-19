<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"> 
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> 
</head>
<body>

<div class="header">
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search">
        <button id="search-button"><i class="fas fa-search"></i></button>
    </div>
    <nav>
        <a href="{{ route('home') }}" class="home">Home</a>
        <a href="{{ route('settings') }}" class="settings">⚙️</a>
        <a href="{{ route('login') }}">Log In</a>
    </nav>
</div>

<div class="route-guidance">
    <h3>Home</h3>
    <form id="route-form">
        <div class="form-group">
            <label for="start-from"><i class="fas fa-map-marker-alt"></i> Start from:</label>
            <input type="text" id="start-from" placeholder="Enter starting location">
        </div>
        <div class="form-group">
            <label for="destination"><i class="fas fa-map-marker-alt"></i> Where to:</label>
            <input type="text" id="destination" placeholder="Enter destination">
        </div>
        <button type="submit">Get Directions</button>
    </form>
</div>

<div id="map" style="height: 100vh;"></div>

<div class="zoom-controls">
    <button id="zoom-in"><i class="fas fa-plus"></i></button>
    <button id="zoom-out"><i class="fas fa-minus"></i></button>
</div>

<script src="{{ asset('js/home.js') }}"></script>
</body>
</html>