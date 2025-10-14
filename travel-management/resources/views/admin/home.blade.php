<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
   
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #E1CBD7;
        color: #333;
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .header {
        background-color: #86A8CF;
        height: 56px;
        display: flex;
        align-items: center;
        padding: 0 24px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1100;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
    }

    .dashboard-container {
        position: relative;
        width: 95%;
        max-width: 100%;
        margin: 80px auto 20px;
        border-radius: 14px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: row;
        min-height: calc(100vh - 100px);
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        background: #86A8CF;
        color: white;
        padding: 20px 0;
        min-height: 100%;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li {
        padding: 15px 20px;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar li:hover,
    .sidebar li.active {
        background: rgba(255, 255, 255, 0.25);
        padding-left: 28px;
        font-weight: 600;
    }

    .sidebar li.active {
        background: rgba(255, 255, 255, 0.3);
        border-left: 4px solid white;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        font-weight: 500;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        padding: 35px;
        background: white;
        min-height: 100%;
    }

    header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        gap: 14px;
    }

    .search-bar {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .search-bar:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
    }

    .profile-btn {
        background: #86A8CF;
        border: none;
        color: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .profile-btn:hover {
        background: #6a8cb3;
    }

    .overview h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
        font-size: 24px;
        font-weight: 600;
    }

    .card-group {
        display: flex;
        gap: 24px;
        justify-content: center;
        flex-wrap: wrap;
        padding: 10px 0;
    }

    .card {
        background: #ffffff;
        border: 1px solid #dfe6eb;
        border-radius: 12px;
        padding: 24px 20px;
        width: 250px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s cubic-bezier(0.25, 0.7, 0.25, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        border-color: #a0cfff;
    }

    .card p {
        margin-bottom: 8px;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        letter-spacing: 0.2px;
    }

    .card small {
        display: block;
        font-size: 14px;
        color: #6b7a89;
        margin-top: 6px;
        line-height: 1.4;
    }
#reportIncidentModal .modal-content {
    max-width: 500px;
    padding: 25px;
    position: relative;
}

#reportIncidentModal .close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #555;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s ease;
}

#reportIncidentModal .close:hover {
    color: #e74c3c;
}

#reportIncidentModal h2 {
    margin-top: 0;
    margin-bottom: 16px;
    color: #2c3e50;
    font-size: 22px;
    text-align: center;
}

#location-status {
    text-align: center;
    margin-bottom: 16px;
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

#reportIncidentForm label {
    display: block;
    margin: 14px 0 6px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 15px;
}

#reportIncidentForm select,
#reportIncidentForm textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    font-family: 'Segoe UI', sans-serif;
    transition: border-color 0.3s;
}

#reportIncidentForm select:focus,
#reportIncidentForm textarea:focus {
    outline: none;
    border-color: #86A8CF;
    box-shadow: 0 0 0 3px rgba(134, 168, 207, 0.2);
}

#reportIncidentForm textarea {
    resize: vertical;
    min-height: 80px;
}

#incident-map {
    width: 100%;
    height: 220px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin: 12px 0;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}
#reportIncidentForm .btn {
    background: #86A8CF;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
    margin-top: 10px;
}

#reportIncidentForm .btn:hover {
    background: #6a8cb3;
}

#success-message {
    text-align: center;
    padding: 25px 10px !important;
}

#success-message i {
    color: #28a745;
    margin-bottom: 12px;
}

#success-message h3 {
    margin: 10px 0;
    color: #28a745;
}

#success-message button {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    margin-top: 15px;
    transition: background 0.3s;
}

