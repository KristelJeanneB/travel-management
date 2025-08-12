<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
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

 .main-content {
        flex: 1;
        padding: 100px;
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
    }

    header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

header input[type="text"] {
    padding: 10px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
}

header button {
    background: none;
    border: none;
    color: #333;
    font-size: 18px;
    cursor: pointer;
}

.overview h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
    font-size: 20px;
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

@media (max-width: 768px) {
    .dashboard-container {
        flex-direction: column;
        height: auto;
    }

    .sidebar {
        width: 100%;
        padding: 10px;
    }

    .sidebar li {
        padding: 10px;
        font-size: 16px;
    }

    .card-group {
        flex-direction: column;
        align-items: center;
    }

    .card {
        width: 80%;
    }
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
</head>
<body>

<div class="background-image"></div>

<div class="dashboard-container">
    <div class="sidebar">
        <ul>
            <li class="{{ request()->routeIs('homeAdmin') ? 'active' : '' }}">
                <a href="{{ route('homeAdmin') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('view') ? 'active' : '' }}">
                <a href="{{ route('view') }}">
                    <i class="fas fa-map-marked-alt"></i> Map View
                </a>
            </li>
            <li class="{{ request()->routeIs('alerts') ? 'active' : '' }}">
                <a href="{{ route('alerts') }}">
                    <i class="fas fa-bell"></i> Alerts
                </a>
            </li>
            <li class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                <a href="{{ route('settings') }}">
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
            <input type="text" placeholder="Search..." />
            <button><i class="fas fa-user"></i></button>
        </header>

        <section class="overview">
            <h2>Settings</h2>
            <div class="card-group">
                <div class="card">
                    <p>Change Password</p>
                    <form>
                        <input type="password" placeholder="Old Password" />
                        <input type="password" placeholder="New Password" />
                        <input type="password" placeholder="Confirm New Password" />
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
                        <input type="checkbox" /> Email Verify
                    </label>
                    <button>Save User Settings</button>
                </div>
            </div>
        </section>
    </div>
</div>

</body>
</html>