@extends('layouts.app')

@section('title', 'Home Admin')

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

    /* Main Content */
    .main-content {
        flex: 1;
        padding: 30px;
        background: white;
        min-height: calc(100vh - 120px);
    }

    /* Search Header */
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

    /* Overview Cards */
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

    /* Modal */
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
                <div class="card" role="article" tabindex="0">
                    <p>Data summary</p>
                </div>
                <div class="card" role="article" tabindex="0">
                    <p>Traffic Overview</p>
                    <small>An overview of website traffic</small>
                </div>
                <div class="card" role="article" tabindex="0">
                    <p>Total Visits</p>
                </div>
                <div class="card" id="payments-card" role="button" tabindex="0" aria-haspopup="dialog" aria-controls="payments-modal" aria-label="View Payments">
                    <p>Payments</p>
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

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const paymentsCard = document.getElementById('payments-card');
        const paymentsModal = document.getElementById('payments-modal');
        const closeModalBtn = document.getElementById('close-modal');
        const paymentsContent = document.getElementById('payments-content');

        const openModal = () => {
            paymentsModal.style.display = 'flex';
            loadPayments();
        };

        const closeModal = () => {
            paymentsModal.style.display = 'none';
            paymentsContent.innerHTML = '<p>Loading payments...</p>';
        };

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

        paymentsCard.addEventListener('click', openModal);
        paymentsCard.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openModal();
            }
        });

        closeModalBtn.addEventListener('click', closeModal);
        window.addEventListener('click', e => {
            if (e.target === paymentsModal) closeModal();
        });

        window.addEventListener('keydown', e => {
            if (e.key === 'Escape' && paymentsModal.style.display === 'flex') {
                closeModal();
            }
        });
    });
</script>
@endsection