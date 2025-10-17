<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Monitor</title>

    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <audio id="new-report-beep" preload="auto">
  <source src="https://cdn.pixabay.com/download/audio/2022/10/31/audio_report-sound-124454.mp3?filename=report-sound-124454.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>



    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand&display=swap');

.route-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.search-row {
    width: 100%;
}

.input-with-icon {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: border-color 0.3s, box-shadow 0.3s;
}

.input-with-icon:focus-within {
    border-color: #5D7EA3;
    box-shadow: 0 2px 8px rgba(93, 126, 163, 0.2);
}

.input-with-icon i {
    color: #5D7EA3;
    font-size: 18px;
    margin-right: 12px;
    min-width: 20px;
    text-align: center;
}

.input-with-icon input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 15px;
    color: #333;
    font-family: 'Quicksand', sans-serif;
    padding: 4px 0;
}

.input-with-icon input::placeholder {
    color: #999;
    font-family: 'Quicksand', sans-serif;
}


.route-guidance > button {
    width: 100%;
    margin-top: 8px;
}


.btn-route {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: #5D7EA3 !important; 
    color: white !important;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-route:hover {
    background: #4a6482 !important;
}

.btn-report {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: #e74c3c !important;
    color: white !important;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-report:hover {
    background: #c0392b !important;
}

.btn-view-reports {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: #28a745 !important;
    color: white !important;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-view-reports:hover {
    background: #218838 !important;
}

/* Ensure icons show up */
.form-group label i,
.btn-report i,
.btn-view-reports i,
.btn-route i {
    color: white;
}

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
            top: 50px;
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

        #incident-modal .fa-check-circle {
    color: #28a745;
    animation: grow 0.5s ease-out;
}


@keyframes grow {
    0% { transform: scale(0); }
    100% { transform: scale(1); }
}
        /* User Location Marker */
.user-location-dot .dot {
    width: 12px;
    height: 12px;
    background-color: #007bff;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 5px rgba(0,0,0,0.4);
}

.user-location-dot .pulse {
    position: absolute;
    top: -6px;
    left: -6px;
    width: 24px;
    height: 24px;
    background-color: rgba(0, 123, 255, 0.3);
    border-radius: 50%;
    animation: pulse 1.5s infinite;
    z-index: -1;
}

.incident-marker .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 5px rgba(0,0,0,0.4);
}


@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 0.8;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }

    /* Mobile responsiveness */
@media (max-width: 768px) {
    .route-guidance {
        width: 100%;
        padding: 15px;
        background: #f4edf2;
        border-radius: 0;
        box-shadow: none;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        max-height: 100vh;
        overflow-y: auto;
        display: none; /* Hidden by default */
    }

    .route-guidance.active {
        display: block;
    }

    .map-container {
    width: 100%;
    height: 100vh;
    position: relative;
}
}

#map {
    width: 100%;
    height: 100vh;
    margin: 0;
    padding: 0;
}
    .header {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1050;
    }

    .nav {
        padding: 10px 15px;
    }

    .back-button {
        font-size: 16px;
    }

    .dropdown {
        position: absolute;
        right: 15px;
        top: 10px;
    }

    .dropdown-content {
        right: 0;
        left: auto;
        min-width: 150px;
    }

    .form-group label {
        font-size: 14px;
    }

    .form-group input {
        font-size: 14px;
        padding: 8px;
    }

    .btn-route,
    .btn-report,
    .btn-view-reports {
        font-size: 14px;
        padding: 10px;
    }

    .modal-content {
        max-width: 90%;
        margin: 10% auto;
        padding: 15px;
    }
    .mobile-menu-btn {
    position: fixed;
    top: 10px;
    left: 10px;
    z-index: 1051;
    background: #5D7EA3;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
}
@media (max-width: 768px) {
  #legend {
        position: fixed;
        bottom: 60px;
        left: 20px;
        width: 320px;
        padding: 20px;
        background: #f4edf2;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba  (0,0,0,0.2);
        z-index: 1060; 
    }

    #legend strong {
        font-size: 14px;
    }

    #legend span {
        font-size: 12px;
    }
}

@media (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
    }
}

@media (max-width: 768px) {
    #userIncidentModal .modal-content {
        padding: 15px;
        max-height: 90vh;
        overflow-y: auto;
    }

    #userIncidentModal table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    #userIncidentModal th,
    #userIncidentModal td {
        padding: 8px 6px;
        white-space: nowrap;
    }

    #userIncidentModal th {
        font-size: 12px;
    }


    #userIncidentModal .modal-content > :not(h2):not(p) {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}

