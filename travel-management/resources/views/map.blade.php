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

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: #5D7EA3;
            color: #fff;
            border: none;
            border-radius: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 12px;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .modal-close {
            float: right;
            font-size: 28px;
            cursor: pointer;
            color: #888;
        }

        .modal-close:hover {
            color: #000;
        }

        h2 {
            margin-top: 0;
            color: #5D7EA3;
        }

        .route-status {
            margin: 12px 0;
            font-size: 18px;
        }

        .traffic-yes {
            color: red;
            font-weight: 700;
        }

        .traffic-no {
            color: green;
            font-weight: 700;
        }

        #loading {
            color: #555;
            font-style: italic;
        }

        #legend {
            position: fixed;
            bottom: 150px;
            left: 20px;
            width: 320px;
            padding: 20px 25px;
            background-color: #f4edf2;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(134, 168, 207, 0.8);
            z-index: 1050;
            max-height: calc(100vh - 90px);
            overflow-y: auto;
        }
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
                <a href="{{ route('settings', ['previous' => 'map']) }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
            </div>
        </div>
    </nav>
</div>

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
    </form>

    <button id="open-traffic-modal" class="btn" style="margin-top: 15px;">
        <i class="fas fa-car-side"></i> Show Traffic Status
    </button>
</div>

<div id="traffic-modal" class="modal">
    <div class="modal-content">
        <span id="close-traffic-modal" class="modal-close">&times;</span>
        <h2>Traffic Status</h2>
        <div id="loading">Loading traffic data...</div>
        <div id="traffic-results" style="display:none;">
            <div class="route-status" id="routeA">Route A: <span></span></div>
            <div class="route-status" id="routeB">Route B: <span></span></div>
            <div class="route-status" id="routeC">Route C: <span></span></div>
            <div class="route-status" id="routeD">Route D: <span></span></div>
        </div>
    </div>
</div>

<div class="map-container">
    <div id="map"></div>
    <div id="route-summary" class="route-summary hidden"></div>

    <div id="legend">
    <strong>Alternate Routes</strong><br>
    <span style="color:red;">●</span> Route A<br>
    <span style="color:blue;">●</span> Route B<br>
    <span style="color:green;">●</span> Route C<br>
    <span style="color:purple;">●</span> Route D<br>
    <small>All share start & destination</small>
</div>
</div>

<div id="incident-modal" class="modal">
    <div class="modal-content" style="max-width: 500px; margin: 40px auto; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <span id="close-incident-modal" class="modal-close" style="float:right; font-size:28px; cursor:pointer;">&times;</span>
        <h2>Report an Incident</h2>

        <form id="incident-form">
            @csrf
            <input type="hidden" name="lat" id="form-lat">
            <input type="hidden" name="lng" id="form-lng">

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
                <label for="incident-description">Add Details (Optional):</label>
                <textarea id="incident-description" name="description" rows="3" placeholder="Please indicate details..."></textarea>
            </div>

            <div class="form-group">
                <label>Your Location:</label>
                <p style="font-size:14px; color:#555;">Allow access to pin your current location.</p>
                <div id="incident-map" style="height: 200px; border: 1px solid #ddd; border-radius: 8px;"></div>
                <p id="location-status" style="color: #d97706; font-size: 14px; text-align: center; margin-top: 8px;">Getting your location...</p>
            </div>

            <button type="submit" class="btn" style="width:100%; padding:12px;">Submit Report</button>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>

