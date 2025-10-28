<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RouteLink</title>
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }
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
                display: none;
            }
            .route-guidance.active {
                display: block;
            }
            .map-container {
                width: 100%;
                height: 100vh;
                position: relative;
            }
            #map {
                touch-action: auto !important;
                pointer-events: auto !important;
            }
            .route-guidance:not(.active) {
                display: none !important;
            }
            #map {
                width: 100%;
                height: 100vh;
                margin: 0;
                padding: 0;
                touch-action: auto;
                pointer-events: auto;
            }
            .route-guidance:not(.active) {
                pointer-events: none;
                opacity: 0;
            }
            .route-guidance.active {
                pointer-events: auto;
                opacity: 1;
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
            #legend {
                position: fixed;
                bottom: 60px;
                left: 20px;
                width: 320px;
                padding: 20px;
                background: #f4edf2;
                border-radius: 12px;
                box-shadow: 0 0 12px rgba(0,0,0,0.2);
                z-index: 1060; 
            }
            #legend strong {
                font-size: 14px;
            }
            #legend span {
                font-size: 12px;
            }
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
            #user-incident-search {
                width: 140px;
                font-size: 13px;
                padding: 6px 10px;
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
        .badge { display: inline-block; }
        .address-cell {
            font-style: normal;
            color: #333;
        }
        #route-summary.visible {
            display: block;
        }
        #route-summary .best-label {
            font-size: 16px;
            font-weight: 700;
            color: #28a745;
        }
        #route-summary .route-details {
            font-size: 14px;
            color: #333;
            margin-top: 5px;
        }
        #route-summary .traffic-note {
            font-size: 13px;
            margin-top: 5px;
        }
        .app-logo {
            height: 50px; 
            width: auto;
            margin-right: 10px;
            vertical-align: middle;
        }
        .app-title {
            display: inline-block;
            margin: 0;
            font-size: 1.4rem;
            color: #333;
        }
        .nav-left {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="header">
    <nav class="nav">
        <div class="nav-left">
            <img src="{{ asset('images/Logo.png') }}" alt="RouteLink Logo" class="app-logo">
            <h1 class="app-title">RouteLink</h1>
        </div>
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
                <input type="text" id="start-from" placeholder="Start location (leave empty for current location)">
            </div>
        </div>
        <div class="search-row">
            <div class="input-with-icon">
                <i class="fas fa-flag-checkered"></i>
                <input type="text" id="destination" placeholder="Enter destination city or place" required>
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
    <div id="alternate-routes-list" style="margin-top: 30px;">
        <strong>Alternate Routes</strong><br>
        <span style="color:red;">●</span> Route A<br>
        <span style="color:blue;">●</span> Route B<br>
        <span style="color:green;">●</span> Route C<br>
        <span style="color:purple;">●</span> Route D<br>
        <small>All share start & destination</small>
    </div>
</div>

<!-- ✅ Updated Traffic Modal (Lane-based) -->
<div id="traffic-modal" class="modal">
    <div class="modal-content">
        <span id="close-traffic-modal" class="modal-close">&times;</span>
        <h2>Traffic Status</h2>
        <div id="loading">Loading traffic data...</div>
        <div id="traffic-results" style="display:none;">
            <div class="route-status" id="lane1-status">
                <strong>Lane 1:</strong> <span id="lane1-count">--</span> vehicles • <span id="lane1-level">--</span>
            </div>
            <div class="route-status" id="lane2-status">
                <strong>Lane 2:</strong> <span id="lane2-count">--</span> vehicles • <span id="lane2-level">--</span>
            </div>
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                <h3 style="font-size: 16px; margin: 10px 0;">📍 Sensor Locations</h3>
                <div id="sensor-info-list" style="font-size: 14px; line-height: 1.5;"></div>
            </div>
        </div>
    </div>
</div>

<div class="map-container">
    <div id="map"></div>
    <div id="route-summary" class="route-summary hidden"></div>
    <div id="direction-arrow"></div>
</div>

<!-- Incident Modals -->
<div id="userIncidentModal" class="modal">
    <div class="modal-content" style="max-width: 800px; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
            <h2>Community Incident Reports</h2>
            <input type="text" id="user-incident-search" placeholder="Search: type, location, date..." style="padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 14px; width: 200px;" />
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
        <span id="close-incident-modal" class="modal-close">&times;</span>
        <h2>Report an Incident</h2>
        <form id="incident-form">@csrf
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
                        <input type="text" id="manual-lat" placeholder="Latitude" style="width: 120px; padding: 8px 10px; font-size: 13px; border: 1px solid #ccc; border-radius: 6px; text-align: center; font-family: 'Quicksand', sans-serif;">
                        <input type="text" id="manual-lng" placeholder="Longitude" style="width: 120px; padding: 8px 10px; font-size: 13px; border: 1px solid #ccc; border-radius: 6px; text-align: center; font-family: 'Quicksand', sans-serif;">
                    </div>
                    <button onclick="useManualLocation()" style="margin-top: 30px; margin-bottom: 15px; padding: 8px 16px; font-size: 13px; background: #5D7EA3; color: white; border: none; border-radius: 6px; cursor: pointer; font-family: 'Quicksand', sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#4a6482'" onmouseout="this.style.background='#5D7EA3'">
                        Use These Coordinates
                    </button>
                </div>
            </div>
            <button type="submit" class="btn" style="width:100%; padding:12px;">Submit Report</button>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

// Traffic status: A/B = Lane1, C/D = Lane2
let trafficStatus = { A: false, B: false, C: false, D: false };
let currentRouteLayers = [];
window.lastRouteData = null;

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

document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
        console.error("❌ Leaflet failed to load!");
        alert("Map library failed to load.");
        return;
    }
    mainMap = L.map('map', {
        dragging: true,
        touchZoom: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        boxZoom: true,
        zoomControl: true
    }).setView(userCoords, 14);

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
            window.endCoords = endCoords;
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
    });

    document.getElementById('close-traffic-modal')?.addEventListener('click', () => {
        document.getElementById('traffic-modal').style.display = 'none';
    });

    document.getElementById('open-user-incident-modal')?.addEventListener('click', () => {
        lastCheckedTime = new Date().toISOString();
        localStorage.setItem('last_incident_view', lastCheckedTime);
        newReportCount = 0;
        updateBadge();
        loadIncidents();
        document.getElementById('userIncidentModal').style.display = 'block';
    });

    startTrafficListener(); // ✅ Real-time lane traffic
});