@media (max-width: 480px) {
    #userIncidentModal th:nth-child(2),
    #userIncidentModal td:nth-child(2) {
        font-size: 12px;
    }

    #userIncidentModal th:nth-child(4),
    #userIncidentModal td:nth-child(4) {
        display: none; 
    }
}
@media (max-width: 768px) {
    #userIncidentModal .modal-content {
        padding: 15px;
        max-height: 90vh;
        overflow-y: auto;
    }
    #userIncidentModal table {
        width: 100%;
        font-size: 13px;
    }
    #userIncidentModal th,
    #userIncidentModal td {
        padding: 8px 6px;
        white-space: nowrap;
    }
    #user-incident-search {
        width: 140px;
        font-size: 13px;
        padding: 6px 10px;
    }
}
.badge { display: inline-block; }
}
.address-cell {
    font-style: ${item.address ? 'normal' : 'italic'};
    color: ${item.address ? '#333' : '#888'};
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
    <form id="route-form" class="route-form">
        <div class="search-row">
            <div class="input-with-icon">
                <i class="fas fa-location-dot"></i>
                <input 
                    type="text" 
                    id="start-from" 
                    placeholder="Start location (leave empty for current location)">
            </div>
        </div>
        <div class="search-row">
            <div class="input-with-icon">
                <i class="fas fa-flag-checkered"></i>
                <input 
                    type="text" 
                    id="destination" 
                    placeholder="Enter destination city or place"
                    required>
            </div>
        </div>
        <button type="submit" class="btn-route">
            <i class="fas fa-directions"></i> Get Directions
        </button>
    </form>

    <button id="open-incident-modal" type="button" class="btn-report">
        <i class="fas fa-exclamation-circle"></i> Report Incident
    </button>

    <button id="open-user-incident-modal" type="button" class="btn-view-reports">
        <i class="fas fa-list-alt"></i> View All Incident Reports
        <span id="new-reports-badge" class="badge" style="display:none; background:#ff4757; color:white; border-radius:10px; padding:2px 6px; font-size:12px; margin-left:6px;"></span>
    </button>

    <button id="open-traffic-modal" class="btn" style="margin-top: 15px;">
        <i class="fas fa-car-side"></i> Show Traffic Status
    </button>

    <div style="margin-top: 30px;">
        <strong>Alternate Routes</strong><br>
        <span style="color:red;">●</span> Route A<br>
        <span style="color:blue;">●</span> Route B<br>
        <span style="color:green;">●</span> Route C<br>
        <span style="color:purple;">●</span> Route D<br>
        <small>All share start & destination</small>
    </div>
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

<div id="userIncidentModal" class="modal">
    <div class="modal-content" style="max-width: 800px; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
            <h2>Community Incident Reports</h2>
            <input 
                type="text" 
                id="user-incident-search" 
                placeholder="Search: type, location, date..." 
                style="padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 14px; width: 200px;"
            />
            <span id="closeUserIncidentModal" class="modal-close">&times;</span>
        </div>
        <p style="color: #555; margin-bottom: 15px;">See real-time reports from other users.</p>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Coords</th>
                    <th>Full Address</th>
                    <th>Reported On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="userIncidentTableBody">
                <tr><td colspan="6" style="text-align:center;">Loading reports...</td></tr>
            </tbody>
        </table>
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
                <div style="margin-top: 16px; text-align: center; font-size: 13px; color: #555;">
                <small>Or enter coordinates manually:</small>
                <div style="display: flex; gap: 8px; justify-content: center; margin-top: 6px; flex-wrap: wrap;">
                    <input 
                        type="text" 
                        id="manual-lat" 
                        placeholder="Latitude" 
                        style="
                            width: 120px; 
                            padding: 8px 10px; 
                            font-size: 13px; 
                            border: 1px solid #ccc; 
                            border-radius: 6px; 
                            text-align: center;
                            font-family: 'Quicksand', sans-serif;
                        ">
                    <input 
                        type="text" 
                        id="manual-lng" 
                        placeholder="Longitude" 
                        style="
                            width: 120px; 
                            padding: 8px 10px; 
                            font-size: 13px; 
                            border: 1px solid #ccc; 
                            border-radius: 6px; 
                            text-align: center;
                            font-family: 'Quicksand', sans-serif;
                        ">
                </div>
                <button 
                    onclick="useManualLocation()" 
                    style="
                        margin-top: 30px; 
                        margin-bottom: 15px;
                        padding: 8px 16px; 
                        font-size: 13px; 
                        background: #5D7EA3; 
                        color: white; 
                        border: none; 
                        border-radius: 6px; 
                        cursor: pointer;
                        font-family: 'Quicksand', sans-serif;
                        transition: background 0.2s;
                    "
                    onmouseover="this.style.background='#4a6482'"
                    onmouseout="this.style.background='#5D7EA3'">
                    Use These Coordinates
                </button>
            </div>
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

let allIncidentData = [];
let userMarker = null;
let accuracyCircle = null;
let incidentMarkers = [];
const LINGAYEN_COORDS = [16.0212, 120.2315];
let userCoords = LINGAYEN_COORDS;
let mainMap;

let lastCheckedTime = localStorage.getItem('last_incident_view') || new Date(0).toISOString();
let newReportCount = 0;
let hasCheckedOnce = false;

function updateBadge() {
    const badge = document.getElementById('new-reports-badge');
    if (newReportCount > 0) {
        badge.textContent = newReportCount;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

async function checkForNewIncidents() {
    try {
        const res = await fetch('{{ route("incidents.fetch") }}', {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        const incidents = await res.json();
        if (!Array.isArray(incidents)) return;

        const newReports = incidents.filter(inc => 
            new Date(inc.created_at) > new Date(lastCheckedTime)
        );

        if (newReports.length > newReportCount && hasCheckedOnce) {
            newReportCount = newReports.length;
            const beep = document.getElementById('new-report-beep');
            if (beep) {
                beep.currentTime = 0;
                beep.play().catch(e => console.warn("Beep blocked:", e));
            }
        } else if (!hasCheckedOnce) {
            newReportCount = newReports.length;
            hasCheckedOnce = true;
        }
        updateBadge();
    } catch (err) {
        console.warn("Failed to check for new incidents:", err);
    }
}

setInterval(checkForNewIncidents, 15000);
checkForNewIncidents();

document.getElementById('open-user-incident-modal')?.addEventListener('click', () => {
    lastCheckedTime = new Date().toISOString();
    localStorage.setItem('last_incident_view', lastCheckedTime);
    newReportCount = 0;
    updateBadge();
    loadIncidents();
    document.getElementById('userIncidentModal').style.display = 'block';
});

document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error("❌ Leaflet failed to load!");
        alert("Map library failed to load.");
        return;
    }

    mainMap = L.map('map').setView(userCoords, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mainMap);

    window.addEventListener('resize', () => mainMap.invalidateSize());
    updateUserLocation();
    loadAndDisplayIncidents(); 

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

    document.getElementById('route-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const startInput = document.getElementById('start-from').value.trim();
        const destInput = document.getElementById('destination').value.trim();
        if (!destInput) return alert("Please enter a destination.");
        try {
            let startCoords = userCoords;
            if (startInput) startCoords = await geocode(startInput);
            const endCoords = await geocode(destInput);
            clearRoutes();
            await drawAlternateRoutes(startCoords, endCoords);
        } catch (err) {
            alert("Error: " + (err.message || "Location not found"));
        }
    });
    document.getElementById("open-incident-modal")?.addEventListener('click', () => {
        document.getElementById("incident-modal").style.display = "block";
        setTimeout(getUserLocationForIncident, 300);
    });
    document.getElementById("close-incident-modal")?.addEventListener('click', () => {
        document.getElementById("incident-modal").style.display = "none";
        if (window.incidentMap) window.incidentMap.remove();
    });
    window.onclick = (e) => {
        if (e.target.id === "incident-modal") {
            e.target.style.display = "none";
            if (window.incidentMap) window.incidentMap.remove();
        }
    };

    document.getElementById('open-traffic-modal')?.addEventListener('click', () => {
        document.getElementById('traffic-modal').style.display = 'block';
        fetchTrafficData();
    });
    document.getElementById('close-traffic-modal')?.addEventListener('click', () => {
        document.getElementById('traffic-modal').style.display = 'none';
    });
});

async function geocode(place) {
    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
    const data = await res.json();
    if (!data.length) throw new Error('Location not found');
    return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
}

function updateUserLocation() {
    if (!navigator.geolocation) return console.warn("Geolocation not supported");
    navigator.geolocation.watchPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            userCoords = [lat, lng];
            if (userMarker) mainMap.removeLayer(userMarker);
            if (accuracyCircle) mainMap.removeLayer(accuracyCircle);
            userMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'user-location-dot',
                    html: '<div class="dot"></div><div class="pulse"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(mainMap).bindPopup("Your Location").openPopup();
            accuracyCircle = L.circle([lat, lng], {
                radius: pos.coords.accuracy,
                weight: 1,
                fillColor: '#3388ff',
                fillOpacity: 0.1
            }).addTo(mainMap);
            mainMap.setView([lat, lng], 15);
        },
        (error) => {
            let msg = "";
            switch(error.code) {
                case error.PERMISSION_DENIED: msg = "Location access denied."; break;
                case error.POSITION_UNAVAILABLE: msg = "Location unavailable."; break;
                case error.TIMEOUT: msg = "Location request timed out."; break;
                default: msg = "Unknown location error.";
            }
            console.warn(msg);
        },
        { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 }
    );
}

