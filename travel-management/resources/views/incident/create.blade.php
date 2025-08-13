@extends('layouts.app')

@section('title', 'Report Incident')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .background-image {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("/images/background.png");
        background-size: cover;
        filter: blur(5px);
        z-index: -1;
    }

    .container {
        display: flex;
        min-height: 100vh;
    }

    .map-container {
        flex: 1;
        height: 100vh;
    }

    .form-container {
        width: 400px;
        background: white;
        padding: 30px;
        border-radius: 12px 0 0 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        z-index: 1;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: bold;
    }

    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-group textarea {
        resize: vertical;
        height: 80px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    .back-link {
        display: inline-block;
        margin-top: 15px;
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    #route-summary {
        margin-top: 10px;
        padding: 10px;
        background-color: #f0f0f0;
        border-radius: 6px;
        font-size: 14px;
        color: #555;
    }
</style>

<div class="background-image"></div>

<div class="container">
    <div id="map" style="height: 400px;"></div>
    <div class="form-container">
        <h2>Report an Incident</h2>
        <form id="incident-form" method="POST" action="{{ route('incident.store') }}">
            @csrf

            <div class="form-group">
                <label for="incident-type"><i class="fas fa-exclamation-triangle"></i> Incident Type:</label>
                <select id="incident-type" name="type" required>
                    <option value="" disabled selected>Select an incident</option>
                    <option value="accident">Accident</option>
                    <option value="traffic_jam">Traffic Jam</option>
                    <option value="road_closure">Road Closure</option>
                    <option value="hazard">Hazard on Road</option>
                </select>
            </div>

            <div class="form-group">
                <label for="incident-description"><i class="fas fa-comment"></i> Details (Optional):</label>
                <textarea id="incident-description" name="description" rows="3" placeholder="Add more details..."></textarea>
            </div>

            <input type="hidden" name="lat" id="form-lat" required>
            <input type="hidden" name="lng" id="form-lng" required>

            <button type="submit" onclick="showSuccessAlert()">Report Incident</button>
        </form>

        <div id="route-summary" style="display: none;">
            <strong>Directions to Incident:</strong><br>
            <span id="route-text"></span>
        </div>

        <a href="{{ route('map') }}" class="back-link">← Back to Map</a>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    function showSuccessAlert() {
        setTimeout(() => {
            alert('✅ Report Submitted! Thank you for reporting the Incident.');
        }, 500);
    }

const map = L.map('map').setView([14.5995, 120.9842], 13); 
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker = null;
let clickedLat = null;
let clickedLng = null;

map.on('click', function(e) {
    clickedLat = e.latlng.lat;
    clickedLng = e.latlng.lng;

    document.getElementById('form-lat').value = clickedLat;
    document.getElementById('form-lng').value = clickedLng;

    if (marker) {
        marker.setLatLng([clickedLat, clickedLng]);
    } else {
        marker = L.marker([clickedLat, clickedLng]).addTo(map);
    }

    marker.bindPopup(`Incident Location: ${clickedLat.toFixed(6)}, ${clickedLng.toFixed(6)}`).openPopup();
});

        if (marker) {
            marker.setLatLng([clickedLat, clickedLng]);
        } else {
            marker = L.marker([clickedLat, clickedLng]).addTo(map);
        }

        marker.bindPopup(`<b>Incident Location</b><br>${clickedLat.toFixed(6)}, ${clickedLng.toFixed(6)}`).openPopup();

        navigator.geolocation.getCurrentPosition(position => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            if (routeControl) map.removeControl(routeControl);

            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(userLat, userLng),
                    L.latLng(clickedLat, clickedLng)
                ],
                routeWhileDragging: false,
                show: false
            }).addTo(map);

            routeControl.on('routesfound', function(e) {
                const route = e.routes[0].summary;
                document.getElementById('route-text').innerHTML = 
                    `Distance: ${(route.totalDistance / 1000).toFixed(2)} km • Time: ${Math.round(route.totalTime / 60)} mins`;
                document.getElementById('route-summary').style.display = 'block';
            });
        }, () => {
            document.getElementById('route-text').innerHTML = "Could not get your location.";
            document.getElementById('route-summary').style.display = 'block';
        });

    navigator.geolocation.getCurrentPosition(
        position => {
            map.setView([position.coords.latitude, position.coords.longitude], 15);
            L.marker([position.coords.latitude, position.coords.longitude])
                .addTo(map)
                .bindPopup("You are here")
                .openPopup();
        },
        () => {
            console.log("Could not get your location.");
        }
    );
</script>
@endsection