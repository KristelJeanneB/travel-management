<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Alerts</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Leaflet CSS (for maps if needed later) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/mi8+tsdM9Gmf5K+M=" crossorigin="" />

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
.analytics-section {
    margin-top: 40px;
    padding: 0 20px;
}

.analytics-section h2 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 15px;
    color: #2c3e50;
}

.analytics-section .last-updated {
    display: block;
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    font-size: 14px;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 20px;
}

.chart-card {
    background: #fff;
    padding: 20px 25px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    height: 400px; /* fixed height so chart stretches */
    display: flex;
    flex-direction: column;
}

.chart-card canvas {
    flex: 1; /* canvas fills the card */
}


/* Responsive for smaller screens */
@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .chart-card {
        padding: 20px;
    }

    .chart-card h3 {
        font-size: 20px;
    }

    .chart-card canvas {
        height: 250px !important;
    }
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

        /* Cards Group */
        .card-group {
            display: flex;
            gap: 28px;
            justify-content: center;
            flex-wrap: wrap;
            padding: 10px 0;
        }

        .card {
            background: white;
            border: 1px solid #e0e6ed;
            border-radius: 14px;
            padding: 26px 20px;
            width: 270px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.25, 0.7, 0.25, 1);
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
            border-color: #a0cfff;
        }

        .card i.icon {
            font-size: 28px;
            margin-bottom: 12px;
            display: block;
        }

        .card p {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .card small {
            display: block;
            font-size: 14px;
            color: #6b7a89;
            line-height: 1.4;
        }

        /* Icons Color Coding */
        .card#failedAttemptsBtn i.icon { color: #e74c3c; }
        .card#newUsersBtn i.icon { color: #27ae60; }
        .card#manageUsersBtn i.icon { color: #3498db; }

        /* Modal Base */
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
            margin: 6% auto;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 850px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
            animation: fadeIn 0.3s ease-out;
            position: relative;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.94); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #eee;
            padding-bottom: 12px;
        }

        .modal-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 22px;
            font-weight: 600;
        }

        .close-btn {
            font-size: 28px;
            background: none;
            border: none;
            cursor: pointer;
            color: #888;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            color: #e74c3c;
            transform: rotate(90deg);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            margin-top: 10px;
        }

        table thead tr {
            background-color: #2c3e50;
            color: white;
        }

        table th, table td {
            padding: 14px 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tbody tr:hover {
            background-color: #f1f7ff;
            transition: background 0.2s;
        }

        /* User List in New Users Modal */
        .user-list-modal ul {
            list-style: none;
            padding: 0;
        }

        .user-list-modal li {
            padding: 14px 16px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .user-list-modal li:hover {
            background-color: #f1f7ff;
            border-radius: 8px;
        }

        .user-list-modal strong {
            color: #2c3e50;
        }

        .user-list-modal small {
            color: #6b7a89;
            font-size: 13px;
        }

        /* Badge & Status Indicators */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }

        .status-danger {
            background: #e74c3c;
        }

        .status-success {
            background: #27ae60;
        }

        .ip-tag {
            font-family: monospace;
            background: #f1f1f1;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
            color: #555;
        }

        /* Toast Notification */
        #toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 14px 22px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            font-size: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 15px 0;
            }

            .main-content {
                padding: 25px;
            }

            .card {
                width: 90%;
                padding: 22px 18px;
            }

            .card p {
                font-size: 17px;
            }

            .card small {
                font-size: 13px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .close-btn {
                font-size: 24px;
            }

            table th, table td {
                padding: 10px 8px;
                font-size: 14px;
            }

            .user-list-modal li {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Admin Panel</h1>
</div>

<div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
        <ul>
            <li>
                <a href="{{ route('homeAdmin') }}"><i class="fas fa-home"></i> Dashboard</a>
            </li>
             <li class="active">
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
                <a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Settings</a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
            </li>
        </ul>
    </nav>

    <main class="main-content">
        <header>
             <!--<input type="search" placeholder="Search..." class="search-bar" aria-label="Search" />-->
            <button class="profile-btn" aria-label="User Profile">
                <i class="fas fa-user"></i>
            </button>
        </header>

        <section class="overview">
            <h2>Security & User Alerts</h2>
            <div class="card-group">

                <!-- Failed Login Attempts -->
                <div class="card" id="failedAttemptsBtn">
                    <i class="fas fa-times-circle icon"></i>
                    <p>Failed Login Attempts</p>
                    <small>Suspicious login activity detected</small>
                </div>

                <!-- New Users -->
                <div class="card" id="newUsersBtn">
                    <i class="fas fa-user-plus icon"></i>
                    <p>New Users</p>
                    <small>Recently registered accounts</small>
                </div>

                <!-- Manage All Users -->
                <div class="card" id="manageUsersBtn">
                    <i class="fas fa-users-cog icon"></i>
                    <p>Manage All Users</p>
                    <small>View and manage users</small>
                </div>

            </div>
        </section>
   <section class="analytics-section">
    <h2>Alert & User Analytics</h2>
    <small id="lastUpdated" class="last-updated">Last updated: just now</small>
    <div class="charts-grid">
        <div class="chart-card">
            <h3>Failed Login Attempts</h3>
            <canvas id="loginAttemptsChart"></canvas>
        </div>
        <div class="chart-card">
            <h3>New Users</h3>
            <canvas id="newUsersChart"></canvas>
        </div>
    </div>
</section>
    </main>
</div>

<div id="failedAttemptsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color:#e74c3c;"></i> Failed Login Attempts</h3>
            <button class="close-btn">&times;</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>Attempts</th>
                    <th>Last Attempt</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="failedAttemptsBody">
                @if(isset($failedAttempts) && $failedAttempts->count())
                    @foreach($failedAttempts as $attempt)
                        <tr>
                            <td>{{ $attempt->email ?? 'Unknown' }}</td>
                            <td><span class="ip-tag">{{ $attempt->ip_address ?? 'N/A' }}</span></td>
                            <td><strong>{{ $attempt->attempts }}</strong></td>
                            <td>{{ $attempt->created_at->diffForHumans() }}</td>
                            <td><span class="status-badge status-danger">Blocked</span></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align:center; color:#777;">No recent failed attempts.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<div id="manageUsersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-users"></i> Manage All Users</h3>
            <button class="close-btn">&times;</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
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
            <h3><i class="fas fa-user-check" style="color:#27ae60;"></i> Newly Registered Users</h3>
            <button class="close-btn">&times;</button>
        </div>
        <div class="user-list-modal">
            @if(isset($newUsers) && $newUsers->count())
                <ul>
                    @foreach($newUsers as $user)
                        <li>
                            <strong>{{ $user->name }}</strong>
                            <small>{{ $user->email }} — Joined {{ $user->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="color:#777; text-align:center; padding:20px;">No new users recently.</p>
            @endif
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div id="toast">Action completed!</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const failedAttemptsBtn = document.getElementById('failedAttemptsBtn');
    const newUsersBtn = document.getElementById('newUsersBtn');
    const manageUsersBtn = document.getElementById('manageUsersBtn');

    const failedAttemptsModal = document.getElementById('failedAttemptsModal');
    const newUsersModal = document.getElementById('newUsersModal');
    const manageUsersModal = document.getElementById('manageUsersModal');

    const closeFailedAttemptsModal = failedAttemptsModal.querySelector('.close-btn');
    const closeNewUsersModal = newUsersModal.querySelector('.close-btn');
    const closeManageUsersModal = manageUsersModal.querySelector('.close-btn');

    const failedAttemptsBody = document.getElementById('failedAttemptsBody');
    const usersTableBody = document.getElementById('usersTableBody');

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

const failedLoginsData = @json($failedAttempts ?? []);
const loginCtx = document.getElementById('loginAttemptsChart').getContext('2d');

if (failedLoginsData.length > 0) {
    const dailyAttempts = {};
    failedLoginsData.forEach(f => {
        const day = formatDate(f.created_at);
        dailyAttempts[day] = (dailyAttempts[day] || 0) + (f.attempts || 1);
    });

    const loginLabels = Object.keys(dailyAttempts);
    const loginValues = Object.values(dailyAttempts);

    new Chart(loginCtx, {
        type: 'bar',
        data: {
            labels: loginLabels,
            datasets: [{
                label: 'Failed Login Attempts',
                data: loginValues,
                backgroundColor: '#e74c3c',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    callbacks: {
                        label: (ctx) => `${ctx.parsed.y} attempt(s)`
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, precision: 0 }
                },
                x: { grid: { display: false } }
            }
        }
    });
} else {
    new Chart(loginCtx, {
        type: 'bar',
        data: { labels: ['No Data'], datasets: [{ data: [0], backgroundColor: '#ccc' }] },
        options: { 
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { display: false } }
        }
    });
}


const newUsersData = @json($newUsers ?? []);
const usersCtx = document.getElementById('newUsersChart').getContext('2d');

if (newUsersData.length > 0) {
    const dailyCounts = {};
    newUsersData.forEach(u => {
        const day = formatDate(u.created_at);
        dailyCounts[day] = (dailyCounts[day] || 0) + 1;
    });

    const userLabels = Object.keys(dailyCounts);
    const userValues = Object.values(dailyCounts);

    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'New Users',
                data: userValues,
                borderColor: '#27ae60',
                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointBackgroundColor: '#27ae60'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.parsed.y} new user(s)`
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
} else {
    new Chart(usersCtx, {
        type: 'line',
        data: { labels: ['No Data'], datasets: [{ data: [0], borderColor: '#ccc' }] },
        options: { 
            plugins: { legend: { display: false } },
            scales: { y: { display: false }, x: { display: false } }
        }
    });
}

document.getElementById('lastUpdated').textContent = 'Last updated: ' + new Date().toLocaleTimeString();

    document.getElementById('lastUpdated').textContent = 'Last updated: ' + new Date().toLocaleTimeString();
    failedAttemptsBtn?.addEventListener('click', () => failedAttemptsModal.style.display = 'flex');
    newUsersBtn?.addEventListener('click', () => newUsersModal.style.display = 'flex');
    manageUsersBtn?.addEventListener('click', loadAllUsers);

    [closeFailedAttemptsModal, closeNewUsersModal, closeManageUsersModal].forEach(btn => {
        btn?.addEventListener('click', () => {
            btn.closest('.modal').style.display = 'none';
        });
    });

    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    };

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }
    });

    function loadAllUsers() {
        manageUsersModal.style.display = 'flex';
        usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;">Loading...</td></tr>';

        fetch('{{ route("admin.users.all") }}')
            .then(r => r.json())
            .then(users => {
                usersTableBody.innerHTML = '';
                if (!users.length) {
                    usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#777;">No users found</td></tr>';
                    return;
                }

                users.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.dataset.id = user.id;
                    tr.innerHTML = `
                        <td><strong>${user.name}</strong></td>
                        <td>${user.email}</td>
                        <td>
                            <span class="status-badge ${user.is_admin ? 'status-success' : 'status-warning'}">
                                ${user.is_admin ? 'Admin' : 'User'}
                            </span>
                        </td>
                        <td>${new Date(user.created_at).toLocaleDateString()}</td>
                        <td>
                            ${user.is_admin 
                                ? '—' 
                                : `<button class="remove-user-btn" style="
                                    background:#e74c3c; 
                                    color:white; 
                                    border:none; 
                                    padding:6px 10px; 
                                    border-radius:6px; 
                                    cursor:pointer;
                                    font-size:14px;
                                ">Remove</button>`}
                        </td>
                    `;
                    usersTableBody.appendChild(tr);
                });

                document.querySelectorAll('.remove-user-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const row = this.closest('tr');
                        const userId = row.dataset.id;
                        const userName = row.querySelector('strong').textContent;

                        if (confirm(`⚠️ Are you sure you want to delete "${userName}"? This cannot be undone.`)) {
                            deleteUser(userId, row);
                        }
                    });
                });
            })
            .catch(() => {
                usersTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:red;">Failed to load users.</td></tr>';
            });
    }

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
                showToast('User deleted successfully.');
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Delete error:', err);
            alert('Network error. Could not delete user.');
        });
    }

    window.showToast = function(message = "Action completed", type = "success") {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
        toast.style.opacity = 1;

        setTimeout(() => {
            toast.style.opacity = 0;
        }, 3000);
    };
});
</script>

</body>
</html>