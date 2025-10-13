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

        /* Modal styles for traffic alert */
        #traffic-alert-modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            font-family: 'Quicksand', sans-serif;
        }
        #traffic-alert-modal .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px 30px;
            border-radius: 12px;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            text-align: center;
        }
        #traffic-alert-modal .modal-content h2 {
            color: #d9534f;
            margin-bottom: 15px;
        }
        #traffic-alert-modal .modal-content button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
        }
        #traffic-alert-modal .modal-content button:hover {
            background-color: #c9302c;
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
                <!--
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                </a>-->
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

        <!-- IT BUSINESS SOLUTION 
        <div class="premium-section" style="margin-top: 20px; text-align: center;">
            <h3 style="margin-bottom: 10px;">Want More Features?</h3>
            <a href="{{ route('premium') }}" class="btn" style="padding: 10px 20px; background-color: lightgray; color: #000; border-radius: 5px; text-decoration: none; font-weight: bold;">
                Go Premium
            </a>
        </div>-->
    </form>

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
            <option value="A">Route A (Municipal Hall)</option>
            <option value="B">Route B (Labrador)</option>
            <option value="C">Route C (Dagupan)</option>
            <option value="D">Route D (Baywalk)</option>
        </select>
        <p id="traffic-status" class="traffic-status" style="margin-top:10px; font-weight:600; color:#5D7EA3;">Status: Waiting for traffic data…</p>
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

