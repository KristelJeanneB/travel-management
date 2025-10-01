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

        .btn { padding: 10px 20px; font-size: 16px; cursor: pointer; background: #5D7EA3; color: #fff; border: none; border-radius: 5px; }
        .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fff; margin: 10% auto; padding: 20px; border-radius: 12px; max-width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .modal-close { float: right; font-size: 28px; cursor: pointer; color: #888; }
        .modal-close:hover { color: #000; }
        h2 { margin-top: 0; color: #5D7EA3; }
        .route-status { margin: 12px 0; font-size: 18px; }
        .traffic-yes { color: red; font-weight: 700; }
        .traffic-no { color: green; font-weight: 700; }
        #loading { color: #555; font-style: italic; }
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

    <!-- ✅ Added Traffic Modal Trigger -->
    <button id="open-traffic-modal" class="btn" style="margin-top: 15px;"><i class="fas fa-car-side"></i> Show Traffic Status</button>
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
</div>

<div id="incident-modal" class="modal" style="display:none; z-index: 1050;">
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
// Firebase Traffic Modal Logic
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
const database = firebase.database();

const modal = document.getElementById('traffic-modal');
const openBtn = document.getElementById('open-traffic-modal');
const closeBtn = document.getElementById('close-traffic-modal');
const loadingEl = document.getElementById('loading');
const resultsEl = document.getElementById('traffic-results');

const routes = ['A', 'B', 'C', 'D'];

function updateTrafficStatus(data) {
    loadingEl.style.display = 'none';
    resultsEl.style.display = 'block';

    routes.forEach(route => {
        const el = document.getElementById('route' + route).querySelector('span');
        const trafficBool = data?.[`sensor${route}`]?.traffic;

        if (trafficBool === true) {
            el.textContent = 'Traffic';
            el.className = 'traffic-yes';
        } else if (trafficBool === false) {
            el.textContent = 'No Traffic';
            el.className = 'traffic-no';
        } else {
            el.textContent = 'No Data';
            el.className = '';
        }
    });
}

async function fetchTrafficData() {
    loadingEl.style.display = 'block';
    resultsEl.style.display = 'none';

    try {
        const snapshot = await database.ref('traffic_logs').limitToLast(1).once('value');
        let latestEntry = null;
        snapshot.forEach(child => {
            latestEntry = child.val();
        });
        if (latestEntry) {
            updateTrafficStatus(latestEntry);
        } else {
            loadingEl.textContent = 'No traffic data available.';
        }
    } catch (error) {
        loadingEl.textContent = 'Error loading data.';
        console.error(error);
    }
}

openBtn.addEventListener('click', () => {
    modal.style.display = 'block';
    fetchTrafficData();
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error("❌ Leaflet failed to load!");
        alert("Map library failed to load.");
        return;
    }

    const LINGAYEN_COORDS = [16.0212, 120.2315];
    let userCoords = LINGAYEN_COORDS;
    let routeControl = null;

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

    const mainMap = L.map('map').setView(LINGAYEN_COORDS, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mainMap);
    L.marker(LINGAYEN_COORDS).addTo(mainMap).bindPopup("Lingayen, Pangasinan").openPopup();

    async function geocode(place) {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
        const data = await res.json();
        if (!data || data.length === 0) throw new Error('Location not found');
        return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }

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

    function drawRoute(startCoords, endCoords, color = 'blue') {
        if (routeControl) mainMap.removeControl(routeControl);

        routeControl = L.Routing.control({
            waypoints: [L.latLng(startCoords), L.latLng(endCoords)],
            router: L.Routing.osrmv1(),
            lineOptions: { styles: [{ color: color, weight: 5 }] },
            show: false,
            addWaypoints: false,
            draggableWaypoints: false
        }).addTo(mainMap);

        routeControl.on('routesfound', function(e) {
            const r = e.routes[0].summary;
            const summary = `Distance: ${(r.totalDistance / 1000).toFixed(2)} km • Estimated time: ${Math.round(r.totalTime / 60)} mins`;
            const summaryBox = document.getElementById('route-summary');
            summaryBox.innerHTML = summary;
            summaryBox.classList.remove('hidden');
        });
    }

    const modal = document.getElementById("incident-modal");
    const openBtn = document.getElementById("open-incident-modal");
    const closeBtn = document.getElementById("close-incident-modal");
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
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                initIncidentMap(lat, lng);
            },
            (error) => {
                console.error("Geolocation error:", error);
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
                        statusEl.innerHTML = `<span style="color: red;">❌ Failed to submit report/span>`;
                }
            }
        );
    }

    openBtn?.addEventListener('click', () => {
        modal.style.display = "block";
        setTimeout(() => getUserLocationForIncident(), 300);
    });

    closeBtn?.addEventListener('click', () => {
        modal.style.display = "none";
        if (incidentMap) {
            incidentMap.off();
            incidentMap.remove();
            incidentMap = null;
        }
    });

    window.onclick = e => { 
        if (e.target === modal) {
            modal.style.display = "none";
            if (incidentMap) {
                incidentMap.off();
                incidentMap.remove();
                incidentMap = null;
            }
        }
    };

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
