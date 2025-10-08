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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand&display=swap');


.btn-route {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: #5D7EA3 !important; /* Force color */
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
    height: 100%;
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
        z-index: 1060; /* Above map, modals, and controls */
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
        <label for="start-from">
            <i class="fas fa-location-dot"></i> Start from:
        </label>
    <input 
        type="text" 
        id="start-from" 
        placeholder="Leave empty to use current location">
    </div>

    <div class="form-group">
        <label for="destination">
            <i class="fas fa-flag-checkered"></i> Destination:
        </label>
    <input 
        type="text" 
        id="destination" 
        placeholder="Enter city/place name">
    </div>

    <button type="submit" class="btn-route">
    <i class="fas fa-directions"></i> Get Directions
</button>

<div class="report-incident-trigger">
    <button id="open-incident-modal" type="button" class="btn-report">
        <i class="fas fa-exclamation-circle"></i> Report Incident
    </button>
</div>

<div class="report-incident-trigger">
    <button id="open-user-incident-modal" class="btn-view-reports">
        <i class="fas fa-list-alt"></i> View All Incident Reports
    </button>
</div>
    </form>

    <button id="open-traffic-modal" class="btn" style="margin-top: 15px;">
        <i class="fas fa-car-side"></i> Show Traffic Status
    </button>

    <div id="" style="margin-top: 30px;">
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
        <span id="closeUserIncidentModal" class="modal-close">&times;</span>
        <h2>Community Incident Reports</h2>
        <p style="color: #555; margin-bottom: 15px;">See real-time reports from other users.</p>

        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Full Address</th>
                    <th>Reported On</th>
                    <th>Status</th>
                </tr>
            </thead>
             <tbody id="userIncidentTableBody">
                <tr><td colspan="5" style="text-align:center;">Loading reports...</td></tr>
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

document.addEventListener('DOMContentLoaded', function () {
    let userMarker = null;
    let accuracyCircle = null;
    let incidentMarkers = [];


    if (typeof L === 'undefined') {
        console.error("❌ Leaflet failed to load!");
        alert("Map library failed to load.");
        return;
    }

   
const incidentsRef = db.ref('incidents').on('value', (snapshot) => {
    clearAllMarkers();

    snapshot.forEach(child => {
        const data = child.val();
        createMarkerOnMap(data); 
        
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('userIncidentModal');
    const openBtn = document.getElementById('open-user-incident-modal');
    const closeBtn = document.getElementById('closeUserIncidentModal');
    const tableBody = document.getElementById('userIncidentTableBody');

    openBtn?.addEventListener('click', () => {
        loadIncidents();
        modal.style.display = 'block';
    });


    closeBtn?.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.onclick = (e) => {
        if (e.target === modal) modal.style.display = 'none';
    };

    function loadIncidents() {
        tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Loading...</td></tr>';

        fetch('{{ route("incidents.fetch") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            tableBody.innerHTML = '';

            if (!data || !Array.isArray(data) || data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No incidents reported yet.</td></tr>';
                return;
            }

            data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            data.forEach(item => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lng);
                const coords = !isNaN(lat) && !isNaN(lng)
                    ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                    : 'Not available';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.title || 'N/A'}</td>
                    <td>${item.description || 'No details'}</td>
                    <td><code>${coords}</code></td>
                    <td style="font-size:13px; color:#555;">Loading address...</td>
                    <td>${new Date(item.created_at).toLocaleString()}</td>
                `;
                tableBody.appendChild(tr);

                if (lat && lng) {
                    reverseGeocode(lat, lng).then(addr => {
                        tr.cells[3].textContent = addr.length > 100 
                            ? addr.substring(0, 100) + '...' 
                            : addr;
                    }).catch(() => {
                        tr.cells[3].textContent = "Address unavailable";
                    });
                } else {
                    tr.cells[3].textContent = "No location";
                }
            });
        })
        .catch(err => {
            console.error("Failed to load incidents:", err);
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:red;">Error loading data.</td></tr>';
        });
    }

    
});

function formatDate(dateStr) {
    try {
        return new Date(dateStr).toLocaleString();
    } catch {
        return 'Invalid Date';
    }
}

function loadAndDisplayIncidents() {
    incidentsRef.on('value', (snapshot) => {

        incidentMarkers.forEach(marker => mainMap.removeLayer(marker));
        incidentMarkers = [];

        snapshot.forEach(child => {
            const data = child.val();
            const id = child.key;

            if (data.lat && data.lng) {
                let iconColor;
                switch(data.type) {
                    case 'accident':
                        iconColor = 'red';
                        break;
                    case 'traffic_jam':
                        iconColor = 'orange';
                        break;
                    case 'road_closure':
                        iconColor = 'purple';
                        break;
                    case 'hazard':
                        iconColor = 'yellow';
                        break;
                    default:
                        iconColor = 'blue';
                }

                const marker = L.marker([data.lat, data.lng], {
                    icon: L.divIcon({
                        className: 'incident-marker',
                        html: `<div style="
                            background: ${iconColor};
                            width: 14px;
                            height: 14px;
                            border-radius: 50%;
                            border: 2px solid white;
                            box-shadow: 0 0 5px rgba(0,0,0,0.5);
                        "></div>`,
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    })
                }).addTo(mainMap).bindPopup(`
                    <b>${data.title}</b><br>
                    ${data.description || 'No details'}<br>
                    <small>Status: ${data.status}</small>
                `);

                incidentMarkers.push(marker);
            }
        });
    }, (error) => {
        console.error("Failed to load incidents from Firebase:", error);
    });
