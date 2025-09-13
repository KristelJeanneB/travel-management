@extends('layouts.app')

@section('title', 'Alerts')

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
        line-height: 1.6;
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
        margin: 20px auto;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: row;
    }

    .sidebar {
        width: 240px;
        background: #86A8CF;
        color: white;
        padding: 20px 0;
        height: 100%;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li {
        padding: 14px 20px;
        cursor: pointer;
        font-size: 15px;
        transition: background 0.3s ease;
    }

    .sidebar li:hover,
    .sidebar li.active {
        background: rgba(255, 255, 255, 0.2);
        padding-left: 25px;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        display: block;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }


    .main-content {
        flex: 1;
        padding: 30px;
        background: white;
        min-height: calc(100vh - 120px);
    }


    header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 12px;
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
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .profile-btn {
        background: #86A8CF;
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

    .profile-btn:hover {
        background: #6a8cb3;
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
        border-top: 4px solid #86A8CF;
        user-select: none;
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
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
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
</style>

<div class="header">
    <h1>Admin</h1>
</div>

<div class="dashboard-container">

    <div class="sidebar">
        <ul>
            <li class="{{ request()->routeIs('homeAdmin') ? 'active' : '' }}">
                <a href="{{ route('homeAdmin') }}"><i class="fas fa-home"></i> Dashboard</a>
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
            <input type="text" placeholder="Search..." class="search-bar" />
            <button class="profile-btn" aria-label="User Profile">
                <i class="fas fa-user"></i>
            </button>
        </header>

        <section class="overview">
            <h2>Alerts</h2>
            <div class="card-group">
            
                <div class="card" id="failedAttemptsBtn">
                    <p>Failed Login Attempts</p>
                    <small>Someone tried to log in many times</small>
                </div>

            
                <div class="card" id="manageUsersBtn">
                    <p>Manage All Users</p>
                    <small>View and manage all registered users</small>
                </div>

            
                <div class="card" id="newUsersBtn">
                    <p>New Users</p>
                    <small>Recently registered accounts</small>
                </div>

            
                <div class="card" id="accidentReportsBtn">
                    <p>Accident Reports</p>
                    <small>User Reports</small>
                </div>
            </div>
        </section>
    </div>
</div>

<div id="failedAttemptsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Failed Login Attempts Details</h3>
            <button class="close-btn" id="closeFailedAttemptsModal">&times;</button>
        </div>
        @if($failedAttempts->count())
            <ul style="list-style:none; padding-left: 0;">
                @foreach($failedAttempts as $attempt)
                    <li style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                        <strong>Email:</strong> {{ $attempt->email ?? 'Unknown' }}<br>
                        <strong>IP Address:</strong> {{ $attempt->ip_address ?? 'N/A' }}<br>
                        <strong>Attempted:</strong> {{ $attempt->created_at->diffForHumans() }}
                    </li>
                @endforeach
            </ul>
        @else
            <p>No failed login attempts recorded.</p>
        @endif
    </div>
</div>

<div id="incidentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Incident Reports</h3>
            <button class="close-btn" id="closeIncidentModal">&times;</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="incidentTableBody"></tbody>
        </table>
    </div>
</div>

<div id="manageUsersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Manage All Users</h3>
            <button class="close-btn" id="closeManageUsersModal">&times;</button>
        </div>
        <table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Registered</th>
        </tr>
    </thead>
    <tbody id="usersTableBody">
    @if(isset($allUsers) && $allUsers->isNotEmpty())
        @foreach($allUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4" style="text-align:center; color:#777;">No users found</td>
        </tr>
    @endif
</tbody>
</table>
    </div>
</div>

<div id="newUsersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Newly Registered Users</h3>
            <button class="close-btn" id="closeNewUsersModal">&times;</button>
        </div>
        <div class="user-list-modal">
            @if($newUsers->count())
                <ul>
                    @foreach($newUsers as $user)
                        <li>
                            <strong>{{ $user->name }}</strong><br>
                            <small>{{ $user->email }} — {{ $user->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No new users recently.</p>
            @endif
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    document.getElementById('failedAttemptsBtn').addEventListener('click', () => {
        document.getElementById('failedAttemptsModal').style.display = 'block';
    });

    document.getElementById('closeFailedAttemptsModal').onclick = () => {
        document.getElementById('failedAttemptsModal').style.display = 'none';
    };

    document.getElementById('accidentReportsBtn').addEventListener('click', () => {
        fetch('{{ route("incidents.fetch") }}')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('incidentTableBody');
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">No reports found</td></tr>';
                } else {
                    data.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${item.title || 'N/A'}</td>
                            <td>${item.description || 'N/A'}</td>
                            <td>${new Date(item.created_at).toLocaleString()}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
                document.getElementById('incidentModal').style.display = 'block';
            })
            .catch(error => {
                alert('Failed to load incident reports.');
                console.error(error);
            });
    });

    document.getElementById('closeIncidentModal').onclick = () => {
        document.getElementById('incidentModal').style.display = 'none';
    };

    document.getElementById('manageUsersBtn').addEventListener('click', () => {
        document.getElementById('manageUsersModal').style.display = 'block';
    });

    document.getElementById('closeManageUsersModal').onclick = () => {
        document.getElementById('manageUsersModal').style.display = 'none';
    };

    document.getElementById('newUsersBtn').addEventListener('click', () => {
        document.getElementById('newUsersModal').style.display = 'block';
    });

    document.getElementById('closeNewUsersModal').onclick = () => {
        document.getElementById('newUsersModal').style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };
</script>
@endsection