// ✅ Updated Traffic Listener (uses /current_counts)
function startTrafficListener() {
    db.ref('current_counts').on('value', (snapshot) => {
        const data = snapshot.val();
        if (!data) return;

        const lane1Count = data.lane1?.count ?? 0;
        const lane1Level = data.lane1?.level ?? 'Unknown';
        const lane2Count = data.lane2?.count ?? 0;
        const lane2Level = data.lane2?.level ?? 'Unknown';

        // Update modal
        document.getElementById('lane1-count').textContent = lane1Count;
        document.getElementById('lane1-level').textContent = lane1Level;
        document.getElementById('lane2-count').textContent = lane2Count;
        document.getElementById('lane2-level').textContent = lane2Level;

        // Color levels
        const getColor = (level) => level.includes('Heavy') ? '#e74c3c' : level.includes('Light') ? '#f39c12' : '#27ae60';
        document.getElementById('lane1-level').style.color = getColor(lane1Level);
        document.getElementById('lane2-level').style.color = getColor(lane2Level);

        // Map lanes to routes
        const isLane1Heavy = lane1Level.includes('Heavy');
        const isLane2Heavy = lane2Level.includes('Heavy');
        trafficStatus = {
            A: isLane1Heavy,
            B: isLane1Heavy,
            C: isLane2Heavy,
            D: isLane2Heavy
        };

        // Update modal visibility
        document.getElementById('loading').style.display = 'none';
        document.getElementById('traffic-results').style.display = 'block';

        // Update route popups if routes exist
        if (window.lastRouteData && window.lastRouteData.length > 0) {
            currentRouteLayers.forEach((polyline, i) => {
                const r = window.lastRouteData[i];
                if (!r) return;
                const routeLetter = String.fromCharCode(65 + i);
                const hasTraffic = trafficStatus[routeLetter];
                const trafficNote = hasTraffic ? '⚠️ Traffic reported' : '✅ Clear';
                polyline.bindPopup(`
                    <b>${r.name}</b><br>
                    Distance: ${(r.distance / 1000).toFixed(2)} km<br>
                    Time: ${Math.round(r.duration / 60)} mins<br>
                    ${trafficNote}
                `);
            });
            suggestBestRoute(false);
        }
    });

    // Keep sensor locations listener (from traffic_logs)
    db.ref('traffic_logs').limitToLast(1).on('value', (snapshot) => {
        let data = null;
        snapshot.forEach(child => { data = child.val(); });
        if (data?.sensor_locations) {
            const letters = ['A', 'B', 'C', 'D'];
            const routeNames = {
                A: 'Route A - West Approach',
                B: 'Route B - South Approach',
                C: 'Route C - North Approach',
                D: 'Route D - East Approach'
            };
            let html = '';
            letters.forEach(letter => {
                const loc = data.sensor_locations[letter];
                if (loc && !isNaN(loc.lat) && !isNaN(loc.lng)) {
                    const lat = parseFloat(loc.lat).toFixed(6);
                    const lng = parseFloat(loc.lng).toFixed(6);
                    html += `
                        <div><strong>Sensor ${letter}</strong>: ${routeNames[letter]}<br>
                        <small>Lat: ${lat}, Lng: ${lng}</small></div>
                        <hr style="margin: 8px 0; border-color: #f0f0f0;">
                    `;
                }
            });
            document.getElementById('sensor-info-list').innerHTML = html || '<em>No sensor data available.</em>';
        }
    });
}

