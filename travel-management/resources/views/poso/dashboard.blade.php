@extends('layouts.app')
<title>RouteLink_Poso</title>

@section('content')
<style>
    /* Apply background to body */
    body {
        background-image: url("{{ asset('images/background.png') }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        min-height: 100vh;
        margin: 0;
        padding-top: 20px; /* Optional: space from top */
        font-family: 'Poppins', sans-serif;
    }

    /* Optional: subtle overlay for better readability (remove if not needed) */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2); /* Very light white overlay */
        z-index: -1;
        pointer-events: none;
    }

    /* Keep your existing form container styles */
    .report-container {
        max-width: 720px;
        width: 100%;
        margin: 1.5rem auto;
        font-family: 'Poppins', sans-serif;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        overflow: hidden;
    }

    /* Your other styles remain unchanged */
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.95rem;
        background: #fff;
        color: #334155;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: #5D7EA3;
        box-shadow: 0 0 0 3px rgba(93, 126, 163, 0.15);
    }

    .coord-input {
        width: 110px;
        padding: 8px;
        text-align: center;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    .coord-input:focus {
        outline: none;
        border-color: #5D7EA3;
    }

    .coord-btn {
        margin-top: 6px;
        padding: 6px 16px;
        background: #5D7EA3;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background 0.2s;
    }
    .coord-btn:hover {
        background: #4b6784;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(to right, #5cb85c, #4cae4c);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(92, 184, 92, 0.3);
    }
    .submit-btn:hover {
        background: linear-gradient(to right, #4cae4c, #449d44);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(92, 184, 92, 0.4);
    }

    @media (max-width: 600px) {
        .form-control,
        .coord-input {
            font-size: 1rem;
            padding: 12px;
        }

        #poso-map {
            height: 240px;
        }

        .submit-btn {
            padding: 14px;
            font-size: 1.05rem;
        }

        div[style*="flex"] {
            flex-direction: column;
            gap: 1rem;
        }

        div[style*="flex"] > div {
            min-width: 100% !important;
        }
    }
</style>

<!-- Form Container -->
<div class="report-container">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #5D7EA3, #87A8C7); color: white; text-align: center; padding: 1.25rem;">
        <h1 style="margin: 0; font-size: 1.5rem; font-weight: 600;">Poso Personnel Report</h1>
        <p style="margin-top: 0.3rem; font-size: 0.9rem; opacity: 0.95;">
            Reporting as: <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }})
        </p>
    </div>

    <!-- Form Body -->
    <div style="padding: 1.5rem;">
        <form method="POST" action="{{ route('poso.report.store') }}" id="poso-incident-form">
            @csrf
            <input type="hidden" name="lat" id="poso-lat">
            <input type="hidden" name="lng" id="poso-lng">
            <input type="hidden" name="reporter_role" value="{{ auth()->user()->role }}">

            <!-- Incident Type -->
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; color: #333; font-size: 0.95rem;">Incident Type</label>
                <select name="type" required class="form-control">
                    <option value="">-- Select --</option>
                    <option value="accident">Accident</option>
                    <option value="traffic_jam">Traffic Jam</option>
                    <option value="road_closure">Road Closure</option>
                    <option value="hazard">Hazard</option>
                    <option value="suspicious_activity">Suspicious Activity</option>
                </select>
            </div>

            <!-- Poso Details -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; color: #333; font-size: 0.95rem;">Unit</label>
                    <input type="text" name="unit" placeholder="e.g. Patrol Unit 5" required class="form-control">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; color: #333; font-size: 0.95rem;">Badge No.</label>
                    <input type="text" name="badge_number" placeholder="e.g. POSO-1234" required class="form-control">
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; color: #333; font-size: 0.95rem;">Description</label>
                <textarea name="description" rows="3" placeholder="Add details about the incident..." class="form-control" style="resize: vertical;"></textarea>
            </div>

            <!-- Location -->
            <div style="margin-bottom: 1.75rem;">
                <label style="font-weight: 600; display: block; margin-bottom: 0.75rem; color: #333; font-size: 0.95rem;">Location</label>
                <div id="poso-map" style="height: 280px; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;"></div>
                <p id="poso-location-status" style="font-size: 0.875rem; color: #64748b; margin-top: 0.5rem;">Getting your location...</p>

                <div style="margin-top: 16px; text-align: center;">
                    <small style="color: #64748b; display: block; margin-bottom: 8px; font-size: 0.875rem;">Or enter coordinates manually:</small>
                    <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;">
                        <input type="text" id="manual-lat" placeholder="Latitude" class="coord-input">
                        <input type="text" id="manual-lng" placeholder="Longitude" class="coord-input">
                    </div>
                    <button type="button" onclick="useManualLocation()" class="coord-btn">Use These Coordinates</button>
                </div>
            </div>

            <button type="submit" class="submit-btn">Submit Report</button>
        </form>
    </div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let posoMap, currentMarker;

document.addEventListener('DOMContentLoaded', () => {
    posoMap = L.map('poso-map').setView([16.0212, 120.2315], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(posoMap);

    getUserLocationForPoso();
});

function getUserLocationForPoso() {
    const statusEl = document.getElementById('poso-location-status');
    const latInput = document.getElementById('poso-lat');
    const lngInput = document.getElementById('poso-lng');

    if (!navigator.geolocation) {
        statusEl.innerHTML = '<span style="color:#d97706;">⚠️ Geolocation not supported</span>';
        return;
    }

    statusEl.textContent = "Locating you...";
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const [lat, lng] = [pos.coords.latitude, pos.coords.longitude];
            latInput.value = lat;
            lngInput.value = lng;
            setMarker(lat, lng, "Your Location");
            posoMap.setView([lat, lng], 16);
            statusEl.innerHTML = '<span style="color:#10b981;">✅ Location acquired</span>';
        },
        () => {
            statusEl.innerHTML = '<span style="color:#d97706;">⚠️ Using default location</span>';
            latInput.value = 16.0212;
            lngInput.value = 120.2315;
            setMarker(16.0212, 120.2315, "Default Location");
        },
        { enableHighAccuracy: true, timeout: 6000, maximumAge: 15000 }
    );
}

function setMarker(lat, lng, popupText) {
    if (currentMarker) posoMap.removeLayer(currentMarker);
    currentMarker = L.marker([lat, lng]).addTo(posoMap).bindPopup(popupText).openPopup();
}

function useManualLocation() {
    const lat = parseFloat(document.getElementById('manual-lat').value);
    const lng = parseFloat(document.getElementById('manual-lng').value);
    if (isNaN(lat) || isNaN(lng)) {
        alert('Please enter valid numeric coordinates.');
        return;
    }
    document.getElementById('poso-lat').value = lat;
    document.getElementById('poso-lng').value = lng;
    setMarker(lat, lng, "Manual Location");
    posoMap.setView([lat, lng], 17);
    document.getElementById('poso-location-status').innerHTML = '<span style="color:#10b981;">✅ Manual location set</span>';
}
</script>
@endsection