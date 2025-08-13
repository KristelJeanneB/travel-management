<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Traffic Monitor</title>

    <!-- Updated map.css -->
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Friendlier font for modal and general -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand&display=swap');
    </style>
</head>
<body>

<div class="header">
    <nav class="nav">
        <a href="{{ route('home') }}" class="back-button" title="Back to Home">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <div class="dropdown">
            <button class="dropbtn" id="dropdown-btn">☰</button>
            <div class="dropdown-content" id="dropdown-menu">
                <a href="{{ route('settings') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Log Out
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

        <div class="report-incident-trigger">
            <button id="open-incident-modal" type="button" class="btn">Report Incident</button>
        </div>
    </form>
</div>

<!-- Wrap map & summary for layout -->
<div class="map-container">
    <div id="map"></div>
    <div id="route-summary" class="route-summary hidden"></div>
</div>

<!-- Incident Modal -->
<div id="incident-modal" class="modal" style="display:none;">
    <div class="modal-content" style="font-family: 'Quicksand', sans-serif;">
        <span id="close-incident-modal" class="modal-close">&times;</span>
        <h2>Report an Incident</h2>
        <form id="incident-form">
            @csrf
            <div class="form-group">
                <label for="incident-type">Incident Type:</label>
                <select id="incident-type" name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="accident">Accident</option>
                    <option value="traffic_jam">Traffic Jam</option>
                    <option value="road_closure">Road Closure</option>
                    <option value="hazard">Hazard</option>
                </select>
            </div>
            <div class="form-group">
                <label for="incident-description">Add Location:</label>
                <textarea id="incident-description" name="description" rows="4" placeholder="Please Indicate location for the authorities..."></textarea>
            </div>
            <button type="submit" class="btn">Submit Report</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dropdown menu logic
    const dropdownBtn = document.getElementById('dropdown-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', () => {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (e) => {
            if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    }

    // Initialize map
    let map = L.map('map').setView([14.5995, 120.9842], 13);
    let userCoords = null;
    let routeControl = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Get user location
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

    // Geocode function using Nominatim
    async function geocode(place) {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
        const data = await res.json();
        if (!data || data.length === 0) throw new Error('Location not found');
        return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }

    // Route planner form
    const routeForm = document.getElementById('route-form');
    if (routeForm) {
        routeForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const startInput = document.getElementById('start-from').value.trim();
            const destInput = document.getElementById('destination').value.trim();
            if (!destInput) return alert("Please enter a destination.");

            try {
                const startCoords = startInput ? await geocode(startInput) : userCoords;
                const endCoords = await geocode(destInput);
                if (!startCoords) return alert("Unable to determine starting point.");
                if (routeControl) map.removeControl(routeControl);

                routeControl = L.Routing.control({
                    waypoints: [L.latLng(startCoords), L.latLng(endCoords)],
                    router: L.Routing.osrmv1(),
                    lineOptions: { styles: [{ color: 'blue', weight: 5 }] },
                    show: false,
                    addWaypoints: false,
                    draggableWaypoints: false
                }).addTo(map);

                routeControl.on('routesfound', function(e) {
                    const r = e.routes[0].summary;
                    const summary = `Distance: ${(r.totalDistance / 1000).toFixed(2)} km • Estimated time: ${Math.round(r.totalTime / 60)} mins`;
                    const summaryBox = document.getElementById('route-summary');
                    summaryBox.innerHTML = summary;
                    summaryBox.classList.remove('hidden');
                });

            } catch (err) {
                alert(err.message || "Error fetching route.");
            }
        });
    }

    // Incident Modal logic
    const modal = document.getElementById("incident-modal");
    const openBtn = document.getElementById("open-incident-modal");
    const closeBtn = document.getElementById("close-incident-modal");
    const incidentForm = document.getElementById('incident-form');

    if (openBtn) openBtn.onclick = () => modal.style.display = "block";
    if (closeBtn) closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = e => { if (modal && e.target === modal) modal.style.display = "none"; };

    if (incidentForm) {
        incidentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const type = document.getElementById('incident-type').value;
            const description = document.getElementById('incident-description').value.trim();

            if (!type) return alert('Please select an incident type.');
            if (!userCoords) return alert('User location not available. Please allow location access.');

            try {
                const res = await fetch("{{ route('incident.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type,
                        description: description,
                        lat: userCoords[0],
                        lng: userCoords[1],
                    })
                });

                if (!res.ok) throw new Error('Network response was not ok');

                const data = await res.json();
                alert(data.message || 'Incident reported successfully.');
                incidentForm.reset();
                modal.style.display = "none";
            } catch (err) {
                console.error(err);
                alert('Failed to report the incident. Please try again.');
            }
        });
    }
});
</script>

</body>
</html>