// === All other core functions ===
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
            if (data.lat && data.lng && data.status === 'approved') {
                const color = { accident: 'red', traffic_jam: 'orange', road_closure: 'purple', hazard: 'yellow' }[data.type] || 'blue';
                const marker = L.marker([data.lat, data.lng], {
                    icon: L.divIcon({
                        html: `<div style="background:${color}; width:14px; height:14px; border-radius:50%; border:2px solid white;"></div>`,
                        className: 'incident-marker',
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    })
                }).bindPopup(`<b>${data.type}</b><br>${data.description || 'No details'}`).addTo(mainMap);
                incidentMarkers.push(marker);
            }
        });
    });
}

function clearRoutes() {
    currentRouteLayers.forEach(layer => mainMap.removeLayer(layer));
    currentRouteLayers = [];
    document.getElementById('route-summary')?.classList.add('hidden');
}

async function drawAlternateRoutes(start, end) {
    clearRoutes();
    const colors = ['red', 'blue', 'green', 'purple'];
    const url = `https://router.project-osrm.org/route/v1/car/${start[1]},${start[0]};${end[1]},${end[0]}?alternatives=true&geometries=geojson&overview=full`;
    let routesDrawn = false;
    if (window.destinationMarker) {
        mainMap.removeLayer(window.destinationMarker);
    }
    try {
        const res = await fetch(url);
        if (!res.ok) throw new Error('Network error');
        const data = await res.json();
        if (data.routes && data.routes.length > 1) {
            const routesToShow = data.routes.slice(0, 4);
            const routePromises = routesToShow.map(async (route, i) => {
                const coords = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                const streetName = await getStreetNamesFromRoute(coords);
                return { ...route, coords, streetName, index: i };
            });
            const enrichedRoutes = await Promise.all(routePromises);
            enrichedRoutes.forEach(({ coords, streetName, distance, duration }, i) => {
                const polyline = L.polyline(coords, {
                    color: colors[i],
                    weight: 5,
                    opacity: 0.8
                }).addTo(mainMap);
                currentRouteLayers.push(polyline);
                if (!window.lastRouteData) window.lastRouteData = [];
                window.lastRouteData[i] = { distance, duration, name: streetName };
            });
            routesDrawn = true;
        }
    } catch (err) {
        console.warn("OSRM alternatives failed", err);
    }
    if (!routesDrawn) {
        const offsets = [
            {lat: +0.007, lng: -0.003},
            {lat: -0.007, lng: -0.003},
            {lat: -0.007, lng: +0.003},
            {lat: +0.007, lng: +0.003}
        ];
        for (let i = 0; i < 4; i++) {
            try {
                const offset = offsets[i] || offsets[0];
                const midLat = (start[0] + end[0]) / 2 + offset.lat;
                const midLng = (start[1] + end[1]) / 2 + offset.lng;
                const via = `${start[1]},${start[0]};${midLng},${midLat};${end[1]},${end[0]}`;
                const res = await fetch(`https://router.project-osrm.org/route/v1/car/${via}?geometries=geojson`);
                const data = await res.json();
                if (!data.routes?.length) continue;
                const route = data.routes[0];
                const coords = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);
                const streetName = await getStreetNamesFromRoute(coords);
                const polyline = L.polyline(coords, {
                    color: colors[i],
                    weight: 5,
                    opacity: 0.8
                }).addTo(mainMap);
                currentRouteLayers.push(polyline);
                if (!window.lastRouteData) window.lastRouteData = [];
                window.lastRouteData[i] = {
                    distance: route.distance,
                    duration: route.duration,
                    name: streetName
                };
            } catch (err) {
                console.warn(`Fallback route ${i} failed`, err);
                if (!window.lastRouteData) window.lastRouteData = [];
                window.lastRouteData[i] = null;
            }
        }
    }
    window.destinationMarker = L.marker([end[0], end[1]])
        .bindPopup("Destination")
        .addTo(mainMap)
        .openPopup();

    currentRouteLayers.forEach((polyline, i) => {
        const r = window.lastRouteData?.[i];
        if (!r) return;
        const hasTraffic = trafficStatus[String.fromCharCode(65 + i)];
        const trafficNote = hasTraffic ? '⚠️ Traffic reported' : '✅ Clear';
        polyline.bindPopup(`
            <b>${r.name}</b><br>
            Distance: ${(r.distance / 1000).toFixed(2)} km<br>
            Time: ${Math.round(r.duration / 60)} mins<br>
            ${trafficNote}
        `);
    });
    suggestBestRoute(true); 
    updateAlternateRoutesList();
}

