<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

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
    #payments-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    #payments-modal .modal-content {
        background: white;
        border-radius: 8px;
        width: 90%;
        max-width: 800px;
        max-height: 80vh;
        overflow-y: auto;
        padding: 20px;
        position: relative;
    }

    #payments-modal .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        background: none;
        border: none;
        cursor: pointer;
        color: #333;
    }

    #payments-modal table {
        width: 100%;
        border-collapse: collapse;
    }

    #payments-modal th,
    #payments-modal td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    #payments-modal thead tr {
        background-color: #007bff;
        color: white;
    }

    #payments-modal button.confirm-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #payments-modal button.confirm-btn:disabled {
        background-color: #6c757d;
        cursor: default;
    }

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
                width: 90%;
            }

            .main-content {
                padding: 20px;
            }
        }
</style>

<div class="header">
    <h1>Admin</h1>
</div>

<div class="dashboard-container">
    <nav class="sidebar" aria-label="Sidebar Navigation">
        <ul>
            <li class="{{ request()->routeIs('homeAdmin') ? 'active' : '' }}">
                <a href="{{ route('homeAdmin') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('alerts') ? 'active' : '' }}">
                <a href="{{ route('alerts') }}">
                    <i class="fas fa-bell"></i> Alerts
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
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
            <input type="search" placeholder="Search..." class="search-bar" aria-label="Search" />
            <button class="profile-btn" aria-label="User Profile">
                <i class="fas fa-user"></i>
            </button>
        </header>

        <section class="overview" aria-labelledby="overview-heading">
            <h2 id="overview-heading">Dashboard Overview</h2>
            <div class="card-group">
                <div class="card" id="totalUsersCard" role="button" tabindex="0" aria-haspopup="dialog">
                    <p>Total Users</p>
                    <small id="userCount">Loading...</small>
                </div>
                <div class="card" id="payments-card" role="button" tabindex="0" aria-haspopup="dialog" aria-controls="payments-modal" aria-label="View Payments">
                    <p>Payments</p>
                    <small>User Payments</small>
                </div>
                <div class="card" id="accidentReportsBtn">
                    <p>Accident Reports</p>
                    <small>User Reports</small>
                </div>
            </div>
        </section>
    </main>
</div>

<div id="payments-modal" role="dialog" aria-modal="true" aria-labelledby="payments-modal-title" tabindex="-1">
    <div class="modal-content">
        <button class="close-btn" id="close-modal">&times;</button>
        <h2 id="payments-modal-title">Payments</h2>
        <div id="payments-content">
            <p>Loading payments...</p>
        </div>
    </div>
</div>

