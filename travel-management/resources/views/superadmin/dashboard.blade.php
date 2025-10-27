@extends('layouts.app')

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
        @for ($i = 1; $i <= 4; $i++)
            @php
                $routes = [
                    1 => ['name' => 'Route A ', 'lat' => '16.029969002086467', 'lng' => '120.22734646082192'],
                    2 => ['name' => 'Route B ', 'lat' => '16.030020362249363', 'lng' => '120.22773928488766'],
                    3 => ['name' => 'Route C ', 'lat' => '16.030323444510252', 'lng' => '120.22774256414759'],
                    4 => ['name' => 'Route D ', 'lat' => '16.030192476120416', 'lng' => '120.22811757860168'],
                ];
                $sensor = $sensors[$i - 1] ?? null;
                $default = $routes[$i];
            @endphp

            <div style="flex: 1; min-width: 240px; max-width: 260px; background: white; border: 1px solid #ddd; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 0.1rem 0.2rem rgba(0,0,0,0.05);">
                <h5 style="margin: 0 0 0.75rem; font-size: 1rem; font-weight: 600; color: #0d6efd;">
                    Sensor {{ $i }} ({{ explode(' - ', $default['name'])[0] }})
                </h5>

                <form method="POST" action="{{ route('admin.update-sensor-location', $i) }}" style="font-size: 0.875rem;">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 0.6rem;">
                        <input type="text" name="name"
                            value="{{ old('name.' . $i, $sensor?->name ?? $default['name']) }}"
                            placeholder="Location Name"
                            style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                            required>
                    </div>
                    <div style="margin-bottom: 0.6rem;">
                        <input type="number" step="any" name="latitude"
                            value="{{ old('latitude.' . $i, $sensor?->latitude ?? $default['lat']) }}"
                            min="-90" max="90"
                            placeholder="Latitude"
                            style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                            required>
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <input type="number" step="any" name="longitude"
                            value="{{ old('longitude.' . $i, $sensor?->longitude ?? $default['lng']) }}"
                            min="-180" max="180"
                            placeholder="Longitude"
                            style="width: 100%; padding: 0.35rem; font-size: 0.875rem; border: 1px solid #ccc; border-radius: 0.25rem;"
                            required>
                    </div>
                    <button type="submit"
                        style="width: 100%; padding: 0.4rem; background: #0d6efd; color: white; border: none; border-radius: 0.25rem; font-size: 0.875rem; cursor: pointer;">
                        Save Location
                    </button>
                </form>
            </div>
        @endfor
    </div>
</div>

    <div style="text-align: center; margin-top: 2rem; color: #6c757d; font-size: 0.875rem;">
        &copy; {{ date('Y') }} Traffic Management System | Super Admin Panel
    </div>
</div>
@endsection