<!-- Traffic alert modal -->
<div id="traffic-alert-modal">
    <div class="modal-content">
        <h2>Traffic Alert</h2>
        <p>There is traffic here.</p>
        <button id="close-traffic-alert">Close</button>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<!-- Firebase SDKs -->
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error("❌ Leaflet failed to load!");
        alert("Map library failed to load.");
        return;
    }

    // Firebase config - Replace these with your actual Firebase config values
    const firebaseConfig = {
      apiKey: "AIzaSyC2A2rUd1SjeEmm7qyMHFz8y1afLmQpJ_0",
      authDomain: "management-6d07b.firebaseapp.com",
      databaseURL: "https://management-6d07b-default-rtdb.firebaseio.com",
      projectId: "management-6d07b",
      storageBucket: "management-6d07b.firebasestorage.app",
      messagingSenderId: "311996981023",
      appId: "1:311996981023:web:2f177c365a36197bbfda2a",
      measurementId: "G-P9EKDRXPP6"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

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

    function drawRoute(start, end) {
        if (routeControl) {
            mainMap.removeControl(routeControl);
        }
        routeControl = L.Routing.control({
            waypoints: [L.latLng(start[0], start[1]), L.latLng(end[0], end[1])],
            routeWhileDragging: false,
            showAlternatives: false,
            createMarker: function(i, wp) {
                return L.marker(wp.latLng, {
                    icon: L.icon({
                        iconUrl: i === 0 ? 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png' : 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-red.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    })
                });
            }
        }).addTo(mainMap);
    }

    // Autocomplete and default user location logic (get user location if available)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((pos) => {
            userCoords = [pos.coords.latitude, pos.coords.longitude];
            mainMap.setView(userCoords, 14);
            L.marker(userCoords).addTo(mainMap).bindPopup("You are here").openPopup();
        }, () => {
            console.warn("Geolocation permission denied, defaulting to Lingayen center");
        });
    }

    // Auto route selection and traffic status
    const routeSelector = document.getElementById('route-selector');
    const trafficStatus = document.getElementById('traffic-status');

    function updateRouteAndStatus(route) {
        const lingayen = L.latLng(LINGAYEN_COORDS[0], LINGAYEN_COORDS[1]);
        let destination;
        switch(route) {
            case 'A':
                // Municipal Hall (Updated coordinates)
                destination = L.latLng(16.0220, 120.2340);
                break;
            case 'B':
                // Labrador (approximate coords)
                destination = L.latLng(16.0335, 120.2170);
                break;
            case 'C':
                // Dagupan (approximate coords)
                destination = L.latLng(16.0455, 120.3300);
                break;
            case 'D':
                // Baywalk (approximate coords)
                destination = L.latLng(16.0225, 120.2340);
                break;
            default:
                console.warn("Unknown route");
                return;
        }

        if (routeControl) {
            mainMap.removeControl(routeControl);
        }
        routeControl = L.Routing.control({
            waypoints: [lingayen, destination],
            routeWhileDragging: false,
            showAlternatives: false,
            addWaypoints: false,
            draggableWaypoints: false,
            createMarker: function(i, wp) {
                return L.marker(wp.latLng, {
                    icon: L.icon({
                        iconUrl: i === 0 ? 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png' : 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-red.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    })
                });
            }
        }).addTo(mainMap);

        // Simulate traffic data fetch and update status
        getTrafficStatus(route);
    }

    // Simulated traffic status check (you can replace with your Firebase traffic data)
    function getTrafficStatus(route) {
        // Dummy simulation: Random traffic condition
        const statusTexts = {
            'A': 'Light Traffic',
            'B': 'Heavy Traffic',
            'C': 'Moderate Traffic',
            'D': 'No Traffic',
        };

        // Example: Fetch real-time traffic from Firebase
        const trafficRef = db.ref('traffic_status/' + route);
        trafficRef.on('value', (snapshot) => {
            let status = snapshot.val();
            if (!status) {
                status = statusTexts[route] || "Unknown";
            }
            trafficStatus.textContent = `Status: ${status}`;

            // Show modal if heavy traffic
            if (status.toLowerCase().includes("heavy")) {
                showTrafficAlertModal();
            }
        });
    }

    function showTrafficAlertModal() {
        const modal = document.getElementById('traffic-alert-modal');
        modal.style.display = 'block';
    }

    document.getElementById('close-traffic-alert').addEventListener('click', () => {
        const modal = document.getElementById('traffic-alert-modal');
        modal.style.display = 'none';
    });

    // Set default route selection and enable dropdown
    routeSelector.disabled = false;
    routeSelector.value = 'A';
    updateRouteAndStatus('A');

    routeSelector.addEventListener('change', () => {
        updateRouteAndStatus(routeSelector.value);
    });

    // Incident report modal logic
    const incidentModal = document.getElementById('incident-modal');
    const openIncidentBtn = document.getElementById('open-incident-modal');
    const closeIncidentBtn = document.getElementById('close-incident-modal');

    openIncidentBtn.addEventListener('click', () => {
        incidentModal.style.display = 'block';
        initIncidentMap();
    });

    closeIncidentBtn.addEventListener('click', () => {
        incidentModal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target === incidentModal) {
            incidentModal.style.display = 'none';
        }
    };

    // Incident report map & geolocation
    let incidentMap, incidentMarker;

    function initIncidentMap() {
        if (incidentMap) {
            incidentMap.invalidateSize();
            return;
        }
        incidentMap = L.map('incident-map').setView(LINGAYEN_COORDS, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(incidentMap);

        incidentMarker = L.marker(LINGAYEN_COORDS, {draggable:true}).addTo(incidentMap);

        document.getElementById('form-lat').value = LINGAYEN_COORDS[0];
        document.getElementById('form-lng').value = LINGAYEN_COORDS[1];

        incidentMarker.on('dragend', function(e) {
            const latlng = e.target.getLatLng();
            document.getElementById('form-lat').value = latlng.lat;
            document.getElementById('form-lng').value = latlng.lng;
        });

        // Try getting current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latlng = [position.coords.latitude, position.coords.longitude];
                incidentMap.setView(latlng, 14);
                incidentMarker.setLatLng(latlng);
                document.getElementById('form-lat').value = latlng[0];
                document.getElementById('form-lng').value = latlng[1];
                document.getElementById('location-status').textContent = "Location pinned.";
            }, function() {
                document.getElementById('location-status').textContent = "Location access denied.";
            });
        } else {
            document.getElementById('location-status').textContent = "Geolocation not supported.";
        }
    }

    // Submit incident report to Firebase
    const incidentForm = document.getElementById('incident-form');
    incidentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const type = document.getElementById('incident-type').value;
        const description = document.getElementById('incident-description').value.trim();
        const lat = parseFloat(document.getElementById('form-lat').value);
        const lng = parseFloat(document.getElementById('form-lng').value);

        if (!type) {
            alert("Please select an incident type.");
            return;
        }

        const newIncidentRef = db.ref('incidents').push();
        newIncidentRef.set({
            type,
            description,
            lat,
            lng,
            timestamp: Date.now()
        }).then(() => {
            alert("Incident report submitted successfully.");
            incidentModal.style.display = 'none';
            incidentForm.reset();
        }).catch((err) => {
            alert("Failed to submit report. Try again.");
            console.error(err);
        });
    });

});
</script>

</body>
</html>
