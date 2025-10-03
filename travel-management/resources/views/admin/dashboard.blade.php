<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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
            transition: all 0.3s ease;
        }

        .sidebar li:hover,
        .sidebar li.active {
            background: rgba(255, 255, 255, 0.2);
            padding-left: 25px;
            font-weight: 600;
        }

        .sidebar li.active {
            background: rgba(255, 255, 255, 0.3);
            border-left: 3px solid white;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
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
    gap: 24px; /* Just enough space without feeling sparse */
    justify-content: center;
    flex-wrap: wrap;
    padding: 10px 0;
}

.card {
    background: #ffffff;
    border: 1px solid #dfe6eb; /* Soft blue-gray border */
    border-radius: 12px;
    padding: 24px 20px;
    width: 250px; /* Medium size – not too small, not overwhelming */
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06); /* Softer shadow */
    transition: 
        transform 0.3s cubic-bezier(0.25, 0.7, 0.25, 1),
        box-shadow 0.3s ease,
        border-color 0.3s ease;
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
    </style>
</head>

<body>

<div class="header">
    <h1>Admin</h1>
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
            <!--<input type="search" placeholder="Search..." class="search-bar" aria-label="Search" />-->
            <button class="profile-btn" aria-label="User Profile">
                <i class="fas fa-user"></i>
            </button>
        </header>

        <section class="overview" aria-labelledby="overview-heading">
            <h2 id="overview-heading">Dashboard Overview</h2>
            <div class="card-group">
                <div class="card" id="totalUsersCard" role="button" tabindex="0">
                    <p>Total Users</p>
                    <small id="userCount">Loading...</small>
                </div>
                <div class="card" id="payments-card" role="button" tabindex="0">
                    <p>Payments</p>
                    <small>User Payments</small>
                </div>
                <div class="card" id="accidentReportsBtn" role="button" tabindex="0">
                    <p>Accident Reports</p>
                    <small>User Reports</small>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- PAYMENTS MODAL -->
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

<!-- ALL USERS MODAL -->
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

<!-- INCIDENT REPORTS MODAL -->
<div id="incidentModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="incident-modal-title">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="incident-modal-title">Incident Reports</h3>
            <button class="close-btn" id="closeIncidentModal">&times;</button>
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
                </tr>
            </thead>
            <tbody id="incidentTableBody"></tbody>
        </table>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Toast Notification -->
<div id="toast">Report updated successfully!</div>

