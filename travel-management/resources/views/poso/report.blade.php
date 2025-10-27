@extends('layouts.app')
@section('content')
<div style="max-width: 800px; margin: 2rem auto; padding: 0 1rem;">
    <h1>👮 Poso Personnel Report</h1>
    <p><strong>Reporting as:</strong> {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</p>

    <form method="POST" action="{{ route('poso.report.store') }}" id="poso-incident-form">
        @csrf
        <input type="hidden" name="lat" id="poso-lat">
        <input type="hidden" name="lng" id="poso-lng">
        
        <!-- Incident Type -->
        <div class="form-group">
            <label>Incident Type:</label>
            <select name="type" required>
                <option value="">-- Select --</option>
                <option value="accident">Accident</option>
                <option value="traffic_jam">Traffic Jam</option>
                <option value="road_closure">Road Closure</option>
                <option value="hazard">Hazard</option>
                <option value="suspicious_activity">Suspicious Activity</option>
            </select>
        </div>
        
        <!-- Poso-Specific Fields -->
        <div class="form-group">
            <label>Unit:</label>
            <input type="text" name="unit" placeholder="e.g., Patrol Unit 5" required>
        </div>
        <div class="form-group">
            <label>Badge Number:</label>
            <input type="text" name="badge_number" placeholder="e.g., POSO-1234" required>
        </div>
        
        <!-- Description -->
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" rows="3" placeholder="Add details..."></textarea>
        </div>
        
        <!-- Location Map -->
        <div class="form-group">
            <label>Location:</label>
            <div id="poso-map" style="height: 300px; border: 1px solid #ddd; border-radius: 8px;"></div>
            <p id="poso-location-status">Getting your location...</p>
        </div>
        
        <button type="submit" class="btn">Submit Report</button>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('poso-map').setView([16.0212, 120.2315], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    // Get location
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            document.getElementById('poso-lat').value = lat;
            document.getElementById('poso-lng').value = lng;
            L.marker([lat, lng]).addTo(map).bindPopup("Your Location").openPopup();
            map.setView([lat, lng], 16);
            document.getElementById('poso-location-status').textContent = "✅ Location set";
        },
        (err) => {
            document.getElementById('poso-location-status').textContent = "⚠️ Use manual coordinates below";
        }
    );
});
</script>
@endsection