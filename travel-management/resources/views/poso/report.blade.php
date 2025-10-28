@extends('layouts.app')
@section('content')
<div style="max-width: 800px; margin: 2rem auto; padding: 0 1rem;">
    <h1>👮 Poso Personnel Report</h1>
    <p><strong>Reporting as:</strong> {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</p>

    <form method="POST" action="{{ route('poso.report.store') }}" id="poso-incident-form">
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


    const form = document.getElementById('poso-incident-form');
    form?.addEventListener('submit', async (e) => {
        e.preventDefault(); 

        const formData = new FormData(form);
        const data = {
            type: formData.get('type'),
            lat: parseFloat(formData.get('lat')),
            lng: parseFloat(formData.get('lng')),
            unit: formData.get('unit'),
            badge_number: formData.get('badge_number'),
            description: formData.get('description')?.trim() || null
        };

        // Validate
        if (!data.type) {
            alert('Please select an incident type.');
            return;
        }
        if (isNaN(data.lat) || isNaN(data.lng)) {
            alert('Invalid location. Please allow location access or enter coordinates manually.');
            return;
        }

        try {
            const response = await fetch("{{ route('poso.report.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                form.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: #28a745;">
                        <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 1rem;"></i>
                        <h2>Report Submitted!</h2>
                        <p>${result.message}</p>
                        <a href="{{ route('poso.dashboard') }}" class="btn" style="margin-top: 1rem; display: inline-block;">Back to Dashboard</a>
                    </div>
                `;
            } else {
                throw new Error(result.message || 'Submission failed');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to submit report: ' + error.message);
        }
    });
});
</script>
@endsection