@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="dashboard-container">
    <h2>Admin Dashboard</h2>
    <p>Welcome back, {{ Auth::user()->name }}</p>

    <div class="card">
        <h3>User Statistics</h3>
        <p>Total Users: {{ $totalUsers ?? 0 }}</p>
    </div>

    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
</div>
@endsection