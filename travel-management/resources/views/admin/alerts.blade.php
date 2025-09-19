<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Alerts</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/mi8+tsdM9Gmf5K+M=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

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
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: row;
            min-height: calc(100vh - 100px); 
        }

        .sidebar {
        width: 240px;
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
            min-height: 100%;
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

            
                <!--<div class="card" id="accidentReportsBtn">
                    <p>Accident Reports</p>
                    <small>User Reports</small>
                </div>-->
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

<!--<div id="incidentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Incident Reports</h3>
            <button class="close-btn" id="closeIncidentModal">&times;</button>
        </div>
       <table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Description</th>
            <th>Location (Coordinates)</th>
            <th>Full Address</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody id="incidentTableBody"></tbody>
</table>
    </div>-->
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="usersTableBody"></tbody>
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
document.addEventListener('DOMContentLoaded', () => {
    const failedAttemptsBtn = document.getElementById('failedAttemptsBtn');
    const accidentReportsBtn = document.getElementById('accidentReportsBtn');
    const manageUsersBtn = document.getElementById('manageUsersBtn');
    const newUsersBtn = document.getElementById('newUsersBtn');

    const failedAttemptsModal = document.getElementById('failedAttemptsModal');
    const incidentModal = document.getElementById('incidentModal');
    const manageUsersModal = document.getElementById('manageUsersModal');
    const newUsersModal = document.getElementById('newUsersModal');

    const closeFailedAttemptsModal = document.getElementById('closeFailedAttemptsModal');
    const closeIncidentModal = document.getElementById('closeIncidentModal');
    const closeManageUsersModal = document.getElementById('closeManageUsersModal');
    const closeNewUsersModal = document.getElementById('closeNewUsersModal');

    const incidentTableBody = document.getElementById('incidentTableBody');
    const usersTableBody = document.getElementById('usersTableBody');

    // === MODAL OPEN/CLOSE HANDLERS ===
    failedAttemptsBtn?.addEventListener('click', () => failedAttemptsModal.style.display = 'block');
    closeFailedAttemptsModal?.addEventListener('click', () => failedAttemptsModal.style.display = 'none');

    accidentReportsBtn?.addEventListener('click', loadIncidents);
    closeIncidentModal?.addEventListener('click', () => incidentModal.style.display = 'none');

    manageUsersBtn?.addEventListener('click', loadAllUsers);
    closeManageUsersModal?.addEventListener('click', () => manageUsersModal.style.display = 'none');

    newUsersBtn?.addEventListener('click', () => newUsersModal.style.display = 'block');
    closeNewUsersModal?.addEventListener('click', () => newUsersModal.style.display = 'none');

    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    };

    // === LOAD INCIDENTS ===
    function loadIncidents() {
        console.log("🔍 Loading incidents...");

        // Show loading
        incidentTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Loading...</td></tr>';

        fetch('{{ route("incidents.fetch") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("📥 Fetched incidents:", data);

            incidentTableBody.innerHTML = '';

            // Handle no data
            if (!data || !Array.isArray(data) || data.length === 0) {
                incidentTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No reports found</td></tr>';
                incidentModal.style.display = 'block';
                return;
            }

            // Process each incident
            data.forEach(item => {
                const tr = document.createElement('tr');

                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lng);
                const coords = !isNaN(lat) && !isNaN(lng)
                    ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                    : 'Not available';

                tr.innerHTML = `
                    <td>${item.title || 'N/A'}</td>
                    <td>${item.description || 'N/A'}</td>
                    <td><code>${coords}</code></td>
                    <td style="font-size:13px; color:#555;">Loading address...</td>
                    <td>${new Date(item.created_at).toLocaleString()}</td>
                `;
                incidentTableBody.appendChild(tr);

                // Only try reverse geocoding if valid coordinates
                if (!isNaN(lat) && !isNaN(lng)) {
                    reverseGeocode(lat, lng)
                        .then(address => {
                            if (tr.cells[3]) {
                                tr.cells[3].textContent = address.length > 100 
                                    ? address.substring(0, 100) + '...' 
                                    : address;
                            }
                        })
                        .catch(err => {
                            console.warn("Geocode failed:", err);
                            if (tr.cells[3]) tr.cells[3].textContent = "Address unavailable";
                        });
                } else {
                    tr.cells[3].textContent = "No location";
                }
            });

            incidentModal.style.display = 'block';
        })
        .catch(err => {
            console.error("❌ Failed to load incidents:", err);
            incidentTableBody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center; color:red;">
                        Error loading data.<br>
                        <small>Check console for details</small>
                    </td>
                </tr>`;
            incidentModal.style.display = 'block';
        });
    }

    // === LOAD ALL USERS ===
    function loadAllUsers() {
        fetch('{{ route("admin.users.all") }}')
            .then(r => r.json())
            .then(users => {
                usersTableBody.innerHTML = '';
                if (!users.length) {
                    usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No users found</td></tr>';
                    return;
                }

                users.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.dataset.id = user.id;
                    tr.innerHTML = `
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.is_admin ? 'Admin' : 'User'}</td>
                        <td>${new Date(user.created_at).toLocaleDateString()}</td>
                        <td>
                            ${user.is_admin ? '—' : `<button class="remove-user-btn" style="background:red; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Remove</button>`}
                        </td>
                    `;
                    usersTableBody.appendChild(tr);
                });

                document.querySelectorAll('.remove-user-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const userId = row.dataset.id;
                        const userName = row.cells[0].textContent;

                        if (confirm(`Are you sure you want to delete "${userName}"?`)) {
                            deleteUser(userId, row);
                        }
                    });
                });

                manageUsersModal.style.display = 'block';
            })
            .catch(err => {
                console.error('Fetch users error:', err);
                alert('Failed to load users.');
            });
    }

    // === DELETE USER ===
    function deleteUser(id, row) {
        fetch(`{{ url('/admin/users') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                row.remove();
                alert(data.message || 'User deleted.');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            alert('Network error. Could not delete user.');
        });
    }

    // === REVERSE GEOCODING UTILITY ===
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
});
</script>
</html>