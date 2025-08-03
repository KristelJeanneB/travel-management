<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Traffic Monitor</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">

    <!-- JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <div class="header">
        <nav class="nav">
            <!-- Home button (left-aligned) -->
            <a href="{{ route('home') }}" class="home" id="home-button" title="Go to Home">
                <i class="fas fa-home"></i>
            </a>

            <!-- Dropdown menu (right-aligned) -->
            <div class="dropdown">
                <button class="dropbtn" id="dropdown-btn">☰</button>
                <div class="dropdown-content" id="dropdown-menu" style="display: none;">
                    <a href="{{ route('settings') }}">Settings ⚙️</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Log Out
                    </a>
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

        
        <div class="report-incident" style="margin-top: 30px;">
            <h3>Report Incident</h3>
            <form id="incident-form">
                <div class="form-group">
                    <label for="incident-type"><i class="fas fa-exclamation-triangle"></i> Incident Type:</label>
                    <select id="incident-type" required>
                        <option value="" disabled selected>Select an incident</option>
                        <option value="accident">Accident</option>
                        <option value="traffic_jam">Traffic Jam</option>
                        <option value="road_closure">Road Closure</option>
                        <option value="hazard">Hazard on Road</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="incident-description"><i class="fas fa-comment"></i> Description:</label>
                    <textarea id="incident-description" rows="3" placeholder="Optional details..."></textarea>
                </div>
                <button type="submit">Report Incident</button>
            </form>
        </div>
    </div>

    <div id="map"></div>

    <div id="route-summary" class="route-summary hidden"></div>

    <script>
       
        const dropdownBtn = document.getElementById('dropdown-btn');
        const dropdownMenu = document.getElementById('dropdown-menu');

        dropdownBtn.addEventListener('click', () => {
            dropdownMenu.style.display = (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', (event) => {
            if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        
        let map = L.map('map').setView([14.5995, 120.9842], 13);
        let userCoords = null;
        let routeControl = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        navigator.geolocation.getCurrentPosition(
            position => {
                userCoords = [position.coords.latitude, position.coords.longitude];
                map.setView(userCoords, 15);
                L.marker(userCoords).addTo(map).bindPopup("You are here").openPopup();
            },
            () => {
                alert("Unable to access your location. Please type it manually.");
            }
        );

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
                const startCoords = startInput ? await geocode(startInput) : userCoords;
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

        
        document.getElementById('incident-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const type = document.getElementById('incident-type').value;
            const desc = document.getElementById('incident-description').value.trim();

            if (!type) {
                alert('Please select an incident type.');
                return;
            }

            alert(`Incident reported:\nType: ${type.replace(/_/g, ' ')}\n${desc ? 'Description: ' + desc : ''}`);

            this.reset();
        });
    </script>

</body>
</html>
