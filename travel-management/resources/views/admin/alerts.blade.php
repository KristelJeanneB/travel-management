@extends('layouts.app')

@section('title', 'Alerts')

@section('content')
<style>
    /* 🔒 Preserved your full original CSS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow: hidden;
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
        background-position: center;
        filter: blur(5px);
        z-index: -1;
    }

    .dashboard-container {
        position: relative;
        width: 90%;
        max-width: 1000px;
        height: 80vh;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: row;
    }

    .sidebar {
        width: 240px;
        background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 20px 0;
        height: 100%;
    }

    .sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar li {
        padding: 14px 20px;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.3s ease, padding-left 0.2s ease;
    }

    .sidebar li:hover {
        background: rgba(255, 255, 255, 0.2);
        padding-left: 25px;
    }

    .sidebar li.active {
        background: rgba(255, 255, 255, 0.2);
        border-left: 3px solid white;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        display: block;
        font-weight: 500;
    }

    .sidebar i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
    }

    .main-content {
        flex: 1;
        padding: 25px;
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
    }

    header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 12px;
    }

    input[type="text"] {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    button {
        background: #007bff;
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    button:hover {
        background: #0056b3;
    }

    .overview h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
        font-size: 22px;
        font-weight: 600;
    }

    .card-group {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .card {
        background-color: #ffffff;
        padding: 22px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        width: 220px;
        text-align: center;
        font-weight: 600;
        color: #444;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-top: 4px solid #007bff;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .card p {
        margin-bottom: 8px;
        font-size: 16px;
    }

    .card small {
        display: block;
        font-size: 12px;
        color: #777;
        margin-top: 6px;
    }

    .card a {
        display: inline-block;
        margin-top: 10px;
        color: #007bff;
        text-decoration: none;
        font-size: 14px;
    }

    .card a:hover {
        text-decoration: underline;
    }

    .card button {
        margin-top: 12px;
        padding: 10px 36px;
        border-radius: 20px;
        font-weight: 600;
        border: none;
        background-color: #f28b82;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(242, 139, 130, 0.4);
        transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        font-size: 15px;
        display: inline-block;
        text-align: center;
        user-select: none;
        min-width: 140px;
    }

    .card button:hover {
        background-color: #d1605f;
        box-shadow: 0 6px 16px rgba(209, 96, 95, 0.6);
        transform: translateY(-2px);
    }

    .card button:active {
        transform: translateY(0);
        box-shadow: 0 3px 6px rgba(209, 96, 95, 0.4);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background: white;
        margin: 8% auto;
        padding: 25px;
        border-radius: 12px;
        width: 90%;
        max-width: 700px;
        max-height: 70vh;
        overflow-y: auto;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        font-size: 15px;
        color: #333;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
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
        transition: color 0.3s ease;
    }

    .close-btn:hover {
        color: #007bff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    table thead tr {
        background-color: #007bff;
        color: white;
    }

    table th, table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            flex-direction: column;
            height: auto;
        }

        .sidebar {
            width: 100%;
            padding: 10px 0;
        }

        .sidebar li {
            padding: 12px 15px;
            font-size: 16px;
        }

        .card-group {
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 90%;
        }

        .main-content {
            padding: 20px;
        }
    }
</style>

<div class="background-image"></div>

<div class="dashboard-container">
    <div class="sidebar">
        <ul>
            <li class="{{ request()->routeIs('homeAdmin') ? 'active' : '' }}">
                <a href="{{ route('homeAdmin') }}"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li class="{{ request()->routeIs('view') ? 'active' : '' }}">
                <a href="{{ route('view') }}"><i class="fas fa-map-marked-alt"></i> Map View</a>
            </li>
            <li class="{{ request()->routeIs('alerts') ? 'active' : '' }}">
                <a href="{{ route('alerts') }}"><i class="fas fa-bell"></i> Alerts</a>
            </li>
            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Settings</a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <input type="text" placeholder="Search..." />
            <button><i class="fas fa-user"></i></button>
        </header>

        <section class="overview">
            <h2>Alerts</h2>
            <div class="card-group">
                <div class="card">
                    <p>Urgent Server Down!</p>
                    <small>Main server not working</small>
                    <a href="#">See info</a>
                </div>
                <div class="card">
                    <p>Failed Login Attempts</p>
                    <small>Someone tried to log in many times</small>
                    <a href="#">Check now</a>
                </div>
                <div class="card">
                    <p>Important: New User</p>
                    <small>text</small>
                </div>
                <div class="card">
                    <p>Accident Reports</p>
                    <small>User Reports</small>
                    <button id="openIncidentsBtn">View Reports</button>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div id="incidentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Incident Reports</h3>
            <button class="close-btn" id="closeModalBtn">&times;</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="incidentTableBody">
                <!-- Data injected via JS -->
            </tbody>
        </table>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    document.getElementById('openIncidentsBtn').addEventListener('click', function () {
        fetch('{{ route("incidents.fetch") }}')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('incidentTableBody');
                tbody.innerHTML = '';
                data.forEach(item => {
                    const tr = document.createElement('tr');

                    const titleTd = document.createElement('td');
                    titleTd.textContent = item.title || 'N/A';
                    tr.appendChild(titleTd);

                    const descTd = document.createElement('td');
                    descTd.textContent = item.description || 'N/A';
                    tr.appendChild(descTd);

                    const dateTd = document.createElement('td');
                    const date = new Date(item.created_at);
                    dateTd.textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                    tr.appendChild(dateTd);

                    tbody.appendChild(tr);
                });

                document.getElementById('incidentModal').style.display = 'block';
            })
            .catch(err => {
                alert('Failed to load incident reports.');
                console.error(err);
            });
    });

    document.getElementById('closeModalBtn').addEventListener('click', () => {
        document.getElementById('incidentModal').style.display = 'none';
    });

    window.onclick = function (event) {
        const modal = document.getElementById('incidentModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection
