@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="background-image"></div>

<div class="dashboard-container">
    
    <div class="sidebar">
        <ul>
            <li><i class="fas fa-home"></i> Dashboard</li>
            <li><i class="fas fa-map-marked-alt"></i> Map View</li>
            <li><i class="fas fa-bell"></i> Alerts</li>
            <li><i class="fas fa-cog"></i> Settings</li>
            <li>
            <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
            </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
            </form>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <input type="text" placeholder="Search..." class="search-bar">
            <button class="profile-btn"><i class="fas fa-user"></i></button>
        </header>

        <section class="overview">
            <h2>Dashboard Overview</h2>
            <div class="card-group">
                <div class="card">
                    <p>Manage Users</p>
                </div>
                <div class="card">
                    <p>See all alerts</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection