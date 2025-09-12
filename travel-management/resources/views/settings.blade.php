@extends('layouts.app')

@section('title', 'Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
@php
    $previous = request()->get('previous', 'home');
    $backRoute = $previous === 'map' ? route('map') : route('home');
@endphp

<div class="settings-wrapper">

    <nav class="settings-nav">
        <a href="{{ $backRoute }}" class="nav-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Settings</h1>
    </nav>

    <div class="settings-panel">

        <section class="setting-section">
            <h2>About Us</h2>
            <p>
                Welcome to our Travel Management Platform!  
                BABY BEA!!!
            </p>
        </section>

        <section class="setting-section">
            <h2>Preferences</h2>
            <ul class="settings-list">
                <li>
                    <button class="setting-button" disabled>
                        <i class="fas fa-bell"></i> Notifications
                    </button>
                </li>
                <li>
                    <button class="setting-button" disabled>
                        <i class="fas fa-volume-up"></i> Sounds & Alerts
                    </button>
                </li>
                <li>
                    <button class="setting-button" disabled>
                        <i class="fas fa-user-shield"></i> Privacy
                    </button>
                </li>
                <li>
                    <button class="setting-button" disabled>
                        <i class="fas fa-user-cog"></i> Account Settings
                    </button>
                </li>
            </ul>
        </section>

    </div>
</div>
@endsection
