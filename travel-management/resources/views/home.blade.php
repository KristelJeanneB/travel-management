HOME

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Traffic Monitor - Home</title>

  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>

  <!-- Header -->
  <header class="header">
    <nav class="nav" aria-label="Main menu">
      <div class="nav-left">
        <h1 class="app-title">Traffic Monitor</h1>
      </div>
      <div class="nav-right dropdown">
        <button class="dropbtn" aria-label="Menu" aria-haspopup="true" aria-expanded="false">&#9776;</button>
        <div class="dropdown-content" role="menu" aria-label="User menu" tabindex="-1">
          <a href="{{ route('settings') }}" aria-label="Settings" role="menuitem" tabindex="0">
            <i class="fas fa-cog"></i> Settings
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
          </form>
          <!---<a href="#" aria-label="Log Out" role="menuitem" tabindex="0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Log Out
          </a> -->
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="main-content">

    <!-- Hero Section with Video Background -->
    <section class="hero video-hero">
      <video autoplay muted loop playsinline class="hero-bg-video">
        <source src="{{ asset('images/vid1.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="hero-overlay"></div>
      <div class="hero-text fade-in-right">
        <h2>Navigate Smarter, Drive Safer</h2>
        <p>Real-time traffic updates and intelligent navigation for your daily commute.</p>
        <a href="{{ route('map') }}" class="start-button">Go to Live Map</a>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
      <h2>How It Works</h2>
      <div class="steps">
        <div class="step">
          <i class="fas fa-map-marked-alt"></i>
          <h3>1. Open the Website</h3>
          <p>Access our web app from any device.</p>
        </div>
        <div class="step">
          <i class="fas fa-traffic-light"></i>
          <h3>2. View Traffic</h3>
          <p>See real-time congestion and road incidents.</p>
        </div>
        <div class="step">
          <i class="fas fa-car"></i>
          <h3>3. Drive Smart</h3>
          <p>Follow smart navigation to avoid delays.</p>
        </div>
      </div>
    </section>

    <!-- Features Section with Images -->
    <section class="features-grid">
      <div class="feature-card">
        <img src="{{ asset('images/map2.jpg') }}" alt="Real-time Map">
        <div class="feature-info">
          <h3>Live Map</h3>
          <p>Track real-time traffic conditions and congestion areas instantly.</p>
        </div>
      </div>

      <div class="feature-card">
        <img src="{{ asset('images/map3.jpg') }}" alt="Smart Navigation">
        <div class="feature-info">
          <h3>Smart Navigation</h3>
          <p>Get intelligent turn-by-turn directions based on live road data.</p>
        </div>
      </div>

      <div class="feature-card">
        <img src="{{ asset('images/map4.jpg') }}" alt="Incident Alerts">
        <div class="feature-info">
          <h3>Incident Alerts</h3>
          <p>Receive alerts for accidents, roadblocks, and unexpected delays.</p>
        </div>
      </div>
    </section>

    <!-- Quick Stats -->
    <section class="quick-stats">
      <h2>Why Traffic Monitor?</h2>
      <div class="stats">
        <div class="stat">
          <h3>+20</h3>
          <p>Roads Covered</p>
        </div>
        <div class="stat">
          <h3>5/min</h3>
          <p>Live Updates</p>
        </div>
        <div class="stat">
          <h3>1</h3>
          <p>Lingayen Monitored</p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
      <h2>Ready to Get Moving?</h2>
      <p>Launch the live map and experience hassle-free travel.</p>
      <a href="{{ route('map') }}" class="start-button">Launch Map</a>
    </section>

    <!-- Our Team Section -->
    <section class="our-team">
      <h2>Meet the Team</h2>
      <div class="team-members">
        <div class="member">
          <img src="{{ asset('images/ray.jpg') }}" alt="Team Member 1">
          <h4>RAY-EM</h4>
          <p>Project Lead</p>
        </div>
        <div class="member">
          <img src="{{ asset('images/kristel.jpg') }}" alt="Team Member 3">
          <h4>KRISTEL</h4>
          <p>Developer</p>
        </div>
        <div class="member">
          <img src="{{ asset('images/noel.jpg') }}" alt="Team Member 4">
          <h4>NOEL</h4>
          <p>Developer</p>
        </div>
        <div class="member">
          <img src="{{ asset('images/bea.jpg') }}" alt="Team Member 5">
          <h4>BABY BEA</h4>
          <p>UI/UX Designer</p>
        </div>
        <div class="member">
          <img src="{{ asset('images/raniel.jpg') }}" alt="Team Member 2">
          <h4>RANIEL</h4>
          <p>Documentator</p>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; {{ date('Y') }} Traffic Monitor. Group 9 - PHINMA University of Pangasinan.</p>
  </footer>

  <!-- Scripts -->
  <script>
    
    const dropbtn = document.querySelector('.dropbtn');
    const dropdownContent = document.querySelector('.dropdown-content');

    dropbtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isActive = dropdownContent.classList.toggle('active');
      dropbtn.setAttribute('aria-expanded', isActive);
    });

    document.addEventListener('click', (event) => {
      if (!dropbtn.contains(event.target) && !dropdownContent.contains(event.target)) {
        dropdownContent.classList.remove('active');
        dropbtn.setAttribute('aria-expanded', 'false');
      }
    });
  </script>

</body>
</html>
