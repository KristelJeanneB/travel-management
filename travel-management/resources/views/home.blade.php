<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Traffic Monitor</title>

<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

<!-- Scripts -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<header class="header">
  <nav class="nav" role="navigation" aria-label="Main menu">
    <div class="dropdown">
      <button class="dropbtn" aria-label="Menu" aria-haspopup="true" aria-expanded="false">&#9776;</button>
      <div class="dropdown-content" role="menu" aria-label="User menu" tabindex="-1">
        <a href="{{ route('settings') }}" aria-label="Settings" role="menuitem" tabindex="0">
          <!-- Settings icon -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1e3a8a" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M19.14 12.94a7.14 7.14 0 000-1.88l2.03-1.58a.5.5 0 00.11-.65l-1.92-3.32a.5.5 0 00-.61-.22l-2.39.96a7.22 7.22 0 00-1.62-.95l-.36-2.54a.5.5 0 00-.5-.43h-3.84a.5.5 0 00-.5.43l-.36 2.54a7.22 7.22 0 00-1.62.95l-2.39-.96a.5.5 0 00-.61.22l-1.92 3.32a.5.5 0 00.11.65l2.03 1.58a7.14 7.14 0 000 1.88l-2.03 1.58a.5.5 0 00-.11.65l1.92 3.32a.5.5 0 00.61.22l2.39-.96a7.22 7.22 0 001.62.95l.36 2.54a.5.5 0 00.5.43h3.84a.5.5 0 00.5-.43l.36-2.54a7.22 7.22 0 001.62-.95l2.39.96a.5.5 0 00.61-.22l1.92-3.32a.5.5 0 00-.11-.65zM12 15.5a3.5 3.5 0 113.5-3.5 3.5 3.5 0 01-3.5 3.5z"/>
          </svg>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
        <a href="#" aria-label="Log Out" role="menuitem" tabindex="0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <!-- Logout icon -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1e3a8a" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8v2h8v14h-8v2h8a2 2 0 002-2V5a2 2 0 00-2-2z"/>
          </svg>
        </a>
      </div>
    </div>
  </nav>
</header>

<!-- Welcome Greeting -->
<div id="welcome-greeting" class="welcome-greeting" role="alert" aria-live="polite">
  <h2>Welcome {{ Auth::user()->name }}</h2>
  <p>Your real-time traffic assistant</p>
</div>

<main>
  <div id="map" role="application" aria-label="Traffic map"></div>

  <button class="start-button" aria-label="Start Navigation" onclick="window.location.href='{{ route('map') }}'">Start Navigation</button>
</main>

<script>
  // Leaflet map setup
  let map = L.map('map').setView([14.5995, 120.9842], 13);
  let userCoords = null;

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19
  }).addTo(map);

  navigator.geolocation.getCurrentPosition(position => {
    userCoords = [position.coords.latitude, position.coords.longitude];
    map.setView(userCoords, 15);
    L.marker(userCoords).addTo(map).bindPopup("You are here").openPopup();
  }, () => {
    alert("Unable to access your location.");
  });

  // Welcome greeting fade out after 4 seconds
  window.addEventListener('load', () => {
    setTimeout(() => {
      const greeting = document.getElementById('welcome-greeting');
      if (greeting) {
        greeting.style.opacity = '0';
        setTimeout(() => greeting.style.display = 'none', 600);
      }
    }, 4000);
  });

  // Dropdown toggle logic with class toggle and aria-expanded update
  const dropbtn = document.querySelector('.dropbtn');
  const dropdownContent = document.querySelector('.dropdown-content');

  dropbtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isActive = dropdownContent.classList.toggle('active');
    dropbtn.setAttribute('aria-expanded', isActive);
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', (event) => {
    if (!dropbtn.contains(event.target) && !dropdownContent.contains(event.target)) {
      dropdownContent.classList.remove('active');
      dropbtn.setAttribute('aria-expanded', 'false');
    }
  });
</script>

</body>
</html>