<script>
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

    // === LOAD USER COUNT ===
    fetch('{{ route("admin.users.count") }}')
        .then(res => res.json())
        .then(data => {
            userCountEl.textContent = data.count || 0;
        })
        .catch(() => {
            userCountEl.textContent = 'Error';
        });

    // === OPEN USERS MODAL ===
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

    // === OPEN PAYMENTS MODAL ===
    paymentsCard?.addEventListener('click', () => {
        paymentsModal.style.display = 'flex';
        loadPayments();
    });

    function loadPayments() {
        paymentsContent.innerHTML = '<p>Loading payments...</p>';

        fetch('{{ route('admin.payments.data') }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data || !Array.isArray(data) || data.length === 0) {
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
                const statusClass = payment.status === 'confirmed' ? 'style="color:green;font-weight:bold;"' : '';
                tableHtml += `
                    <tr data-id="${payment.id}">
                        <td>${payment.id}</td>
                        <td>${payment.user_name}</td>
                        <td>$${parseFloat(payment.amount).toFixed(2)}</td>
                        <td ${statusClass}>${payment.status}</td>
                        <td>
                            ${payment.status === 'pending' 
                                ? `<button class="confirm-btn">Confirm</button>` 
                                : '<small>Completed</small>'}
                        </td>
                    </tr>
                `;
            });

            tableHtml += `</tbody></table>`;
            paymentsContent.innerHTML = tableHtml;

            // Confirm Payment Handler
            document.querySelectorAll('.confirm-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const row = btn.closest('tr');
                    const paymentId = row.dataset.id;
                    btn.disabled = true;
                    btn.textContent = 'Confirming...';

                    fetch(`{{ url('admin/payments/confirm') }}/${paymentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            row.querySelector('.status').textContent = 'confirmed';
                            row.querySelector('.status').style.color = 'green';
                            row.querySelector('.status').style.fontWeight = 'bold';
                            btn.remove();
                            showToast('Payment confirmed!');
                        } else {
                            alert('Failed to confirm payment.');
                            btn.disabled = false;
                            btn.textContent = 'Confirm';
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Network error. Try again.');
                        btn.disabled = false;
                        btn.textContent = 'Confirm';
                    });
                });
            });
        })
        .catch(err => {
            console.error('Fetch error:', err);
            paymentsContent.innerHTML = '<p>Failed to load payments. Please try again.</p>';
        });
    }

    // === INCIDENT REPORTS ===
    accidentReportsBtn?.addEventListener('click', loadIncidents);

    function loadIncidents() {
        incidentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Loading reports...</td></tr>';
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
                incidentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No reports available</td></tr>';
                return;
            }

            const reportedCount = data.filter(i => i.status === 'reported').length;
            updateAlertBadge(reportedCount);

            data.forEach(item => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lng);
                const coords = !isNaN(lat) && !isNaN(lng)
                    ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                    : 'Not available';

                const tr = document.createElement('tr');
                tr.dataset.id = item.id;

                const isResolved = item.status === 'resolved';
                const btnText = isResolved ? '✅ Resolved' : '✓ Resolve';
                const button = document.createElement('button');
                button.className = 'status-toggle-btn';
                button.textContent = btnText;
                button.dataset.status = item.status;
                button.style.background = isResolved ? '#17a2b8' : '#28a745';
                button.disabled = false;

                button.addEventListener('click', function () {
                    const currentStatus = this.dataset.status;
                    const newStatus = currentStatus === 'reported' ? 'resolved' : 'reported';
                    const id = tr.dataset.id;

                    // Optimistic UI Update
                    if (newStatus === 'resolved') {
                        this.textContent = '✅ Resolved';
                        this.style.background = '#17a2b8';
                        this.dataset.status = 'resolved';
                    } else {
                        this.textContent = '✓ Resolve';
                        this.style.background = '#28a745';
                        this.dataset.status = 'reported';
                    }

                    // Send to server
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
                            updateAlertBadge(); // Refresh count
                        } else {
                            throw new Error('Update failed');
                        }
                    })
                    .catch(err => {
                        console.error('Error updating status:', err);
                        alert('Could not update status. Reverting...');
                        // Rollback UI
                        const revertStatus = this.dataset.status === 'resolved' ? 'reported' : 'resolved';
                        const wasResolved = revertStatus === 'resolved';
                        this.textContent = wasResolved ? '✅ Resolved' : '✓ Resolve';
                        this.style.background = wasResolved ? '#17a2b8' : '#28a745';
                        this.dataset.status = revertStatus;
                    });
                });

                const tdButton = document.createElement('td');
                tdButton.appendChild(button);

                tr.innerHTML = `
                    <td>${item.title || 'N/A'}</td>
                    <td>${item.description || 'N/A'}</td>
                    <td><code>${coords}</code></td>
                    <td style="font-size:13px; color:#555;">Loading address...</td>
                    <td>${new Date(item.created_at).toLocaleString()}</td>
                `;
                tr.appendChild(tdButton);
                incidentTableBody.appendChild(tr);

                if (lat && lng) {
                    reverseGeocode(lat, lng).then(addr => {
                        tr.cells[3].textContent = addr.length > 100 ? addr.substring(0, 100) + '...' : addr;
                    }).catch(() => {
                        tr.cells[3].textContent = "Address unavailable";
                    });
                }
            });
        })
        .catch(err => {
            console.error("❌ Failed to load incidents:", err);
            incidentTableBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align:center; color:red;">
                        Error loading reports.<br><small>Check console</small>
                    </td>
                </tr>`;
        });
    }

    // Reverse Geocoding Helper
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

    // === MODAL CLOSE HANDLERS ===
    [closeUsersModalBtn, closeModalBtn, closeIncidentModal].forEach(btn => {
        btn?.addEventListener('click', () => {
            if (btn === closeUsersModalBtn) usersModal.style.display = 'none';
            if (btn === closeModalBtn) paymentsModal.style.display = 'none';
            if (btn === closeIncidentModal) incidentModal.style.display = 'none';
        });
    });

    // Close modal when clicking overlay
    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    };

    // Escape key closes any open modal
    window.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            ['usersModal', 'paymentsModal', 'incidentModal'].forEach(id => {
                const modal = document.getElementById(id);
                if (modal) modal.style.display = 'none';
            });
        }
    });

    // === ALERT BADGE UPDATER ===
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

    // Initial load
    updateAlertBadge();

    // Auto-refresh every 30 seconds
    setInterval(updateAlertBadge, 30000);

    // === TOAST NOTIFICATION ===
    window.showToast = function(message = "Action completed", type = "success") {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
        toast.style.opacity = 1;

        setTimeout(() => {
            toast.style.opacity = 0;
        }, 3000);
    }
});
</script>

</body>
</html>