function loadAndDisplayIncidents() {
    db.ref('incidents').on('value', (snapshot) => {
        incidentMarkers.forEach(marker => mainMap.removeLayer(marker));
        incidentMarkers = [];
        snapshot.forEach(child => {
            const data = child.val();
            if (data.lat && data.lng) {
                const color = data.status === 'resolved' ? 'gray' : 
                             { accident: 'red', traffic_jam: 'orange', road_closure: 'purple', hazard: 'yellow' }[data.type] || 'blue';
                const marker = L.marker([data.lat, data.lng], {
                    icon: L.divIcon({
                        html: `<div style="background:${color}; width:14px; height:14px; border-radius:50%; border:2px solid white;"></div>`,
                        className: 'incident-marker',
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    })
                }).bindPopup(`<b>${data.type}</b><br>${data.description || 'No details'}<br><small>Status: ${data.status === 'resolved' ? '✅ Resolved' : '🔴 Active'}</small>`).addTo(mainMap);
                incidentMarkers.push(marker);
            }
        });
    });
}

let currentRouteLayers = [];
function clearRoutes() {
    currentRouteLayers.forEach(layer => mainMap.removeLayer(layer));
    currentRouteLayers = [];
    document.getElementById('route-summary')?.classList.add('hidden');
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
            const routesToShow = data.routes.slice(0, 4);
            routesToShow.forEach((route, i) => {
                const coords = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);
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
                if (i === 0) {
                    document.getElementById('route-summary').innerHTML = `
                        🏆 <strong>Best Option: ${names[i]}</strong><br>
                        ${(route.distance / 1000).toFixed(2)} km • ${Math.round(route.duration / 60)} mins
                    `;
                    document.getElementById('route-summary').classList.remove('hidden');
                }
            });
            L.marker([end[0], end[1]]).addTo(mainMap).bindPopup("Destination").openPopup();
            return;
        }
    } catch (err) {
        console.warn("OSRM alternatives failed", err);
    }
    for (let i = 0; i < 4; i++) {
        try {
            const offsets = [{lat:+0.007,lng:-0.003},{lat:-0.007,lng:-0.003},{lat:-0.007,lng:+0.003},{lat:+0.007,lng:+0.003}];
            const offset = offsets[i] || offsets[0];
            const midLat = (start[0] + end[0]) / 2 + offset.lat;
            const midLng = (start[1] + end[1]) / 2 + offset.lng;
            const via = i === 0 ? `${start[1]},${start[0]};${end[1]},${end[0]}` : `${start[1]},${start[0]};${midLng},${midLat};${end[1]},${end[0]}`;
            const res = await fetch(`https://router.project-osrm.org/route/v1/car/${via}?geometries=geojson`);
            const data = await res.json();
            if (!data.routes?.length) continue;
            const route = data.routes[0];
            const coords = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);
            const polyline = L.polyline(coords, {
                color: colors[i],
                weight: 5,
                opacity: 0.8
            }).bindPopup(`<b>${names[i]}</b><br>Distance: ${(route.distance / 1000).toFixed(2)} km`).addTo(mainMap);
            currentRouteLayers.push(polyline);
            if (i === 0) {
                document.getElementById('route-summary').innerHTML = `
                    🏆 <strong>Best Option: ${names[i]}</strong><br>
                    ${(route.distance / 1000).toFixed(2)} km • ${Math.round(route.duration / 60)} mins
                `;
                document.getElementById('route-summary').classList.remove('hidden');
            }
        } catch (err) {
            console.warn(`Fallback route ${names[i]} failed`, err);
        }
    }
    L.marker([end[0], end[1]]).addTo(mainMap).bindPopup("Destination").openPopup();
}

