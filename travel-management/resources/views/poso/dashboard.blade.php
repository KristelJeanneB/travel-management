@extends('layouts.app')
<title>RouteLink_Poso</title>
@section('content')
<div style="max-width: 700px; margin: 2rem auto; padding: 1rem; font-family:'Quicksand',sans-serif;">

    <h1 style="text-align:center; margin-bottom:1rem;">👮 Poso Personnel Report</h1>
    <p style="text-align:center; margin-bottom:2rem;">
        <strong>Reporting as:</strong> {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
    </p>

    <form method="POST" action="{{ route('poso.report.store') }}" id="poso-incident-form">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="lat" id="poso-lat">
        <input type="hidden" name="lng" id="poso-lng">
        <input type="hidden" name="reporter_role" value="{{ auth()->user()->role }}">
        

        <!-- Incident Type -->
        <div class="form-group" style="margin-bottom:1rem;">
            <label style="font-weight:600;">Incident Type:</label>
            <select name="type" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                <option value="">-- Select --</option>
                <option value="accident">Accident</option>
                <option value="traffic_jam">Traffic Jam</option>
                <option value="road_closure">Road Closure</option>
                <option value="hazard">Hazard</option>
                <option value="suspicious_activity">Suspicious Activity</option>
            </select>
        </div>

        <!-- Poso-Specific Fields -->
        <div class="form-group" style="margin-bottom:1rem;">
            <label style="font-weight:600;">Unit:</label>
            <input type="text" name="unit" placeholder="e.g., Patrol Unit 5" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
        </div>
        <div class="form-group" style="margin-bottom:1rem;">
            <label style="font-weight:600;">Badge Number:</label>
            <input type="text" name="badge_number" placeholder="e.g., POSO-1234" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
        </div>

        <!-- Description -->
        <div class="form-group" style="margin-bottom:1rem;">
            <label style="font-weight:600;">Description:</label>
            <textarea name="description" rows="3" placeholder="Add details..." style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;"></textarea>
        </div>

        <!-- Location Map -->
        <div class="form-group" style="margin-bottom:1rem;">
            <label style="font-weight:600;">Location:</label>
            <div id="poso-map" style="height:350px; border:1px solid #ddd; border-radius:8px; margin-bottom:8px;"></div>
            <p id="poso-location-status" style="font-size:13px; color:#555;">Getting your location...</p>
            <div style="margin-top:12px; text-align:center; font-size:13px; color:#555;">
                <small>Or enter coordinates manually:</small>
                <div style="display:flex; gap:8px; justify-content:center; margin-top:6px; flex-wrap:wrap;">
                    <input type="text" id="manual-lat" placeholder="Latitude" style="width:120px; padding:8px; border-radius:6px; text-align:center; border:1px solid #ccc;">
                    <input type="text" id="manual-lng" placeholder="Longitude" style="width:120px; padding:8px; border-radius:6px; text-align:center; border:1px solid #ccc;">
                </div>
                <button type="button" onclick="useManualLocation()" style="margin-top:10px; padding:6px 14px; background:#5D7EA3; color:white; border:none; border-radius:6px; cursor:pointer;">Use These Coordinates</button>
            </div>
        </div>

        <button type="submit" style="width:100%; padding:12px; background:#28a745; color:white; border:none; border-radius:8px; cursor:pointer; font-size:16px;">Submit Report</button>
    </form>
</div>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let posoMap, currentMarker;

document.addEventListener('DOMContentLoaded', () => {
    posoMap = L.map('poso-map').setView([16.0212, 120.2315], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(posoMap);

    getUserLocationForPoso();
});
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

fetch('/incidents', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        type: 'traffic_jam',
        description: '...',
        lat: 12.345678,
        lng: 123.456789,
        unit: '2',
        badge_number: '123456'
    })
})
.then(res => res.json())
.then(data => console.log(data))
.catch(err => console.error(err));

function getUserLocationForPoso() {
    const statusEl = document.getElementById('poso-location-status');
    const latInput = document.getElementById('poso-lat');
    const lngInput = document.getElementById('poso-lng');

    if (!navigator.geolocation) {
        statusEl.textContent = "Geolocation not supported.";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const [lat, lng] = [pos.coords.latitude, pos.coords.longitude];
            latInput.value = lat;
            lngInput.value = lng;
            setMarker(lat, lng, "Your Location");
            posoMap.setView([lat, lng], 16);
            statusEl.textContent = "✅ Location set";
        },
        (err) => {
            statusEl.innerHTML = `<span style="color:#d97706;">⚠️ Using default location</span>`;
            latInput.value = 16.0212;
            lngInput.value = 120.2315;
            setMarker(16.0212, 120.2315, "Default Location");
        },
        { enableHighAccuracy: true, timeout: 5000, maximumAge: 15000 }
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
        alert('Please enter valid coordinates.');
        return;
    }
    document.getElementById('poso-lat').value = lat;
    document.getElementById('poso-lng').value = lng;
    setMarker(lat, lng, "Manual Location");
    posoMap.setView([lat, lng], 17);
}

// Form submission
document.getElementById('poso-incident-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    if (!data.type) return alert('Please select an incident type.');
    if (!data.lat || !data.lng) return alert('Location is required.');

    try {
        const res = await fetch("{{ route('poso.report.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        const result = await res.json();

        if (res.ok) {
            this.innerHTML = `
                <div style="text-align:center; padding:30px; color:#28a745;">
                    <i class="fas fa-check-circle" style="font-size:48px; margin-bottom:10px;"></i>
                    <h3>Report Submitted!</h3>
                    <p>${result.message || 'Thank you for your report, Officer.'}</p>
                    <button onclick="window.location.reload()" style="margin-top:15px; padding:8px 16px; background:#5D7EA3; color:white; border:none; border-radius:6px; cursor:pointer;">Submit Another</button>
                </div>
            `;
        } else {
            alert(result.message || 'Failed to submit report.');
        }
    } catch (err) {
        console.error(err);
        alert('Network error. Please try again.');
    }
});
</script>
@endsection
