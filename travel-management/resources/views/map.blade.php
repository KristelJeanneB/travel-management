<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Traffic Monitor</title>

    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand&display=swap');
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <nav class="nav">
        <a href="{{ route('home') }}" class="back-button" title="Back to Home">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <div class="dropdown">
            <button class="dropbtn" id="dropdown-btn">☰</button>
            <div class="dropdown-content" id="dropdown-menu">
                <a href="{{ route('settings', ['previous' => 'map']) }}">
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

<!-- ROUTE PLANNER PANEL -->
<div class="route-guidance">
    <h3>Route Planner</h3>
    <form id="route-form">
        <div class="form-group">
            <label for="start-from"><i class="fas fa-map-marker-alt"></i> Start from:</label>
            <input type="text" id="start-from" placeholder="Leave empty to use Lingayen center">
        </div>
        <div class="form-group">
            <label for="destination"><i class="fas fa-map-marker-alt"></i> Destination:</label>
            <input type="text" id="destination" placeholder="Enter city/place name">
        </div>
        <button type="submit">Get Directions</button>

        <div class="report-incident-trigger">
            <button id="open-incident-modal" type="button" class="btn">Report Incident</button>
        </div>

        <!-- Premium Section -->
        <div class="premium-section" style="margin-top: 20px; text-align: center;">
            <h3 style="margin-bottom: 10px;">Want More Features?</h3>
            <a href="{{ route('premium') }}" class="btn" style="padding: 10px 20px; background-color: lightgray; color: #000; border-radius: 5px; text-decoration: none; font-weight: bold;">
                Go Premium
            </a>
        </div>
    </form>

    <!-- AUTOMATIC ROUTE BOX (moved down, styled like planner) -->
    <div class="route-options-box" style="
        margin-top: 25px;
        padding: 15px 20px;
        background-color: #f4edf2;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(134,168,207,0.6);
    ">
        <h4 style="font-weight:700; color:#5D7EA3; margin-bottom:10px;">Automatic Route Selection</h4>
        <select id="route-selector" disabled style="
            width:100%;
            padding:8px 10px;
            border:none;
            border-radius:6px;
            font-size:16px;
            box-shadow: inset 0 0 6px rgba(134,168,207,0.4);
        ">
            <option value="A">Route A</option>
            <option value="B">Route B</option>
            <option value="C">Route C</option>
        </select>
        <p id="traffic-status" class="traffic-status" style="margin-top:10px; font-weight:600; color:#5D7EA3;">Status: Waiting for Arduino data…</p>
    </div>
</div>

<!-- MAP + SUMMARY -->
<div class="map-container">
    <div id="map"></div>
    <div id="route-summary" class="route-summary hidden"></div>
</div>

<!-- INCIDENT MODAL -->
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

<!-- SCRIPTS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const LINGAYEN_COORDS = [16.0212, 120.2315];
    let userCoords = LINGAYEN_COORDS;
    let routeControl = null;

    // Dropdown menu
    const dropdownBtn = document.getElementById('dropdown-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');
    dropdownBtn?.addEventListener('click', () => {
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Map init
    const map = L.map('map').setView(LINGAYEN_COORDS, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker(LINGAYEN_COORDS).addTo(map).bindPopup("Lingayen, Pangasinan").openPopup();

    async function geocode(place) {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
        const data = await res.json();
        if (!data || data.length === 0) throw new Error('Location not found');
        return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }

    // Route form
    const routeForm = document.getElementById('route-form');
    routeForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const startInput = document.getElementById('start-from').value.trim();
        const destInput = document.getElementById('destination').value.trim();
        if (!destInput) return alert("Please enter a destination.");

        try {
            const startCoords = startInput ? await geocode(startInput) : userCoords;
            const endCoords = await geocode(destInput);
            drawRoute(startCoords, endCoords);
        } catch (err) {
            alert(err.message || "Error fetching route.");
        }
    });

    function drawRoute(startCoords, endCoords, color='blue') {
        if (routeControl) map.removeControl(routeControl);
        routeControl = L.Routing.control({
            waypoints: [L.latLng(startCoords), L.latLng(endCoords)],
            router: L.Routing.osrmv1(),
            lineOptions: { styles: [{ color: color, weight: 5 }] },
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
    }

    // Incident Modal
    const modal = document.getElementById("incident-modal");
    const openBtn = document.getElementById("open-incident-modal");
    const closeBtn = document.getElementById("close-incident-modal");
    const incidentForm = document.getElementById('incident-form');
    openBtn?.addEventListener('click', () => modal.style.display = "block");
    closeBtn?.addEventListener('click', () => modal.style.display = "none");
    window.onclick = e => { if (e.target === modal) modal.style.display = "none"; };

    incidentForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const type = document.getElementById('incident-type').value;
        const description = document.getElementById('incident-description').value.trim();
        if (!type) return alert('Please select an incident type.');

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

    // NEW Auto Route Switch
    const routeSelector = document.getElementById('route-selector');
    const trafficStatus = document.getElementById('traffic-status');
    const routeCoords = {
        A: [[16.0212, 120.2315],[16.0250, 120.2350]],
        B: [[16.0212, 120.2315],[16.0300, 120.2400]],
        C: [[16.0212, 120.2315],[16.0350, 120.2450]]
    };

    function autoDrawRoute(route) {
        routeSelector.value = route;
        trafficStatus.textContent = `Status from Arduino: Taking safest Route ${route}`;
        const coords = routeCoords[route];
        drawRoute(coords[0], coords[1], 'green');
    }

    setInterval(async () => {
        try {
            const res = await fetch('/api/traffic-status');
            if (res.ok) {
                const data = await res.json();
                if (data.status === 'clear') {
                    autoDrawRoute(data.route);
                } else {
                    const fallback = data.route === 'A' ? 'B' : 'C';
                    autoDrawRoute(fallback);
                }
            }
        } catch (err) {
            
        }
    }, 5000);
});
</script>

</body>
</html>
