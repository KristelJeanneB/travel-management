@extends('layouts.app')
<title>RouteLink_SuperAdmin</title>
@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1 style="color: #0d6efd; font-weight: bold; margin: 0;">📊 Super Admin Dashboard</h1>
        <span style="font-size: 0.9rem; color: #6c757d;">Welcome back, {{ auth()->user()->name }}</span>
    </div>
       <!-- Sensor Management Section -->
<div style="background: #f8f9fa; padding: 1.5rem; border-radius: 0.5rem; margin-top: 2rem; border: 1px solid #dee2e6;">
    <h2 style="font-size: 1.25rem; font-weight: 600; color: #333; margin-bottom: 1rem;">
        📍 Manage Sensor Locations (Routes A–D)
    </h2>

    @if (session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 0.75rem; border-radius: 0.375rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
        @foreach(['A', 'B', 'C', 'D'] as $id)
    @php
        $sensor = $sensors->firstWhere('sensor_id', $id);
        $defaults = [
            'A' => ['name' => 'Route A – West Approach', 'lat' => 16.02996900, 'lng' => 120.22734646, 'start_lat' => 16.03002, 'start_lng' => 120.22734, 'end_lat' => 16.02985, 'end_lng' => 120.22687],
            'B' => ['name' => 'Route B – South Approach', 'lat' => 16.03002036, 'lng' => 120.22773928, 'start_lat' => 16.03014, 'start_lng' => 120.22783, 'end_lat' => 16.03028, 'end_lng' => 120.22845],
            'C' => ['name' => 'Route C – North Approach', 'lat' => 16.03032344, 'lng' => 120.22774256, 'start_lat' => 16.03034, 'start_lng' => 120.22775, 'end_lat' => 16.03085, 'end_lng' => 120.22761],
            'D' => ['name' => 'Route D – East Approach', 'lat' => 16.03019248, 'lng' => 120.22811758, 'start_lat' => 16.02997, 'start_lng' => 120.22762, 'end_lat' => 16.02945, 'end_lng' => 120.22772],
        ];
        $default = $defaults[$id];
    @endphp

    <div style="flex: 1; min-width: 280px; max-width: 300px; background: white; border: 1px solid #ddd; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 0.1rem 0.2rem rgba(0,0,0,0.05);">
        <h5 style="margin: 0 0 0.75rem; font-size: 1rem; font-weight: 600; color: #0d6efd;">
            Sensor {{ $id }} ({{ explode(' – ', $default['name'])[0] }})
        </h5>

        <form method="POST" action="{{ route('admin.update-sensor-location', $id) }}" style="font-size: 0.875rem;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 0.6rem;">
                <input type="text" name="name"
                    value="{{ old('name', $sensor?->name ?? $default['name']) }}"
                    placeholder="Location Name"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>

            <!-- Main sensor location (for marker) -->
            <div style="margin-bottom: 0.6rem;">
                <input type="number" step="any" name="latitude"
                    value="{{ old('latitude', $sensor?->latitude ?? $default['lat']) }}"
                    min="-90" max="90"
                    placeholder="Main Latitude"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>
            <div style="margin-bottom: 0.6rem;">
                <input type="number" step="any" name="longitude"
                    value="{{ old('longitude', $sensor?->longitude ?? $default['lng']) }}"
                    min="-180" max="180"
                    placeholder="Main Longitude"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>

            <!-- Start point -->
            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #eee; font-weight: bold; color: #555;">
                🚦 Detection Zone Start
            </div>
            <div style="margin-bottom: 0.6rem;">
                <input type="number" step="any" name="start_lat"
                    value="{{ old('start_lat', $sensor?->start_lat ?? $default['start_lat']) }}"
                    min="-90" max="90"
                    placeholder="Start Latitude"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>
            <div style="margin-bottom: 0.6rem;">
                <input type="number" step="any" name="start_lng"
                    value="{{ old('start_lng', $sensor?->start_lng ?? $default['start_lng']) }}"
                    min="-180" max="180"
                    placeholder="Start Longitude"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>

            <!-- End point -->
            <div style="margin-top: 8px; font-weight: bold; color: #555;">
                🏁 Detection Zone End
            </div>
            <div style="margin-bottom: 0.6rem;">
                <input type="number" step="any" name="end_lat"
                    value="{{ old('end_lat', $sensor?->end_lat ?? $default['end_lat']) }}"
                    min="-90" max="90"
                    placeholder="End Latitude"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>
            <div style="margin-bottom: 0.75rem;">
                <input type="number" step="any" name="end_lng"
                    value="{{ old('end_lng', $sensor?->end_lng ?? $default['end_lng']) }}"
                    min="-180" max="180"
                    placeholder="End Longitude"
                    style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                    required>
            </div>

            <button type="submit"
                style="width: 100%; padding: 0.4rem; background: #0d6efd; color: white; border: none; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer;">
                Save Sensor Zone
            </button>
        </form>
    </div>
@endforeach
    </div>
</div>

    <div style="text-align: center; margin-top: 2rem; color: #6c757d; font-size: 0.875rem;">
        &copy; {{ date('Y') }} Traffic Management System | Super Admin Panel
    </div>
</div>
@endsection