#success-message button:hover {
    background: #218838;
}
    /* Modal Base Style */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        margin: 5% auto;
        padding: 25px;
        border-radius: 12px;
        width: 90%;
        max-width: 800px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        position: relative;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .modal-header h3 {
        margin: 0;
        color: #007bff;
        font-weight: 700;
    }

    .close-btn {
        font-size: 26px;
        background: none;
        border: none;
        cursor: pointer;
        color: #555;
        transition: color 0.3s ease, transform 0.2s ease;
    }

    .close-btn:hover {
        color: #007bff;
        transform: rotate(90deg);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    table thead tr {
        background-color: #007bff;
        color: white;
    }

    table th, table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tbody tr:hover {
        background-color: #f1f7ff;
    }


    .status-toggle-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
        width: 100%;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .status-toggle-btn:hover:not(:disabled) {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    .status-toggle-btn:disabled {
        opacity: 0.8;
        cursor: default;
    }

    .btn-resolved {
        background: #17a2b8 !important;
    }

    .confirm-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .confirm-btn:hover {
        background-color: #218838;
    }

    .confirm-btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }

    .card i.icon {
        font-size: 36px;
        margin-bottom: 12px;
        display: block;
        transition: transform 0.2s ease;
    }

    .card:hover i.icon {
        transform: scale(1.1);
    }

    .route-status {
        font-size: 16px;
        margin: 10px 0;
        padding: 8px;
        border-radius: 6px;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
    }
    .route-status span.traffic-yes {
        color: #e74c3c;
        font-weight: bold;
    }
    .route-status span.traffic-no {
        color: #27ae60;
        font-weight: bold;
    }

    #traffic-modal {
        z-index: 1001;
    }
    .stats {
  display: flex;
  justify-content: space-around;
  gap: 10px;
  margin: 20px 0;
}
.stat-card {
  background: #f5f7fa;
  border-radius: 12px;
  padding: 10px 15px;
  text-align: center;
  flex: 1;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.stat-card h3 {
  margin: 0;
  color: #2c3e50;
}
    /* Toast Notification */
    #toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        font-size: 14px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-container {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            padding: 10px 0;
        }

        .card-group {
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 220px;
            padding: 20px 16px;
        }

        .card p {
            font-size: 17px;
        }

        .card small {
            font-size: 13px;
        }

        .main-content {
            padding: 20px;
        }

        .modal-content {
            width: 95%;
            padding: 15px;
        }

        .close-btn {
            font-size: 24px;
        }
    }

    .status-toggle-btn.delete-btn {
        background: #dc3545;
        margin-top: 6px;
    }

    .status-toggle-btn.delete-btn:hover:not(:disabled) {
        background: #c82333;
    }

    #confirmModal .modal-content {
        background: white;
    }

    /* Search in Incident Modal */
    #incident-search {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        width: 200px;
        margin-left: 10px;
    }

    @media (max-width: 768px) {
        #incident-search {
            width: 140px;
            font-size: 13px;
            padding: 6px 10px;
        }
    }

    /* Mobile-friendly incident reports table */
    @media (max-width: 768px) {
        #incidentModal .modal-content {
            padding: 15px;
            max-height: 90vh;
            overflow-y: auto;
        }

        #incidentModal table {
            width: 100%;
            font-size: 13px;
        }

        #incidentModal th,
        #incidentModal td {
            padding: 8px 6px;
            white-space: nowrap;
        }

        #incidentModal .modal-content > :not(.modal-header):not(h3):not(p) {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    @media (max-width: 480px) {
        #incidentModal th:nth-child(4),
        #incidentModal td:nth-child(4) {
            display: none;
        }
    }
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

</style>
</head>

<body>

<div class="header">
    <h1>Admin Panel</h1>
</div>

<div class="dashboard-container">
    <nav class="sidebar" aria-label="Sidebar Navigation">
        <ul>
            <li class="active">
                <a href="{{ route('homeAdmin') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('alerts') }}">
                    <i class="fas fa-bell"></i> Alerts 
                    <span id="alert-count-badge" style="
                        float: right;
                        background: #dc3545;
                        color: white;
                        font-size: 12px;
                        padding: 2px 6px;
                        border-radius: 10px;
                        display: none;
                    ">0</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
            </li>
        </ul>
    </nav>

    <main class="main-content" role="main">
        <header>
            <button class="profile-btn" aria-label="User Profile">
                <i class="fas fa-user"></i>
            </button>
        </header>

        <section class="overview" aria-labelledby="overview-heading">
    <h2 id="overview-heading">Dashboard Overview</h2>
    <div class="card-group">
   
        <div class="card" id="totalUsersCard" role="button" tabindex="0">
            <i class="fas fa-users icon" style="color: #3498db;"></i>
            <p>Total Users</p>
            <small id="userCount">Loading...</small>
        </div>

        <!--<div class="card" id="payments-card" role="button" tabindex="0">
            <i class="fas fa-wallet icon" style="color: #27ae60;"></i>
            <p>Payments</p>
            <small>User Payments</small>
        </div>-->

        <div class="card" id="accidentReportsBtn" role="button" tabindex="0">
            <i class="fas fa-car-crash icon" style="color: #e74c3c;"></i>
            <p>Accident Reports</p>
            <small>User Reports</small>
        </div>
        <div class="card" id="reportIncidentBtn" role="button" tabindex="0">
            <i class="fas fa-plus-circle icon" style="color: #f39c12;"></i>
            <p>Report Incident</p>
            <small>Log a new incident</small>
        </div>

       <div class="card" id="open-traffic-modal" role="button" tabindex="0">
            <i class="fas fa-route icon" style="color: green;"></i>
            <p>Traffic Status</p>
            <small>Real-Time Traffic Status</small>
        </div>
        <div class="card" id="open-traffic-analytics" role="button" tabindex="0">
            <i class="fas fa-chart-line icon" style="color: dodgerblue;"></i>
            <p>Traffic Analytics</p>
            <small>View Trends & Patterns</small>
            </div>
        </div>