async function getAddressFromCoords(lat, lng) {
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
            headers: { 'User-Agent': 'TrafficMonitorApp' }
        });
        const data = await res.json();
        return data.display_name || 'Unknown location';
    } catch (err) {
        return 'Address unavailable';
    }
}

function updateRouteSummary(routeLetter, routeName, distanceKm, timeMins, hasTraffic) {
    const summaryEl = document.getElementById('route-summary');
    if (!summaryEl) return;
    const trafficNote = hasTraffic ? '⚠️ Traffic' : '✅ No Traffic';
    summaryEl.innerHTML = `
        <div class="best-label">🏆 Best Option: Route ${routeLetter}</div>
        <div class="route-details">${routeName}</div>
        <div class="route-details">${distanceKm} km • ${timeMins} mins</div>
        <div class="traffic-note" style="color: ${hasTraffic ? '#e74c3c' : '#27ae60'}; font-weight: 600;">
            ${trafficNote}
        </div>
    `;
    summaryEl.classList.remove('hidden');
    summaryEl.classList.add('visible');
}

let arrow = document.getElementById('direction-arrow');
if (typeof DeviceOrientationEvent !== 'undefined' && typeof DeviceOrientationEvent.requestPermission === 'function') {
    document.addEventListener('click', function requestPermission() {
        DeviceOrientationEvent.requestPermission()
            .then(permission => {
                if (permission === 'granted') {
                    window.addEventListener('deviceorientationabsolute', handleOrientation);
                    arrow.style.display = 'block';
                }
            })
            .catch(console.error);
        document.removeEventListener('click', requestPermission); 
    }, { once: true });
} else {
    window.addEventListener('deviceorientation', handleOrientation);
    arrow.style.display = 'block';
}