loadAndDisplayIncidents();
}

    const LINGAYEN_COORDS = [16.0212, 120.2315];
    let userCoords = LINGAYEN_COORDS;

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

const mainMap = L.map('map').setView(userCoords, 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(mainMap);

window.addEventListener('resize', function() {
    mainMap.invalidateSize();
});

updateUserLocation();
    function updateUserLocation() {
    if (!navigator.geolocation) {
        console.warn("Geolocation not supported");
        return;
    }

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
            console.error("Location error:", error);
            let msg = "";
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msg = "Location access denied.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = "Location unavailable.";
                    break;
                case error.TIMEOUT:
                    msg = "Location request timed out.";
                    break;
                default:
                    msg = "Unknown location error.";
            }
            console.warn(msg);
        },
        {
            enableHighAccuracy: true,
            maximumAge: 10000,
            timeout: 10000
        }
    );
}
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
            const routesToShow = data.routes.slice(0, 4);

            routesToShow.forEach((route, i) => {
                const coords = route.geometry.coordinates.map(coord => [
                    coord[1], 
                    coord[0]  
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

                if (i === 0) {
                    document.getElementById('route-summary').innerHTML = `
                        Best Option: ${(route.distance / 1000).toFixed(2)} km • 
                        ${Math.round(route.duration / 60)} mins
                    `;
                    document.getElementById('route-summary').classList.remove('hidden');
                }
            });

            L.marker([end[0], end[1]]).addTo(mainMap).bindPopup("Destination").openPopup();
            return;
        }
    } catch (err) {
        console.warn("OSRM alternatives failed, using manual detours", err);
    }

    for (let i = 0; i < 4; i++) {
        try {
            let midLat, midLng;

            const offsets = [
                { lat: +0.007, lng: -0.003 }, // NE
                { lat: -0.007, lng: -0.003 }, // SE
                { lat: -0.007, lng: +0.003 }, // SW
                { lat: +0.007, lng: +0.003 }  // NW
            ];

            const offset = offsets[i] || offsets[0];
            midLat = (start[0] + end[0]) / 2 + offset.lat;
            midLng = (start[1] + end[1]) / 2 + offset.lng;

            const isDirect = i === 0; 
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

    L.marker([end[0], end[1]]).addTo(mainMap).bindPopup("Destination").openPopup();
}


    async function createDetourRoutes(start, end) {
        const names = ['Route A', 'Route B', 'Route C'];
        const colors = ['red', 'blue', 'green'];

        for (let i = 0; i < 3; i++) {
            let url;

            if (i === 0) {
        
                url = `https://router.project-osrm.org/route/v1/car/${start[1]},${start[0]};${end[1]},${end[0]}?geometries=geojson`;
            } else {
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

    routeForm?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const startInput = document.getElementById('start-from').value.trim();
    const destInput = document.getElementById('destination').value.trim();
    if (!destInput) return alert("Please enter a destination.");

    try {
        let startCoords;

        if (startInput) {
            startCoords = await geocode(startInput);
        } else {
            if (userCoords[0] && userCoords[1]) {
                startCoords = userCoords;
            } else {
                startCoords = [16.0212, 120.2315]; 
            }
        }

        const endCoords = await geocode(destInput);

        clearRoutes();
        await drawAlternateRoutes(startCoords, endCoords);

    } catch (err) {
        alert("Error: " + (err.message || "Location not found"));
    }
});

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

document.getElementById('incident-form').style.display = 'none';

const modalContent = document.querySelector('#incident-modal .modal-content');
modalContent.insertAdjacentHTML('beforeend', `
    <div id="success-message" style="
        text-align: center;
        padding: 30px;
        font-family: 'Quicksand', sans-serif;
        color: #28a745;
    ">
        <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 10px;"></i>
        <h3>Report Submitted!</h3>
        <p>${data.message || 'Thank you for reporting the incident.'}</p>
        <button onclick="closeSuccess()" class="btn" style="margin-top: 10px;">Close</button>
    </div>
`);


function closeSuccess() {
    const successMsg = document.getElementById('success-message');
    if (successMsg) successMsg.remove();
    document.getElementById('incident-form').style.display = 'block';
    incidentForm.reset();
    incidentModal.style.display = 'none';
}
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

        trafficData = {
            A: data?.sensorA?.traffic === true,
            B: data?.sensorB?.traffic === true,
            C: data?.sensorC?.traffic === true,
            D: data?.sensorD?.traffic === true
        };

        ['A', 'B', 'C', 'D'].forEach(r => {
            const el = document.getElementById(`route${r}`).querySelector('span');
            const hasTraffic = trafficData[r];
            el.textContent = hasTraffic ? 'Traffic' : 'No Traffic';
            el.className = hasTraffic ? 'traffic-yes' : 'traffic-no';
        });

        const start = userCoords;
        const end = window.lastEndCoords;
        if (start && end) {
            drawAlternateRoutes(start, end);
        }
    }
   document.querySelector('#userIncidentModal thead tr').innerHTML = `
    <th>Type</th>
    <th>Description</th>
    <th>Location (Coords)</th>
    <th>Full Address</th>
    <th>Reported</th>
    <th>Status</th>
`;

const modal = document.getElementById('userIncidentModal');
const openBtn = document.getElementById('open-user-incident-modal');
const closeBtn = document.getElementById('closeUserIncidentModal');
const tableBody = document.getElementById('userIncidentTableBody');


async function reverseGeocode(lat, lng) {
    try {
        const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`,
            { mode: 'cors' }
        );
        if (!res.ok) throw new Error();
        const data = await res.json();
        return data.display_name || 'Unknown location';
    } catch (err) {
        return 'Address unavailable';
    }
}

function loadIncidents() {
    const tableBody = document.getElementById('userIncidentTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Loading reports...</td></tr>';

    fetch('{{ route("incidents.fetch") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network error');
        return res.json();
    })
    .then(data => {
        tableBody.innerHTML = '';

        if (!data || !Array.isArray(data) || data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No incidents reported yet.</td></tr>';
            return;
        }

        data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

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
            const coords = !isNaN(lat) && !isNaN(lng)
                ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                : 'Not available';

           const displayType = typeLabels[item.title] || (item.title ? item.title.charAt(0).toUpperCase() + item.title.slice(1).replace('_', ' ') : 'Unknown');

    
            const statusBadge = item.status === 'resolved'
                ? '<span style="color:#17a2b8; font-weight:bold;">✅ Resolved</span>'
                : '<span style="color:#d9534f; font-weight:bold;">🔴 Active</span>';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${displayType}</strong></td>
                <td>${item.description ? item.description : '<em>No details provided</em>'}</td>
                <td><code>${coords}</code></td>
                <td style="font-size:13px; color:#555;">Loading address...</td>
                <td style="white-space:nowrap;">${formatDate(item.created_at)}</td>
                <td>${statusBadge}</td>
            `;
            tableBody.appendChild(tr);
            if (lat && lng) {
                reverseGeocode(lat, lng).then(addr => {
                    if (tr.cells[3]) {
                        tr.cells[3].textContent = addr.length > 100 
                            ? addr.substring(0, 100) + '...' 
                            : addr;
                    }
                }).catch(() => {
                    if (tr.cells[3]) tr.cells[3].textContent = "Address unavailable";
                });
            } else {
                if (tr.cells[3]) tr.cells[3].textContent = "No location";
            }
        });
    })
    .catch(err => {
        console.error("Failed to load incident reports:", err);
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Failed to load reports. Please try again.</td></tr>';
    });
}