// Incident Report Form
document.getElementById('incident-form')?.addEventListener('submit', async function(e) {
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
        document.getElementById('incident-form').style.display = 'none';
        const modalContent = document.querySelector('#incident-modal .modal-content');
        modalContent.insertAdjacentHTML('beforeend', `
            <div id="success-message" style="text-align:center;padding:30px;font-family:'Quicksand',sans-serif;color:#28a745;">
                <i class="fas fa-check-circle" style="font-size:48px;margin-bottom:10px;"></i>
                <h3>Report Submitted!</h3>
                <p>${data.message || 'Thank you for reporting the incident.'}</p>
                <button onclick="closeSuccess()" class="btn" style="margin-top:10px;">Close</button>
            </div>
        `);
        function closeSuccess() {
            document.getElementById('success-message')?.remove();
            document.getElementById('incident-form').style.display = 'block';
            document.getElementById('incident-form').reset();
            document.getElementById('incident-modal').style.display = 'none';
        }
    } catch (err) {
        alert('Failed to submit report.');
        console.error(err);
    }
});

// Traffic Modal
async function fetchTrafficData() {
    const loadingEl = document.getElementById('loading');
    const resultsEl = document.getElementById('traffic-results');
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
    document.getElementById('loading').style.display = 'none';
    document.getElementById('traffic-results').style.display = 'block';
    const trafficData = {
        A: data?.sensorA?.traffic === true,
        B: data?.sensorB?.traffic === true,
        C: data?.sensorC?.traffic === true,
        D: data?.sensorD?.traffic === true
    };
    ['A', 'B', 'C', 'D'].forEach(r => {
        const el = document.getElementById(`route${r}`)?.querySelector('span');
        if (el) {
            const hasTraffic = trafficData[r];
            el.textContent = hasTraffic ? 'Traffic' : 'No Traffic';
            el.className = hasTraffic ? 'traffic-yes' : 'traffic-no';
        }
    });
}

async function reverseGeocode(lat, lng) {
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, { mode: 'cors' });
        if (!res.ok) throw new Error();
        const data = await res.json();
        return data.display_name || 'Unknown location';
    } catch (err) {
        return 'Address unavailable';
    }
}

function formatDate(dateStr) {
    try {
        return new Date(dateStr).toLocaleString();
    } catch {
        return 'Invalid Date';
    }
}

function renderIncidents(data) {
    const tableBody = document.getElementById('userIncidentTableBody');
    tableBody.innerHTML = '';
    if (!data?.length) {
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No matching reports</td></tr>';
        return;
    }
    const typeLabels = {
        accident: '🚗 Accident',
        traffic_jam: '🚦 Traffic Jam',
        road_closure: '🚧 Road Closure',
        hazard: '⚠️ Hazard',
        other: 'ℹ️ Other',
        fire: '🔥 Fire',
        flooding: '🌊 Flooding'
    };
    data.forEach(item => {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lng);
        const coords = !isNaN(lat) && !isNaN(lng) ? `${lat.toFixed(6)}, ${lng.toFixed(6)}` : 'Not available';
        const displayType = typeLabels[item.title] || (item.title ? item.title.charAt(0).toUpperCase() + item.title.slice(1).replace('_', ' ') : 'Unknown');
        const statusBadge = item.status === 'resolved'
            ? '<span style="color:#17a2b8; font-weight:bold;">✅ Resolved</span>'
            : '<span style="color:#d9534f; font-weight:bold;">🔴 Active</span>';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><strong>${displayType}</strong></td>
            <td>${item.description ? item.description : '<em>No details provided</em>'}</td>
            <td><code>${coords}</code></td>
            <td class="address-cell" style="font-size:13px; color:#555;">${item.address || 'Loading address...'}</td>
            <td style="white-space:nowrap;">${formatDate(item.created_at)}</td>
            <td>${statusBadge}</td>
        `;
        tableBody.appendChild(tr);
        if (lat && lng && !item.address) {
            reverseGeocode(lat, lng).then(addr => {
                if (tr.cells[3]) {
                    tr.cells[3].textContent = addr.length > 100 ? addr.substring(0, 100) + '...' : addr;
                    const index = allIncidentData.findIndex(i => i.id == item.id);
                    if (index !== -1) allIncidentData[index].address = addr;
                }
            }).catch(() => {
                if (tr.cells[3]) tr.cells[3].textContent = "Address unavailable";
            });
        } else if (item.address && tr.cells[3]) {
            tr.cells[3].textContent = item.address;
        }
    });
}

function loadIncidents() {
    const tableBody = document.getElementById('userIncidentTableBody');
    const searchInput = document.getElementById('user-incident-search');
    tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Loading reports...</td></tr>';
    fetch('{{ route("incidents.fetch") }}', {
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(res => res.ok ? res.json() : Promise.reject('Network error'))
    .then(data => {
        tableBody.innerHTML = '';
        if (!data || !Array.isArray(data) || data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No incidents reported yet.</td></tr>';
            allIncidentData = [];
            return;
        }
        data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        allIncidentData = data;
        renderIncidents(data);

        if (!searchInput.dataset.listenerAttached) {
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = searchInput.value.toLowerCase().trim();
                    if (!query) return renderIncidents(allIncidentData);
                    const filtered = allIncidentData.filter(item => {
                        const lat = parseFloat(item.lat);
                        const lng = parseFloat(item.lng);
                        const coords = !isNaN(lat) && !isNaN(lng) ? `${lat.toFixed(6)}, ${lng.toFixed(6)}` : '';
                        const address = item.address || '';
                        const dateStr = new Date(item.created_at).toLocaleString().toLowerCase();
                        return (
                            (item.title && item.title.toLowerCase().includes(query)) ||
                            (item.description && item.description.toLowerCase().includes(query)) ||
                            coords.toLowerCase().includes(query) ||
                            address.toLowerCase().includes(query) ||
                            dateStr.includes(query)
                        );
                    });
                    renderIncidents(filtered);
                }, 300);
            });
            searchInput.dataset.listenerAttached = true;
        }
    })
    .catch(err => {
        console.error("Failed to load incident reports:", err);
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Failed to load reports. Please try again.</td></tr>';
    });
}

document.getElementById('closeUserIncidentModal')?.addEventListener('click', () => {
    document.getElementById('userIncidentModal').style.display = 'none';
});
window.onclick = (e) => {
    if (e.target.id === 'userIncidentModal') {
        e.target.style.display = 'none';
    }
};
async function getUserLocationForIncident() {
    const statusEl = document.getElementById('location-status');
    const latInput = document.getElementById('form-lat');
    const lngInput = document.getElementById('form-lng');
    const mapDiv = document.getElementById('incident-map');
    
    // Initialize mini map instantly with last known location
    if (window.incidentMap) window.incidentMap.remove();
    window.incidentMap = L.map(mapDiv).setView(userCoords, 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(window.incidentMap);
    
    // Add marker for cached coords immediately
    let marker = L.marker(userCoords).addTo(window.incidentMap)
        .bindPopup("Using last known location").openPopup();
    latInput.value = userCoords[0];
    lngInput.value = userCoords[1];
    statusEl.textContent = "Using last known location...";

    // Try to get accurate location (5s timeout)
    if (!navigator.geolocation) {
        statusEl.textContent = "Geolocation not supported.";
        return;
    }
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const [lat, lng] = [pos.coords.latitude, pos.coords.longitude];
            userCoords = [lat, lng];
            latInput.value = lat;
            lngInput.value = lng;
            marker.setLatLng([lat, lng])
                .setPopupContent("✅ Accurate location found!")
                .openPopup();
            window.incidentMap.setView([lat, lng], 17);
            statusEl.textContent = "✅ Accurate location found!";
        },
        (error) => {
            let msg = "📍 Using last known location.";
            if (error.code === error.PERMISSION_DENIED) {
                msg = "⚠️ Permission denied. Using last known location.";
            } else if (error.code === error.TIMEOUT) {
                msg = "⏱️ Location request timed out. Using last known location.";
            }
            statusEl.innerHTML = `<span style="color: #d97706;">${msg}</span>`;
        },
        { enableHighAccuracy: true, timeout: 5000, maximumAge: 15000 }
    );
}
function useManualLocation() {
    const lat = parseFloat(document.getElementById('manual-lat').value);
    const lng = parseFloat(document.getElementById('manual-lng').value);
    if (isNaN(lat) || isNaN(lng)) {
        alert('Please enter valid coordinates.');
        return;
    }
 
    document.getElementById('form-lat').value = lat;
    document.getElementById('form-lng').value = lng;

    if (window.incidentMap) {
        window.incidentMap.setView([lat, lng], 17);
        window.incidentMap.eachLayer(layer => {
            if (layer instanceof L.Marker) window.incidentMap.removeLayer(layer);
        });
        L.marker([lat, lng]).addTo(window.incidentMap).bindPopup("Manual location").openPopup();
    }
    document.getElementById('location-status').textContent = "📍 Manual location set";
}
</script>

</body>
</html>