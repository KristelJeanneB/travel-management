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
            background-color: #f4f4f4;
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
            border-color: #86A8CF;
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
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
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
            width: 280px;
            text-align: center;
            font-weight: 500;
            color: #444;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-top: 4px solid #86A8CF;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .card p {
            margin-bottom: 12px;
            font-size: 18px;
        }

        .card input[type="password"],
        .card input[type="checkbox"] {
            margin: 8px 0;
            padding: 8px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .card input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        .card label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }

        .card button {
            background: #86A8CF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
            font-weight: 500;
        }

        .card button:hover {
            background: #6a8cb3;
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
</head>
<body>

    <div class="header">
        <h1>Admin</h1>
    </div>

    <div class="dashboard-container">

        <div class="sidebar">
            <ul>
                <li>
                    <a href="{{ route('homeAdmin') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('alerts') }}">
                        <i class="fas fa-bell"></i> Alerts
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
        </div>

        <div class="main-content">
            <header>
                <input type="text" placeholder="Search..." class="search-bar" />
                <button class="profile-btn" aria-label="User Profile">
                    <i class="fas fa-user"></i>
                </button>
            </header>

            <section class="overview">
                <h2>Admin Settings</h2>
                <div class="card-group">
                    <div class="card">
                        <p>Change Password</p>
                        <form>
                            <input type="password" placeholder="Old Password" required />
                            <input type="password" placeholder="New Password" required />
                            <input type="password" placeholder="Confirm New Password" required />
                            <button type="submit">Save Changes</button>
                        </form>
                    </div>

                    <div class="card">
                        <p>Data Privacy</p>
                        <label>
                            <input type="checkbox" checked /> Allow Data Sharing
                        </label>
                        <button>Save Privacy</button>
                    </div>

                    <div class="card">
                        <p>User Settings</p>
                        <label>
                            <input type="checkbox" /> Allow New Users
                        </label>
                        <label>
                            <input type="checkbox" checked /> Email Verification
                        </label>
                        <button>Save User Settings</button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>
</html>