<!-- ALL USERS MODAL -->
<div id="usersModal" class="modal">
    <div class="modal-content">
        <button class="close-btn" id="closeUsersModal">&times;</button>
        <h2>All Users</h2>
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
<div id="incidentModal" class="modal">
    <div class="modal-content">
        <button class="close-btn" id="closeIncidentModal">&times;</button>
        <h2>Incident Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Coords</th>
                    <th>Address</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="incidentTableBody"></tbody>
        </table>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const paymentsCard = document.getElementById('payments-card');
    const totalUsersCard = document.getElementById('totalUsersCard');
    const usersModal = document.getElementById('usersModal');
    const paymentsModal = document.getElementById('payments-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const closeUsersModalBtn = document.getElementById('closeUsersModal');
    const paymentsContent = document.getElementById('payments-content');
    const usersTableBody = document.querySelector('#usersTable tbody');
    const userCountEl = document.getElementById('userCount');

    const accidentReportsBtn = document.getElementById('accidentReportsBtn');
    const incidentModal = document.getElementById('incidentModal');
    const closeIncidentModal = document.getElementById('closeIncidentModal');
    const incidentTableBody = document.getElementById('incidentTableBody');

    accidentReportsBtn?.addEventListener('click', loadIncidents);
    closeIncidentModal?.addEventListener('click', () => incidentModal.style.display = 'none');

    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    };

    // === LOAD INCIDENTS ===
    function loadIncidents() {
    console.log("🔍 Loading incidents...");

    incidentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Loading...</td></tr>';

    fetch('{{ route("incidents.fetch") }}', {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        console.log("📥 Fetched incidents:", data);
        incidentTableBody.innerHTML = '';

        if (!data.length) {
            incidentTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No reports found</td></tr>';
            incidentModal.style.display = 'block';
            return;
        }

        data.forEach(item => {
            const tr = document.createElement('tr');
            tr.dataset.id = item.id;

            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lng);
            const coords = !isNaN(lat) && !isNaN(lng)
                ? `${lat.toFixed(6)}, ${lng.toFixed(6)}`
                : 'Not available';

            tr.innerHTML = `
                <td>${item.title || 'N/A'}</td>
                <td>${item.description || 'N/A'}</td>
                <td><code>${coords}</code></td>
                <td style="font-size:13px; color:#555;">Loading...</td>
                <td>${new Date(item.created_at).toLocaleString()}</td>
                <td>
                <button class="remove-btn" style="
                    background:green;
                    color:white;
                    border:none;
                    padding:6px 10px;
                    border-radius:4px;
                    cursor:pointer;
                    font-size:13px;
                    font-weight:bold;">
                    ✓ Resolve
                </button>
                </td>
            `;
            incidentTableBody.appendChild(tr);

            // Reverse geocode
            if (lat && lng) {
                reverseGeocode(lat, lng).then(addr => {
                    if (tr.cells[3]) tr.cells[3].textContent = addr.length > 100 ? addr.substring(0, 100) + '...' : addr;
                });
            } else {
                tr.cells[3].textContent = "No location";
            }
        });

        document.querySelectorAll('.checked-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const row = this.closest('tr');
        const id = row.dataset.id;

        // Visual feedback
        if (this.textContent.trim() === '✓ Checked') {
            this.textContent = '✔️ Resolved';
            this.style.background = '#17a2b8';
            this.disabled = true;
        }
    });
});
        // Attach remove handlers
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                const id = row.dataset.id;
                const type = row.cells[0].textContent;

                if (confirm(`Are you sure you this was reported? "${type}" report?`)) {
                    deleteIncident(id, row);
                }
            });
        });

        incidentModal.style.display = 'block';
    })
    .catch(err => {
        console.error("❌ Failed to load incidents:", err);
        incidentTableBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center; color:red;">
                    Error loading data.<br>
                    <small>Check console</small>
                </td>
            </tr>`;
        incidentModal.style.display = 'block';
    });
}

// === DELETE INCIDENT ===
function deleteIncident(id, row) {
    fetch(`{{ url('/incidents') }}/${id}`, {
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
            alert(data.message || 'Report removed.');
        } else {
            alert('Failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Delete error:', err);
        alert('Network error. Could not remove report.');
    });
}

    // === LOAD USER COUNT ===
    fetch('{{ route("admin.users.count") }}')
        .then(res => res.json())
        .then(data => {
            userCountEl.textContent = data.count;
        })
        .catch(err => {
            console.error('Failed to load user count:', err);
            userCountEl.textContent = 'Error';
        });

    // === OPEN USERS MODAL ===
    totalUsersCard.addEventListener('click', () => {
        usersModal.style.display = 'flex';
        loadAllUsers();
    });

    totalUsersCard.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            totalUsersCard.click();
        }
    });

    function loadAllUsers() {
        fetch('{{ route("admin.users.all") }}')
            .then(res => res.json())
            .then(users => {
                usersTableBody.innerHTML = '';
                if (users.length === 0) {
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

    // === CLOSE MODALS ===
    closeUsersModalBtn.addEventListener('click', () => {
        usersModal.style.display = 'none';
    });

    closeModalBtn.addEventListener('click', () => {
        paymentsModal.style.display = 'none';
        paymentsContent.innerHTML = '<p>Loading payments...</p>';
    });

    window.addEventListener('click', e => {
        if (e.target === usersModal) usersModal.style.display = 'none';
        if (e.target === paymentsModal) paymentsModal.style.display = 'none';
    });

    window.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (usersModal.style.display === 'flex') usersModal.style.display = 'none';
            if (paymentsModal.style.display === 'flex') paymentsModal.style.display = 'none';
        }
    });

    // === PAYMENTS MODAL (existing logic) ===
    paymentsCard.addEventListener('click', () => {
        paymentsModal.style.display = 'flex';
        loadPayments();
    });

    paymentsCard.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            paymentsCard.click();
        }
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