</section>
    </main>
</div>
<div id="traffic-analytics-modal" class="modal">
  <div class="modal-content" style="max-width:800px;">
    <span id="close-traffic-analytics" class="close">&times;</span>
    <h2>Traffic Analytics Dashboard</h2>

    <div class="stats">
      <div class="stat-card">
        <h3 id="totalReports">0</h3>
        <p>Total Reports</p>
      </div>
      <div class="stat-card">
        <h3 id="heavyPercent">0%</h3>
        <p>Heavy Traffic</p>
      </div>
      <div class="stat-card">
        <h3 id="avgSpeed">-- km/h</h3>
        <p>Average Speed</p>
      </div>
    </div>

    <div class="chart-container" style="margin: 20px auto; max-width: 350px;">
        <canvas id="trafficChart"></canvas>
    </div>


    <div class="chart-container" style="margin-top:30px;">
      <canvas id="trendChart" height="150"></canvas>
    </div>
  </div>
</div>
<div id="traffic-status-modal" class="modal">
  <div class="modal-content">
    <span class="close" data-close="traffic-status-modal">&times;</span>
    <h2>Real-Time Traffic Status</h2>
    <p>Displays live traffic data and congestion levels.</p>
  </div>
  <div id="traffic-map" style="height:400px;width:100%;border-radius:8px;"></div>
</div>

<div id="traffic-modal" class="modal">
    <div class="modal-content">
        <button id="close-traffic-modal" class="close-btn" aria-label="Close">&times;</button>
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

<div id="payments-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="payments-modal-title">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="payments-modal-title">User Payments</h3>
            <button class="close-btn" id="close-modal">&times;</button>
        </div>
        <div id="payments-content">
            <p>Loading payments...</p>
        </div>
    </div>
</div>

<div id="usersModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="users-modal-title">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="users-modal-title">All Users</h3>
            <button class="close-btn" id="closeUsersModal">&times;</button>
        </div>
        <table id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="incidentModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="incident-modal-title">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="incident-modal-title">Incident Reports</h3>
            <div style="display: flex; align-items: center; gap: 10px;">
                <input 
                    type="text" 
                    id="incident-search" 
                    placeholder="Search: type, location, date..." 
                />
                <button class="close-btn" id="closeIncidentModal">&times;</button>
            </div>
        </div>
        <table>
            <thead>
    <tr>
        <th>Type</th>
        <th>Description</th>
        <th>Coords</th>
        <th>Address</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th> 
    </tr>
</thead>
            <tbody id="incidentTableBody"></tbody>
        </table>
    </div>
</div>
<div id="reportIncidentModal" class="modal">
    <div class="modal-content">
        <button id="closeReportIncidentModal" class="close">&times;</button>
        <h2>Report an Incident</h2>
        <p id="location-status">📍 Getting your location...</p>
        <form id="reportIncidentForm">
            <label for="incident-type">Incident Type:</label>
            <select id="incident-type" required>
                <option value="">-- Select Type --</option>
                <option value="accident">Accident</option>
                <option value="traffic_jam">Traffic Jam</option>
                <option value="road_closure">Road Closure</option>
                <option value="hazard">Hazard</option>
            </select>

            <label for="incident-description">Description:</label>
            <textarea id="incident-description" rows="3" placeholder="Describe details..." required></textarea>

            <input type="hidden" id="form-lat">
            <input type="hidden" id="form-lng">

            <div id="incident-map"></div>

            <button type="submit" class="btn">Submit Report</button>
        </form>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div id="toast">Report updated successfully!</div>

