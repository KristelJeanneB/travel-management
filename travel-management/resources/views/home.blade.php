<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Traffic Monitor</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">

    <!-- JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="header">
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search location...">
        <button id="search-button"><i class="fas fa-search"></i></button>
    </div>

    <nav class="nav">
        <a href="{{ route('home') }}" class="home">Home</a>
        <div class="dropdown">
            <button class="dropbtn">☰</button>
            <div class="dropdown-content">
                <a href="{{ route('settings') }}">Settings ⚙️</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
            </div>
        </div>
    </nav>
</div>

<div class="route-guidance">
    <h3>Route Planner</h3>
    <form id="route-form">
        <div class="form-group">
            <label for="start-from"><i class="fas fa-map-marker-alt"></i> Start from:</label>
            <input type="text" id="start-from" placeholder="Leave empty to use current location">
        </div>
        <div class="form-group">
            <label for="destination"><i class="fas fa-map-marker-alt"></i> Destination:</label>
            <input type="text" id="destination" placeholder="Enter city/place name">
        </div>
        <button type="submit">Get Directions</button>
    </form>
</div>

<div id="map"></div>

<div class="zoom-controls">
    <button id="zoom-in"><i class="fas fa-plus"></i></button>
    <button id="zoom-out"><i class="fas fa-minus"></i></button>
</div>


<div id="route-summary" class="route-summary hidden"></div>


<script>
    let map = L.map('map').setView([14.5995, 120.9842], 13); 
    let userCoords = null;
    let routeControl = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    
    navigator.geolocation.getCurrentPosition(position => {
        userCoords = [position.coords.latitude, position.coords.longitude];
        map.setView(userCoords, 15);
        L.marker(userCoords).addTo(map).bindPopup("You are here").openPopup();
    }, () => {
        alert("Unable to access your location. Please type it manually.");
    });

    
    document.getElementById('zoom-in').onclick = () => map.zoomIn();
    document.getElementById('zoom-out').onclick = () => map.zoomOut();

    
    async function geocode(place) {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
        const data = await res.json();
        if (!data || data.length === 0) throw new Error('Location not found');
        return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }

   
    document.getElementById('route-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const startInput = document.getElementById('start-from').value.trim();
        const destInput = document.getElementById('destination').value.trim();

        if (!destInput) {
            alert("Please enter a destination.");
            return;
        }

        try {
            const startCoords = startInput
                ? await geocode(startInput)
                : userCoords;

            const endCoords = await geocode(destInput);

            if (!startCoords) {
                alert("Unable to determine starting point.");
                return;
            }

            
            if (routeControl) map.removeControl(routeControl);

            
            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(startCoords),
                    L.latLng(endCoords)
                ],
                router: L.Routing.osrmv1(),
                lineOptions: {
                    styles: [{ color: 'blue', weight: 5 }]
                },
                show: false,
                addWaypoints: false,
                draggableWaypoints: false
            }).addTo(map);

            routeControl.on('routesfound', function(e) {
                const route = e.routes[0].summary;
                const distance = (route.totalDistance / 1000).toFixed(2);
                const time = Math.round(route.totalTime / 60);
                const summary = `Distance: ${distance} km &bull; Estimated time: ${time} mins`;

                const summaryBox = document.getElementById('route-summary');
                summaryBox.innerHTML = summary;
                summaryBox.classList.remove('hidden');
            });

        } catch (err) {
            alert(err.message || "Error fetching route.");
        }
    });
</script>

</body>
</html>