function handleOrientation(event) {
    let alpha = event.alpha; 
    if (alpha === null || alpha === undefined) return;
    alpha = (alpha + 360) % 360;
    arrow.style.transform = `translateX(-50%) rotate(${alpha}deg)`;
}

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

function updateAlternateRoutesList() {
    const routeList = document.getElementById('alternate-routes-list');
    if (!routeList || !window.lastRouteData) return;
    routeList.innerHTML = '';
    const title = document.createElement('strong');
    title.textContent = 'Alternate Routes';
    routeList.appendChild(title);
    const routeNames = ['A', 'B', 'C', 'D'];
    const colors = ['red', 'blue', 'green', 'purple'];
    for (let i = 0; i < 4; i++) {
        const routeData = window.lastRouteData[i];
        const routeItem = document.createElement('div');
        routeItem.className = 'route-item';
        routeItem.style.display = 'flex';
        routeItem.style.alignItems = 'flex-start';
        routeItem.style.gap = '10px';
        routeItem.style.margin = '8px 0';
        routeItem.style.padding = '10px';
        routeItem.style.backgroundColor = '#f8f9fa';
        routeItem.style.borderRadius = '8px';
        routeItem.style.fontSize = '14px';
        const dot = document.createElement('div');
        dot.style.width = '12px';
        dot.style.height = '12px';
        dot.style.borderRadius = '50%';
        dot.style.backgroundColor = colors[i];
        dot.style.marginTop = '4px';
        dot.style.flexShrink = '0';
        const info = document.createElement('div');
        info.style.flex = '1';
        info.style.minWidth = '0';
        if (!routeData) {
            const name = document.createElement('div');
            name.innerHTML = `<strong>Route ${routeNames[i]}</strong>: <em>Unavailable</em>`;
            info.appendChild(name);
        } else {
            const routeName = routeData.name || `Route ${routeNames[i]}`;
            const distanceKm = (routeData.distance / 1000).toFixed(1);
            const timeMins = Math.round(routeData.duration / 60);
            const hasTraffic = trafficStatus[routeNames[i]];
            const statusText = hasTraffic ? '⚠️ Traffic' : '✅ Clear';
            const statusColor = hasTraffic ? '#e74c3c' : '#27ae60';
            const name = document.createElement('div');
            name.innerHTML = `<strong>Route ${routeNames[i]}</strong>: ${routeName}`;
            name.style.marginBottom = '4px';
            const metrics = document.createElement('div');
            metrics.innerHTML = `<small style="color:#555;">${distanceKm} km • ${timeMins} mins</small>`;
            metrics.style.marginBottom = '4px';
            const status = document.createElement('div');
            status.innerHTML = `<small style="color:${statusColor}; font-weight:600;">${statusText}</small>`;
            info.appendChild(name);
            info.appendChild(metrics);
            info.appendChild(status);
        }
        routeItem.appendChild(dot);
        routeItem.appendChild(info);
        routeList.appendChild(routeItem);
    }
    const footer = document.createElement('div');
    footer.innerHTML = '<small style="color:#777; font-style:italic;">All share start & destination</small>';
    footer.style.marginTop = '12px';
    footer.style.textAlign = 'center';
    routeList.appendChild(footer);
}

function suggestBestRoute(showAlert = true) {
    const routeNames = ['A', 'B', 'C', 'D'];
    let bestRoute = null;
    let bestDuration = Infinity;
    for (let i = 0; i < routeNames.length; i++) {
        const name = routeNames[i];
        const hasTraffic = trafficStatus[name];
        const routeData = window.lastRouteData?.[i];
        if (!routeData || hasTraffic) continue;
        if (routeData.duration < bestDuration) {
            bestDuration = routeData.duration;
            bestRoute = name;
            bestRouteIndex = i;
        }
    }
    if (bestRoute === null) {
        bestDuration = Infinity;
        for (let i = 0; i < routeNames.length; i++) {
            const routeData = window.lastRouteData?.[i];
            if (!routeData) continue;
            if (routeData.duration < bestDuration) {
                bestDuration = routeData.duration;
                bestRoute = routeNames[i];
                bestRouteIndex = i;
            }
        }
    }
    currentRouteLayers.forEach(polyline => {
        if (polyline) {
            polyline.setStyle({ weight: 5, opacity: 0.6 });
        }
    });
    if (window.bestRouteGlow) {
        mainMap.removeLayer(window.bestRouteGlow);
        window.bestRouteGlow = null;
    }
    if (bestRouteIndex !== null && currentRouteLayers[bestRouteIndex]) {
        const bestPolyline = currentRouteLayers[bestRouteIndex];
        const coords = bestPolyline.getLatLngs();
        const color = bestPolyline.options.color;
        window.bestRouteGlow = L.polyline(coords, {
            color: color,
            weight: 16,
            opacity: 0.3,
            className: 'best-route-glow'
        }).addTo(mainMap);
        bestPolyline.setStyle({
            weight: 8,
            opacity: 1.0
        });
        bestPolyline.bringToFront();
    }
    let message = '';
    if (bestRoute !== null) {
        const routeData = window.lastRouteData[bestRouteIndex];
        const mins = Math.round(bestDuration / 60);
        const km = (routeData.distance / 1000).toFixed(2);
        const routeName = routeData.name || `Route ${bestRoute}`;
        const hasTraffic = trafficStatus[bestRoute];
        message = hasTraffic
            ? `⚠️ All routes have traffic. Best: Route ${bestRoute} — ${routeName} (${mins} mins).`
            : `🟢 Recommended: Route ${bestRoute} — ${routeName} (no traffic, ${mins} mins).`;
        updateRouteSummary(bestRoute, routeName, km, mins, hasTraffic);
    } else {
        message = 'ℹ️ Unable to determine best route.';
        document.getElementById('route-summary').classList.add('hidden');
    }
    if (showAlert) {
        const alertDiv = document.createElement('div');
        alertDiv.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: 'Quicksand', sans-serif;
            font-size: 15px;
            z-index: 1060;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            max-width: 90%;
            text-align: center;
        `;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);
        setTimeout(() => {
            if (alertDiv.parentNode) alertDiv.parentNode.removeChild(alertDiv);
        }, 6000);
    }
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

async function getStreetNamesFromRoute(coords, maxSamples = 6) {
    const total = coords.length;
    if (total === 0) return 'Unnamed Street';
    const indices = new Set();
    indices.add(0);
    indices.add(total - 1);
    if (total > 2) {
        const step = Math.max(1, Math.floor(total / (maxSamples - 2)));
        for (let i = step; i < total - 1 && indices.size < maxSamples; i += step) {
            indices.add(i);
        }
    }
    const sampledCoords = Array.from(indices).map(i => coords[i]);
    const streetNames = [];
    for (const [lat, lng] of sampledCoords) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`, {
                headers: { 'User-Agent': 'TrafficMonitorApp' }
            });
            const data = await res.json();
            const road = data.address?.road || data.address?.pedestrian || data.address?.path;
            if (!streetNames.includes(road)) {
                streetNames.push(road);
                if (streetNames.length >= 3) break;
            }
        } catch (err) {
            console.warn("Reverse geocode failed", err);
        }
    }
    if (streetNames.length === 1) return streetNames[0];
    return streetNames.slice(0, 3).join(' → ');
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
        const statusBadge = (() => {
            switch(item.status) {
                case 'approved': return '<span style="color:#28a745; font-weight:bold;">✅ Approved</span>';
                case 'pending': return '<span style="color:#ffc107; font-weight:bold;">⏳ Pending</span>';
                case 'rejected': return '<span style="color:#dc3545; font-weight:bold;">❌ Rejected</span>';
                default: return '<span style="color:#17a2b8; font-weight:bold;">ℹ️ ' + item.status + '</span>';
            }
        })();
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
    if (window.incidentMap) window.incidentMap.remove();
    window.incidentMap = L.map(mapDiv).setView(userCoords, 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(window.incidentMap);
    let marker = L.marker(userCoords).addTo(window.incidentMap)
        .bindPopup("Using last known location").openPopup();
    latInput.value = userCoords[0];
    lngInput.value = userCoords[1];
    statusEl.textContent = "Using last known location...";
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