<script>
// Firebase Config
const firebaseConfig = {
    apiKey: "AIzaSyC2A2rUd1SjeEmm7qyMHFz8y1afLmQpJ_0",
    authDomain: "management-6d07b.firebaseapp.com",
    databaseURL: "https://management-6d07b-default-rtdb.firebaseio.com/",
    projectId: "management-6d07b",
    storageBucket: "management-6d07b.appspot.com",
    messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
    appId: "YOUR_APP_ID"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.database();

document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error("❌ Leaflet failed to load!");
        alert("Map library failed to load.");
        return;
    }

    const LINGAYEN_COORDS = [16.0212, 120.2315];
    let userCoords = LINGAYEN_COORDS;

    // --- Dropdown Menu ---
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

    // --- Initialize Map ---
    const mainMap = L.map('map').setView(LINGAYEN_COORDS, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mainMap);
    //L.marker(LINGAYEN_COORDS).addTo(mainMap).bindPopup("Lingayen, Pangasinan").openPopup();

    // --- Route Form & Alternate Routes ---
    const routeForm = document.getElementById('route-form');
    const summaryBox = document.getElementById('route-summary');
    let currentRouteLayers = [];

    function clearRoutes() {
        currentRouteLayers.forEach(layer => mainMap.removeLayer(layer));
        currentRouteLayers = [];
        summaryBox.classList.add('hidden');
    }

    async function geocode(place) {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
        const data = await res.json();
        if (!data.length) throw new Error('Location not found');
        return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }

async function drawAlternateRoutes(start, end) {
    clearRoutes(); 

    const names = ['Route A', 'Route B', 'Route C', 'Route D'];
    const colors = ['red', 'blue', 'green', 'purple'];
    
    const url = `https://router.project-osrm.org/route/v1/car/${start[1]},${start[0]};${end[1]},${end[0]}?alternatives=true&geometries=geojson&overview=full`;

    try {
        const res = await fetch(url);
        if (!res.ok) throw new Error('Network error');
        const data = await res.json();

        if (data.routes && data.routes.length > 1) {
            // Use real OSRM alternatives (up to 4)
            const routesToShow = data.routes.slice(0, 4);

            routesToShow.forEach((route, i) => {
                const coords = route.geometry.coordinates.map(coord => [
                    coord[1], // lat
                    coord[0]  // lng
                ]);

                const polyline = L.polyline(coords, {
                    color: colors[i],
                    weight: 5,
                    opacity: 0.8
                }).bindPopup(`
                    <b>${names[i]}</b><br>
                    Distance: ${(route.distance / 1000).toFixed(2)} km<br>
                    Time: ${Math.round(route.duration / 60)} mins
                `).addTo(mainMap);

                currentRouteLayers.push(polyline);

                // Show summary for main route only
                if (i === 0) {
                    document.getElementById('route-summary').innerHTML = `
                        Best Option: ${(route.distance / 1000).toFixed(2)} km • 
                        ${Math.round(route.duration / 60)} mins
                    `;
                    document.getElementById('route-summary').classList.remove('hidden');
                }
            });

            // Add destination marker
            L.marker([end[0], end[1]]).addTo(mainMap).bindPopup("Destination").openPopup();
            return;
        }
    } catch (err) {
        console.warn("OSRM alternatives failed, using manual detours", err);
    }

    // 🔁 Fallback: Create 4 artificial detours
    for (let i = 0; i < 4; i++) {
        try {
            let midLat, midLng;

            // Create 4 distinct via points around center
            const offsets = [
                { lat: +0.007, lng: -0.003 }, // NE
                { lat: -0.007, lng: -0.003 }, // SE
                { lat: -0.007, lng: +0.003 }, // SW
                { lat: +0.007, lng: +0.003 }  // NW
            ];

            const offset = offsets[i] || offsets[0];
            midLat = (start[0] + end[0]) / 2 + offset.lat;
            midLng = (start[1] + end[1]) / 2 + offset.lng;

            const isDirect = i === 0; // First is direct
            const viaPointUrl = isDirect
                ? `${start[1]},${start[0]};${end[1]},${end[0]}`
                : `${start[1]},${start[0]};${midLng},${midLat};${end[1]},${end[0]}`;

            const fallbackUrl = `https://router.project-osrm.org/route/v1/car/${viaPointUrl}?geometries=geojson`;

            const res = await fetch(fallbackUrl);
            const data = await res.json();

            if (!data.routes || data.routes.length === 0) continue;

            const route = data.routes[0];
            const coords = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);

            const polyline = L.polyline(coords, {
                color: colors[i],
                weight: 5,
                opacity: 0.8
            }).bindPopup(`<b>${names[i]}</b><br>Distance: ${(route.distance / 1000).toFixed(2)} km`)
              .addTo(mainMap);

            currentRouteLayers.push(polyline);

            // Summary for first route
            if (i === 0) {
                document.getElementById('route-summary').innerHTML = `
                    🏆 <strong>Best Option: ${names[i]}</strong><br>
                            ${(route.distance / 1000).toFixed(2)} km • 
                            ${Math.round(route.duration / 60)} mins
                `;
                document.getElementById('route-summary').classList.remove('hidden');
            }
        } catch (err) {
            console.warn(`Fallback route ${names[i]} failed`, err);
        }
    }

    // Always add destination pin
    L.marker([end[0], end[1]]).addTo(mainMap).bindPopup("Destination").openPopup();
}

    // Fallback: Create manual detours via artificial waypoints
    async function createDetourRoutes(start, end) {
        const names = ['Route A', 'Route B', 'Route C'];
        const colors = ['red', 'blue', 'green'];

        for (let i = 0; i < 3; i++) {
            let url;

            if (i === 0) {
                // Direct route
                url = `https://router.project-osrm.org/route/v1/car/${start[1]},${start[0]};${end[1]},${end[0]}?geometries=geojson`;
            } else {
                // Add detour point (north/south variation)
                const offset = (i - 1) * 0.007;
                const midLat = (start[0] + end[0]) / 2 + offset;
                const midLng = (start[1] + end[1]) / 2 - offset;

                url = `https://router.project-osrm.org/route/v1/car/${start[1]},${start[0]};${midLng},${midLat};${end[1]},${end[0]}?geometries=geojson`;
            }

            try {
                const res = await fetch(url);
                const data = await res.json();
                if (!data.routes || data.routes.length === 0) continue;

                const route = data.routes[0];
                const coords = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);

                const polyline = L.polyline(coords, {
                    color: colors[i],
                    weight: 5,
                    opacity: 0.8
                }).bindPopup(`<b>${names[i]}</b><br>Distance: ${(route.distance / 1000).toFixed(2)} km`)
                  .addTo(mainMap);

                currentRouteLayers.push(polyline);
            } catch (err) {
                console.warn(`Fallback route ${i} failed`, err);
            }
        }
    }

    // Handle form submission
    routeForm?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const startInput = document.getElementById('start-from').value.trim();
        const destInput = document.getElementById('destination').value.trim();
        if (!destInput) return alert("Please enter a destination.");

        try {
            const startCoords = startInput ? await geocode(startInput) : LINGAYEN_COORDS;
            const endCoords = await geocode(destInput);

            clearRoutes();
            await drawAlternateRoutes(startCoords, endCoords);

        } catch (err) {
            alert("Error: " + (err.message || "Location not found"));
        }
    });

    // --- Incident Reporting Modal ---
    const incidentModal = document.getElementById("incident-modal");
    const openIncidentBtn = document.getElementById("open-incident-modal");
    const closeIncidentBtn = document.getElementById("close-incident-modal");
    const incidentForm = document.getElementById('incident-form');
    let incidentMap = null;

    function initIncidentMap(lat, lng) {
        if (incidentMap) {
            incidentMap.off();
            incidentMap.remove();
        }
        incidentMap = L.map('incident-map').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(incidentMap);
        L.marker([lat, lng]).addTo(incidentMap).bindPopup("Your reported location").openPopup();
        document.getElementById('form-lat').value = lat;
        document.getElementById('form-lng').value = lng;
        document.getElementById('location-status').innerHTML = `<strong>📍 Pinned:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }

    function getUserLocationForIncident() {
        const statusEl = document.getElementById('location-status');
        statusEl.textContent = "Getting your location...";
        if (!navigator.geolocation) {
            statusEl.innerHTML = `<span style="color: red;">❌ Geolocation not supported</span>`;
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => initIncidentMap(pos.coords.latitude, pos.coords.longitude),
            (error) => {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        statusEl.innerHTML = `<span style="color: red;">❌ Permission denied</span>`;
                        break;
                    case error.POSITION_UNAVAILABLE:
                        statusEl.innerHTML = `<span style="color: red;">❌ Location unavailable</span>`;
                        break;
                    case error.TIMEOUT:
                        statusEl.innerHTML = `<span style="color: red;">❌ Request timeout</span>`;
                        break;
                    default:
                        statusEl.innerHTML = `<span style="color: red;">❌ Failed to get location</span>`;
                }
            }
        );
    }

    openIncidentBtn?.addEventListener('click', () => {
        incidentModal.style.display = "block";
        setTimeout(getUserLocationForIncident, 300);
    });

    closeIncidentBtn?.addEventListener('click', () => {
        incidentModal.style.display = "none";
        if (incidentMap) incidentMap.remove();
    });

    window.onclick = (e) => {
        if (e.target === incidentModal) {
            incidentModal.style.display = "none";
            if (incidentMap) incidentMap.remove();
        }
    };

    incidentForm?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const type = document.getElementById('incident-type').value;
        const description = document.getElementById('incident-description').value.trim();
        const lat = parseFloat(document.getElementById('form-lat').value);
        const lng = parseFloat(document.getElementById('form-lng').value);

        if (!type) return alert('Please select an incident type.');
        if (isNaN(lat) || isNaN(lng)) return alert('Invalid location.');

        try {
            const res = await fetch("{{ route('incident.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ type, description, lat, lng })
            });
            const data = await res.json();
            alert(data.message || 'Report submitted!');
            incidentForm.reset();
            incidentModal.style.display = 'none';
        } catch (err) {
            alert('Failed to submit report.');
            console.error(err);
        }
    });

    // --- Traffic Modal ---
    const trafficModal = document.getElementById('traffic-modal');
    const openTrafficBtn = document.getElementById('open-traffic-modal');
    const closeTrafficBtn = document.getElementById('close-traffic-modal');
    const loadingEl = document.getElementById('loading');
    const resultsEl = document.getElementById('traffic-results');

    openTrafficBtn?.addEventListener('click', () => {
        trafficModal.style.display = 'block';
        fetchTrafficData();
    });

    closeTrafficBtn?.addEventListener('click', () => {
        trafficModal.style.display = 'none';
    });

    window.onclick = (e) => {
        if (e.target === trafficModal) trafficModal.style.display = 'none';
    };

    async function fetchTrafficData() {
        loadingEl.style.display = 'block';
        resultsEl.style.display = 'none';
        try {
            const snapshot = await db.ref('traffic_logs').limitToLast(1).once('value');
            let data = null;
            snapshot.forEach(child => { data = child.val(); });
            if (data) updateTrafficStatus(data);
            else loadingEl.textContent = 'No traffic data.';
        } catch (err) {
            loadingEl.textContent = 'Error loading data.';
            console.error(err);
        }
    }

    function updateTrafficStatus(data) {
        loadingEl.style.display = 'none';
        resultsEl.style.display = 'block';
        ['A', 'B', 'C', 'D'].forEach(r => {
            const el = document.getElementById(`route${r}`).querySelector('span');
            const hasTraffic = data?.[`sensor${r}`]?.traffic;
            el.textContent = hasTraffic === true ? 'Traffic' : hasTraffic === false ? 'No Traffic' : 'No Data';
            el.className = hasTraffic === true ? 'traffic-yes' : hasTraffic === false ? 'traffic-no' : '';
        });
    }
});
</script>

</body>
</html>