<div id="confirmModal" class="modal" style="display: none; z-index: 2000;">
    <div class="modal-content" style="max-width: 400px; text-align: center; padding: 25px; font-size: medium;">
        <h3 style="margin-top: 0; color: #e74c3c;">CONFIRM DELETE</h3>
        <p id="confirmMessage" style="margin-top: 0; color: black; font-size: large;">Are you sure you want to delete this report?</p>
        <div style="margin-top: 20px;">
            <button id="confirmNoBtn" class="btn" style="background: #a0cfff; color: black; font-size: large;">Cancel</button>
            <button id="confirmYesBtn" class="btn" style="background: #e74c3c; color: black; margin-right: 10px; font-size: large;">Delete</button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>
<script>
const firebaseConfig = {
    apiKey: "AIzaSyC2A2rUd1SjeEmm7qyMHFz8y1afLmQpJ_0",
    authDomain: "management-6d07b.firebaseapp.com",
    databaseURL: "https://management-6d07b-default-rtdb.firebaseio.com/", // ← NO SPACES
    projectId: "management-6d07b",
    storageBucket: "management-6d07b.appspot.com",
    messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
    appId: "YOUR_APP_ID"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.database();

document.addEventListener('DOMContentLoaded', () => {
    const totalUsersCard = document.getElementById('totalUsersCard');
    const paymentsCard = document.getElementById('payments-card');
    const accidentReportsBtn = document.getElementById('accidentReportsBtn');

    const usersModal = document.getElementById('usersModal');
    const paymentsModal = document.getElementById('payments-modal');
    const incidentModal = document.getElementById('incidentModal');

    const closeUsersModalBtn = document.getElementById('closeUsersModal');
    const closeModalBtn = document.getElementById('close-modal');
    const closeIncidentModal = document.getElementById('closeIncidentModal');

    const userCountEl = document.getElementById('userCount');
    const paymentsContent = document.getElementById('payments-content');
    const incidentTableBody = document.getElementById('incidentTableBody');
    const usersTableBody = document.querySelector('#usersTable tbody');
    const searchInput = document.getElementById('incident-search');

    const trafficModal = document.getElementById('traffic-modal');
    const openTrafficBtn = document.getElementById('open-traffic-modal');
    const closeTrafficBtn = document.getElementById('close-traffic-modal');
    const loadingEl = document.getElementById('loading');
    const resultsEl = document.getElementById('traffic-results');
    const openAnalytics = document.getElementById("open-traffic-analytics");
    const modal = document.getElementById("traffic-analytics-modal");
    const closeAnalytics = document.getElementById("close-traffic-analytics");
    let doughnutChart, trendChart;

  openAnalytics?.addEventListener("click", () => {
    modal.style.display = "block";
    loadTrafficAnalytics();
  });

  closeAnalytics?.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.onclick = (e) => {
    if (e.target === modal) modal.style.display = "none";
  };

  function loadTrafficAnalytics() {
    const refTraffic = db.ref("traffic_logs");

    refTraffic.on("value", (snapshot) => {
      const data = snapshot.val();
      if (!data) return;

      const counts = { heavy: 0, moderate: 0, normal: 0 };
      const hourly = {};
      let totalSpeed = 0, speedCount = 0;

      Object.values(data).forEach((entry) => {
        const status = entry.status || "normal";
        if (counts[status] !== undefined) counts[status]++;

        if (entry.speed) {
          totalSpeed += Number(entry.speed);
          speedCount++;
        }

        if (entry.timestamp) {
          const hour = new Date(entry.timestamp).getHours();
          hourly[hour] = (hourly[hour] || 0) + 1;
        }
      });

      const total = counts.heavy + counts.moderate + counts.normal;
      document.getElementById("totalReports").textContent = total;
      document.getElementById("heavyPercent").textContent =
        total ? Math.round((counts.heavy / total) * 100) + "%" : "0%";
      document.getElementById("avgSpeed").textContent = speedCount
        ? (totalSpeed / speedCount).toFixed(1) + " km/h"
        : "-- km/h";

      const ctx = document.getElementById("trafficChart").getContext("2d");
      if (doughnutChart) doughnutChart.destroy();

      doughnutChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: ["Heavy", "Moderate", "Normal"],
          datasets: [
            {
              label: "Traffic Distribution",
              data: [counts.heavy, counts.moderate, counts.normal],
              backgroundColor: ["#e74c3c", "#f39c12", "#2ecc71"],
            },
          ],
        },
        options: {
            maintainAspectRatio: false,
            cutout: "65%", 
            plugins: {
            legend: { position: "bottom" },
            title: { display: true, text: "Current Traffic Distribution" },
            },
        },
      });


      const hours = Object.keys(hourly).sort((a, b) => a - b);
      const values = hours.map((h) => hourly[h]);

      const ctx2 = document.getElementById("trendChart").getContext("2d");
      if (trendChart) trendChart.destroy();

      trendChart = new Chart(ctx2, {
        type: "bar",
        data: {
          labels: hours.map((h) => h + ":00"),
          datasets: [
            {
              label: "Reports per Hour",
              data: values,
              backgroundColor: "#3498db",
            },
          ],
        },
        options: {
          plugins: {
            title: {
              display: true,
              text: "Traffic Reports by Hour",
            },
          },
          scales: { y: { beginAtZero: true } },
        },
      });
    });
  }

    let allIncidentData = [];
  
    openTrafficBtn?.addEventListener('click', () => {
        trafficModal.style.display = 'flex';
        fetchTrafficData();
    });

    closeTrafficBtn?.addEventListener('click', () => {
        trafficModal.style.display = 'none';
    });

    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    };

    async function fetchTrafficData() {
        loadingEl.style.display = 'block';
        resultsEl.style.display = 'none';
        try {
            const snapshot = await db.ref('traffic_logs').limitToLast(1).once('value');
            let data = null;
            snapshot.forEach(child => { data = child.val(); });
            if (data) {
                updateTrafficStatus(data);
            } else {
                loadingEl.textContent = 'No traffic data available.';
            }
        } catch (err) {
            console.error("Firebase error:", err);
            loadingEl.textContent = 'Failed to load traffic data.';
        }
    }

    function updateTrafficStatus(data) {
        loadingEl.style.display = 'none';
        resultsEl.style.display = 'block';

        const routes = ['A', 'B', 'C', 'D'];
        routes.forEach(r => {
            const sensorKey = `sensor${r}`;
            const hasTraffic = data?.[sensorKey]?.traffic === true;
            const el = document.querySelector(`#route${r} span`);
            if (el) {
                el.textContent = hasTraffic ? 'Traffic' : 'No Traffic';
                el.className = hasTraffic ? 'traffic-yes' : 'traffic-no';
            }
        });
    }

    fetch('{{ route("admin.users.count") }}')
        .then(res => res.json())
        .then(data => {
            userCountEl.textContent = data.count || 0;
        })
        .catch(() => {
            userCountEl.textContent = 'Error';
        });


    totalUsersCard?.addEventListener('click', () => {
        usersModal.style.display = 'flex';
        loadAllUsers();
    });

    function loadAllUsers() {
        usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Loading...</td></tr>';

        fetch('{{ route("admin.users.all") }}')
            .then(res => res.json())
            .then(users => {
                usersTableBody.innerHTML = '';
                if (!users.length) {
                    usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No users found</td></tr>';
                    return;
                }
                users.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.is_admin ? 'Admin' : 'User'}</td>
                        <td>${new Date(user.created_at).toLocaleDateString()}</td>
                    `;
                    usersTableBody.appendChild(tr);
                });
            })
            .catch(() => {
                usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:red;">Failed to load users</td></tr>';
            });
    }

    paymentsCard?.addEventListener('click', () => {
        paymentsModal.style.display = 'flex';
        loadPayments();
    });

    function loadPayments() {
        fetch('{{ route('admin.payments.data') }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.length) {
                paymentsContent.innerHTML = '<p>No payments found.</p>';
                return;
            }

            let tableHtml = `
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(payment => {
                tableHtml += `
                    <tr data-id="${payment.id}">
                        <td>${payment.id}</td>
                        <td>${payment.user_name}</td>
                        <td>${payment.amount}</td>
                        <td class="status">${payment.status}</td>
                        <td>
                            ${payment.status === 'pending' 
                                ? `<button class="confirm-btn">Confirm</button>` 
                                : ''}
                        </td>
                    </tr>
                `;
            });

            tableHtml += `</tbody></table>`;
            paymentsContent.innerHTML = tableHtml;

            // Attach confirm button listeners
            document.querySelectorAll('.confirm-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const row = btn.closest('tr');
                    const paymentId = row.dataset.id;
                    btn.disabled = true;
                    btn.textContent = 'Confirming...';

                    fetch(`{{ url('admin/payments/confirm') }}/${paymentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(res => res.json())
                    .then(resData => {
                        if (resData.success) {
                            row.querySelector('.status').textContent = 'confirmed';
                            btn.remove();
                        } else {
                            alert('Failed to confirm payment.');
                            btn.disabled = false;
                            btn.textContent = 'Confirm';
                        }
                    })
                    .catch(() => {
                        alert('Error confirming payment.');
                        btn.disabled = false;
                        btn.textContent = 'Confirm';
                    });
                });
            });
        })
        .catch(() => {
            paymentsContent.innerHTML = '<p>Failed to load payments.</p>';
        });
    }
    accidentReportsBtn?.addEventListener('click', loadIncidents);

    function loadIncidents() {
        incidentTableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Loading reports...</td></tr>';
        incidentModal.style.display = 'flex';

        fetch('{{ route("incidents.fetch") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response failed');
            return response.json();
        })
        .then(data => {
            incidentTableBody.innerHTML = '';

            if (!data || !Array.isArray(data) || data.length === 0) {
                incidentTableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;">No reports available</td></tr>';
                allIncidentData = [];
                return;
            }

            allIncidentData = data;
            renderIncidents(data);

            const reportedCount = data.filter(i => i.status === 'reported').length;
            updateAlertBadge(reportedCount);
        })
        .catch(err => {
            console.error("❌ Failed to load incidents:", err);
            incidentTableBody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align:center; color:red;">
                        Error loading reports.<br><small>Check console</small>
                    </td>
                </tr>`;
        });
    }

    function renderIncidents(data) {
        incidentTableBody.innerHTML = '';

        if (!data.length) {
            incidentTableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;">No matching reports</td></tr>';
            return;
        }

        data.forEach(item => {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lng);
            const coords = !isNaN(lat) && !isNaN(lng)
                ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                : 'Not available';

            const tr = document.createElement('tr');
            tr.dataset.id = item.id;

            const statusBtn = document.createElement('button');
            statusBtn.className = 'status-toggle-btn';
            statusBtn.textContent = item.status === 'resolved' ? '✅ Resolved' : '✓ Resolve';
            statusBtn.style.background = item.status === 'resolved' ? '#17a2b8' : '#28a745';
            statusBtn.dataset.status = item.status;

            statusBtn.addEventListener('click', function () {
                const currentStatus = this.dataset.status;
                const newStatus = currentStatus === 'reported' ? 'resolved' : 'reported';
                const id = tr.dataset.id;

                this.textContent = newStatus === 'resolved' ? '✅ Resolved' : '✓ Resolve';
                this.style.background = newStatus === 'resolved' ? '#17a2b8' : '#28a745';
                this.dataset.status = newStatus;

                fetch(`{{ url('/incidents') }}/${id}/update-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        showToast(`Incident ${newStatus}!`);
                        updateAlertBadge();
                    } else {
                        throw new Error('Update failed');
                    }
                })
                .catch(err => {
                    console.error('Error updating status:', err);
                    alert('Could not update status. Reverting...');

                    const revertStatus = this.dataset.status === 'resolved' ? 'reported' : 'resolved';
                    this.textContent = revertStatus === 'resolved' ? '✅ Resolved' : '✓ Resolve';
                    this.style.background = revertStatus === 'resolved' ? '#17a2b8' : '#28a745';
                    this.dataset.status = revertStatus;
                });
            });

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'status-toggle-btn delete-btn';
            deleteBtn.textContent = '🗑️ Delete';
            deleteBtn.addEventListener('click', function () {
                const id = tr.dataset.id;

                const confirmModal = document.getElementById('confirmModal');
                const confirmYesBtn = document.getElementById('confirmYesBtn');
                const confirmNoBtn = document.getElementById('confirmNoBtn');

                confirmModal.style.display = 'flex';

                const handleYes = () => {
                    confirmYesBtn.removeEventListener('click', handleYes);
                    confirmNoBtn.removeEventListener('click', handleNo);
                    confirmModal.style.display = 'none';

                    fetch(`{{ url('/incidents') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            tr.remove();
                            showToast('Report deleted!', 'success');
                            updateAlertBadge();
                            allIncidentData = allIncidentData.filter(i => i.id != id);
                        } else {
                            throw new Error('Delete failed');
                        }
                    })
                    .catch(err => {
                        console.error('Delete error:', err);
                        showToast('Failed to delete report.', 'error');
                    });
                };

                const handleNo = () => {
                    confirmYesBtn.removeEventListener('click', handleYes);
                    confirmNoBtn.removeEventListener('click', handleNo);
                    confirmModal.style.display = 'none';
                };

                confirmYesBtn.addEventListener('click', handleYes);
                confirmNoBtn.addEventListener('click', handleNo);

                const closeOnOutsideClick = (e) => {
                    if (e.target === confirmModal) {
                        handleNo();
                        window.removeEventListener('click', closeOnOutsideClick);
                    }
                };
                window.addEventListener('click', closeOnOutsideClick);
            });

            tr.innerHTML = `
                <td>${item.title || 'N/A'}</td>
                <td>${item.description || 'N/A'}</td>
                <td><code>${coords}</code></td>
                <td style="font-size:13px; color:#555;">${item.address || 'Loading address...'}</td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
            `;

            const statusCell = document.createElement('td');
            statusCell.appendChild(statusBtn);
            tr.appendChild(statusCell);

            const deleteCell = document.createElement('td');
            deleteCell.appendChild(deleteBtn);
            tr.appendChild(deleteCell);

            incidentTableBody.appendChild(tr);

            if (lat && lng && !item.address) {
                reverseGeocode(lat, lng).then(addr => {
                    tr.cells[3].textContent = addr.length > 100 ? addr.substring(0, 100) + '...' : addr;
                    const index = allIncidentData.findIndex(i => i.id == item.id);
                    if (index !== -1) {
                        allIncidentData[index].address = addr;
                    }
                }).catch(() => {
                    tr.cells[3].textContent = "Address unavailable";
                });
            } else if (item.address) {
                tr.cells[3].textContent = item.address;
            }
        });
    }

    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = searchInput.value.toLowerCase().trim();
            if (!query) {
                renderIncidents(allIncidentData);
                return;
            }

            const filtered = allIncidentData.filter(item => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lng);
                const coords = !isNaN(lat) && !isNaN(lng)
                    ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                    : '';

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

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`,
                { mode: 'cors' }
            );
            if (!res.ok) throw new Error(`Geocode failed: ${res.status}`);
            const data = await res.json();
            return data.display_name || 'Unknown location';
        } catch (err) {
            console.error("Reverse geocode error:", err);
            return 'Address unavailable';
        }
    }

    [closeUsersModalBtn, closeModalBtn, closeIncidentModal].forEach(btn => {
        btn?.addEventListener('click', () => {
            if (btn === closeUsersModalBtn) usersModal.style.display = 'none';
            if (btn === closeModalBtn) paymentsModal.style.display = 'none';
            if (btn === closeIncidentModal) incidentModal.style.display = 'none';
        });
    });

    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    };

    window.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            ['usersModal', 'paymentsModal', 'incidentModal'].forEach(id => {
                const modal = document.getElementById(id);
                if (modal) modal.style.display = 'none';
            });
        }
    });

    function updateAlertBadge(force = null) {
        if (force !== null) {
            updateAlertCountManual(force);
            return;
        }
        fetch('{{ route("incidents.fetch") }}')
            .then(r => r.json())
            .then(data => {
                const count = Array.isArray(data) ? data.filter(i => i.status === 'reported').length : 0;
                updateAlertCountManual(count);
            })
            .catch(console.error);
    }

    function updateAlertCountManual(count) {
        const badge = document.getElementById('alert-count-badge');
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline';
        } else {
            badge.style.display = 'none';
        }
    }

    updateAlertBadge();
    setInterval(updateAlertBadge, 30000);

    window.showToast = function(message = "Action completed", type = "success") {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
        toast.style.opacity = 1;
        setTimeout(() => { toast.style.opacity = 0; }, 3000);
    }
const reportIncidentBtn = document.getElementById('reportIncidentBtn');
const reportIncidentModal = document.getElementById('reportIncidentModal');
const closeReportIncidentModal = document.getElementById('closeReportIncidentModal');
const reportIncidentForm = document.getElementById('reportIncidentForm');
let incidentMap = null;

reportIncidentBtn.addEventListener('click', () => {
  reportIncidentModal.style.display = 'flex';

  setTimeout(getUserLocationForIncident, 300);
});

closeReportIncidentModal.addEventListener('click', closeModal);
window.addEventListener('click', e => { if (e.target === reportIncidentModal) closeModal(); });
window.addEventListener('keydown', e => { if (e.key === "Escape") closeModal(); });

function closeModal() {
  reportIncidentModal.style.display = 'none';
  if (incidentMap) incidentMap.remove();
}

function initIncidentMap(lat, lng) {
  if (incidentMap) { incidentMap.off(); incidentMap.remove(); }
  
  incidentMap = L.map('incident-map').setView([lat, lng], 16);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(incidentMap);
  
  const marker = L.marker([lat, lng], { draggable: true }).addTo(incidentMap);
  marker.bindPopup("Drag to adjust location").openPopup();
  
  marker.on('dragend', e => {
    const { lat, lng } = e.target.getLatLng();
    setLatLng(lat, lng);
  });
  
  setLatLng(lat, lng);

  setTimeout(() => {
    incidentMap.invalidateSize();
  }, 400);
}

function setLatLng(lat, lng) {
  document.getElementById('form-lat').value = lat;
  document.getElementById('form-lng').value = lng;
  document.getElementById('location-status').innerHTML =
    `<strong>📍 Pinned:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
}

