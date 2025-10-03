<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Settings</title>

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

        /* Header */
        .header {
            background-color: #86A8CF;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1100;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 600;
            color: white;
        }

        /* Dashboard Container */
        .dashboard-container {
            position: relative;
            width: 95%;
            max-width: 100%;
            margin: 80px auto 20px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
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
            justify-content: flex-end;
            margin-bottom: 30px;
            gap: 14px;
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

        /* Section Title */
        .overview h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 26px;
            font-weight: 600;
        }

        /* Card Group */
        .card-group {
            display: flex;
            gap: 28px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Cards */
        .card {
            background-color: #ffffff;
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            width: 320px;
            text-align: center;
            transition: all 0.3s ease;
            border-top: 5px solid #86A8CF;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.14);
        }

        .card i.icon {
            font-size: 30px;
            margin-bottom: 14px;
            display: block;
            color: #86A8CF;
        }

        .card p {
            font-size: 19px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 16px;
        }

        /* Form Styles */
        .setting-item {
            margin: 14px 0;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .setting-item label {
            font-size: 15px;
            color: #444;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        /* Custom Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            margin-left: 8px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .3s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #86A8CF;
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        /* Input Fields */
        .card input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        .card input[type="password"]:focus {
            border-color: #86A8CF;
            box-shadow: 0 0 0 2px rgba(134, 168, 207, 0.2);
        }

        /* Button */
        .card button {
            background: #86A8CF;
            color: white;
            border: none;
            padding: 11px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            font-weight: 600;
            font-size: 15px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .card button:hover {
            background: #6a8cb3;
            transform: translateY(-1px);
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

            .main-content {
                padding: 25px;
            }

            .card-group {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 90%;
                padding: 24px;
            }

            .card p {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Admin Settings</h1>
</div>

<div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
        <ul>
            <li>
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
            <li class="active">
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

    <!-- Main Content -->
    <main class="main-content">
        <header>
            <button class="profile-btn" aria-label="User Profile">
                <i class="fas fa-user"></i>
            </button>
        </header>

        <section class="overview">
            <h2>Admin Settings</h2>
            <div class="card-group">

                <!-- Change Password -->
                <div class="card">
                    <i class="fas fa-key icon"></i>
                    <p>Change Password</p>
                    <form id="changePasswordForm">
                        <input type="password" placeholder="Old Password" required />
                        <input type="password" placeholder="New Password" required />
                        <input type="password" placeholder="Confirm New Password" required />
                        <button type="submit">Save Changes</button>
                    </form>
                </div>

                <!-- Data Privacy -->
                <div class="card">
                    <i class="fas fa-shield-alt icon"></i>
                    <p>Data Privacy</p>
                    <div class="setting-item">
                        <label>
                            <input type="checkbox" class="toggle-input" checked>
                            <span class="toggle-switch"><span class="slider"></span></span>
                            Allow Data Sharing
                        </label>
                    </div>
                    <button id="savePrivacyBtn">Save Privacy</button>
                </div>

                <!-- User Settings -->
                <div class="card">
                    <i class="fas fa-users-cog icon"></i>
                    <p>User Settings</p>
                    <div class="setting-item">
                        <label>
                            <input type="checkbox" class="toggle-input">
                            <span class="toggle-switch"><span class="slider"></span></span>
                            Allow New Users
                        </label>
                    </div>
                    <div class="setting-item">
                        <label>
                            <input type="checkbox" class="toggle-input" checked>
                            <span class="toggle-switch"><span class="slider"></span></span>
                            Email Verification Required
                        </label>
                    </div>
                    <button id="saveUserSettingsBtn">Save Settings</button>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Success Toast Notification -->
<div id="toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 14px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    font-size: 15px;
">Settings saved!</div>

<script>
    function showToast(message = "Settings saved!") {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.opacity = 1;

        setTimeout(() => {
            toast.style.opacity = 0;
        }, 3000);
    }

    // Handle form submission
    document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert("Password change request sent.");
        this.reset();
        showToast("Password updated!");
    });

    // Save Privacy Button
    document.getElementById('savePrivacyBtn')?.addEventListener('click', () => {
        showToast("Privacy settings saved.");
    });

    // Save User Settings Button
    document.getElementById('saveUserSettingsBtn')?.addEventListener('click', () => {
        showToast("User settings updated.");
    });
</script>

</body>
</html>