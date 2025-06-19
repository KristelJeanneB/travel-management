@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="map-container">
        <div id="map" style="height: 100vh;"></div>
    </div>


    <div id="settings-modal" class="settings-modal hidden">
        <div class="settings-content">
            <h2>Settings</h2>
            <ul>
                <li>
                    <i class="fas fa-bell"></i> Notification
                    <button class="toggle-button"><i class="fas fa-chevron-right"></i></button>
                </li>
                <li>
                    <i class="fas fa-volume-up"></i> Sounds and alerts
                    <button class="toggle-button"><i class="fas fa-chevron-right"></i></button>
                </li>
                <li>
                    <i class="fas fa-info-circle"></i> About
                    <button class="toggle-button"><i class="fas fa-chevron-right"></i></button>
                </li>
            </ul>
        </div>
    </div>
@endsection