function getUserLocationForIncident() {
  const status = document.getElementById('location-status');
  status.textContent = "📡 Getting your location...";
  
  if (!navigator.geolocation) {
    status.innerHTML = "<span style='color:red'>❌ Geolocation not supported</span>";
    return;
  }

  navigator.geolocation.getCurrentPosition(
    pos => initIncidentMap(pos.coords.latitude, pos.coords.longitude),
    err => {
      console.error(err);
      status.innerHTML = "<span style='color:red'>❌ Unable to get location</span>";
    }
  );
}

reportIncidentForm.addEventListener('submit', async e => {
  e.preventDefault();
  
  const type = document.getElementById('incident-type').value;
  const description = document.getElementById('incident-description').value.trim();
  const lat = parseFloat(document.getElementById('form-lat').value);
  const lng = parseFloat(document.getElementById('form-lng').value);
  
  if (!type) return alert('Please select a type.');
  if (isNaN(lat) || isNaN(lng)) return alert('Invalid location.');

  try {
    const res = await fetch("{{ route('incident.store') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
      },
      body: JSON.stringify({ type, description, lat, lng })
    });
    const data = await res.json();

    reportIncidentForm.style.display = 'none';
    const box = reportIncidentModal.querySelector('.modal-content');
    box.insertAdjacentHTML('beforeend', `
      <div id="success-message" style="text-align:center;padding:20px;color:#28a745;">
        <i class="fas fa-check-circle" style="font-size:48px;margin-bottom:10px;"></i>
        <h3>Report Submitted!</h3>
        <p>${data.message || 'Thank you for reporting the incident.'}</p>
        <button id="closeSuccessBtn" class="btn" style="margin-top:10px;">Close</button>
      </div>`);
      
    document.getElementById('closeSuccessBtn').addEventListener('click', () => {
      document.getElementById('success-message').remove();
      reportIncidentForm.reset();
      reportIncidentForm.style.display = 'block';
      closeModal();
    });
  } catch (err) {
    console.error(err);
    alert('Failed to submit report.');
  }
});
});
</script>

</body>
</html>