openBtn?.addEventListener('click', () => {
    loadIncidents();
    modal.style.display = 'block';
});

closeBtn?.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.onclick = (e) => {
    if (e.target === modal) modal.style.display = 'none';
};

db.ref('incidents').on('value', (snapshot) => {
    window.incidentMarkers = window.incidentMarkers || [];
    window.incidentMarkers.forEach(marker => {
        if (mainMap.hasLayer(marker)) mainMap.removeLayer(marker);
    });
    window.incidentMarkers = [];

    snapshot.forEach(child => {
        const data = child.val();
        if (!data.lat || !data.lng) return;

        const color = data.status === 'resolved' ? 'gray' : 
                     { accident: 'red', traffic_jam: 'orange', road_closure: 'purple', hazard: 'yellow' }[data.type] || 'blue';

        const statusText = data.status === 'resolved' ? '✅ Resolved' : '🔴 Active';

        const marker = L.marker([data.lat, data.lng], {
            icon: L.divIcon({
                html: `<div style="background:${color}; width:14px; height:14px; border-radius:50%; border:2px solid white;"></div>`,
                className: 'incident-marker',
                iconSize: [18, 18],
                iconAnchor: [9, 9]
            })
        }).bindPopup(`<b>${data.type}</b><br>${data.description || 'No details'}<br><small>Status: ${statusText}</small>`).addTo(mainMap);

        window.incidentMarkers.push(marker);
    });
});
});
</script>

</body>
</html>