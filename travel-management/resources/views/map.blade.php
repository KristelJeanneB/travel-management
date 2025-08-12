<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Traffic Monitor</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/map.css') }}">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
          <a href="{{ route('settings') }}">
            <i class="fas fa-cog"></i> Settings
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Log Out
          </a>
        </div>
      </div>
    </nav>
  </div>

  <!-- Route Planner -->
  <div class="route-guidance">
    <h3>Route Planner</h3>
    <form id="route-form">
      <div class="form-group">
        <label for="start-from"><i class="fas fa-map-marker-alt"></i> Start from:</label>
        <input type="text" id="start-from" placeholder="Leave empty to use current location">
      </div>
      <div class="form-group">
        <label for="destination"><i class="fas fa-map-marker-alt"></i> Destination:</label>
        <input type="text" id="destination" placeholder="Enter city/place name">
      </div>
      <button type="submit">Get Directions</button>
    </form>

    <div class="report-incident-trigger">
      <button id="open-incident-modal">Report Incident</button>
    </div>
  </div>

  <!-- Modal -->
  <div id="incident-modal" class="modal">
    <div class="modal-content">
      <span class="modal-close" id="close-incident-modal">&times;</span>
      <h3>Report Incident</h3>
      <form id="incident-form">
        <div class="form-group">
          <label for="incident-type"><i class="fas fa-exclamation-triangle"></i> Incident Type:</label>
          <select id="incident-type" required>
            <option value="" disabled selected>Select an incident</option>
            <option value="accident">Accident</option>
            <option value="traffic_jam">Traffic Jam</option>
            <option value="road_closure">Road Closure</option>
            <option value="hazard">Hazard on Road</option>
          </select>
        </div>
        <div class="form-group">
          <label for="incident-description"><i class="fas fa-comment"></i> Add Location:</label>
          <textarea id="incident-description" rows="3" placeholder="Optional details..."></textarea>
        </div>
        <button type="submit">Report Incident</button>
      </form>
    </div>
  </div>

  <div id="map"></div>
  <div id="route-summary" class="route-summary hidden"></div>

  <!-- Scripts -->
  <script>
    // Dropdown
    const dropdownBtn = document.getElementById('dropdown-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');
    dropdownBtn.addEventListener('click', () => {
      dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', (e) => {
      if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.style.display = 'none';
      }
    });

    // Leaflet Map Init
    let map = L.map('map').setView([14.5995, 120.9842], 13);
    let userCoords = null;
    let routeControl = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    navigator.geolocation.getCurrentPosition(
      position => {
        userCoords = [position.coords.latitude, position.coords.longitude];
        map.setView(userCoords, 15);
        L.marker(userCoords).addTo(map).bindPopup("You are here").openPopup();
      },
      () => {
        alert("Unable to access your location. Please type it manually.");
      }
    );

    async function geocode(place) {
      const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`);
      const data = await res.json();
      if (!data || data.length === 0) throw new Error('Location not found');
      return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }

    document.getElementById('route-form').addEventListener('submit', async function(e) {
      e.preventDefault();
      const startInput = document.getElementById('start-from').value.trim();
      const destInput = document.getElementById('destination').value.trim();
      if (!destInput) return alert("Please enter a destination.");

      try {
        const startCoords = startInput ? await geocode(startInput) : userCoords;
        const endCoords = await geocode(destInput);
        if (!startCoords) return alert("Unable to determine starting point.");
        if (routeControl) map.removeControl(routeControl);

        routeControl = L.Routing.control({
          waypoints: [L.latLng(startCoords), L.latLng(endCoords)],
          router: L.Routing.osrmv1(),
          lineOptions: { styles: [{ color: 'blue', weight: 5 }] },
          show: false,
          addWaypoints: false,
          draggableWaypoints: false
        }).addTo(map);

        routeControl.on('routesfound', function(e) {
          const r = e.routes[0].summary;
          const summary = `Distance: ${(r.totalDistance / 1000).toFixed(2)} km • Estimated time: ${Math.round(r.totalTime / 60)} mins`;
          const summaryBox = document.getElementById('route-summary');
          summaryBox.innerHTML = summary;
          summaryBox.classList.remove('hidden');
        });

      } catch (err) {
        alert(err.message || "Error fetching route.");
      }
    });

    // Modal logic
    const modal = document.getElementById("incident-modal");
    document.getElementById("open-incident-modal").onclick = () => modal.style.display = "block";
    document.getElementById("close-incident-modal").onclick = () => modal.style.display = "none";
    window.onclick = e => { if (e.target == modal) modal.style.display = "none"; }

    // Incident form
    document.getElementById('incident-form').addEventListener('submit', async function(e) {
      e.preventDefault();
      const type = document.getElementById('incident-type').value;
      const description = document.getElementById('incident-description').value.trim();
      if (!type) return alert('Please select an incident type.');

      try {
        const res = await fetch("{{ route('incidents.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ type, description })
        });

        const data = await res.json();
        alert(data.message || 'Incident reported successfully.');
        this.reset();
        modal.style.display = "none";
      } catch (err) {
        console.error(err);
        alert('Failed to report the incident. Please try again.');
      }
    });
  </script>
</body>
</html>
