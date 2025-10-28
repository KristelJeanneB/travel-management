@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Apply background directly to body */
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background-image: url("{{ asset('images/background.png') }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: #000;
        min-height: 100vh;
        padding-top: 20px;
    }

    /* Frosted glass container */
    .frosted-container {
        background: rgba(255, 255, 255, 0.85); /* Slightly opaque white */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin: 0 auto 2rem;
        max-width: 1200px;
        width: 90%;
        border: 1px solid rgba(106, 139, 176, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        color: #000;
    }

    h2 {
        color: #6a8bb0;
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(to right, #6a8bb0, #5a7aa0);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1rem;
        box-shadow: 0 4px 10px rgba(106, 139, 176, 0.3);
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(106, 139, 176, 0.4);
        background: linear-gradient(to right, #5a7aa0, #4a6990);
    }

    /* Alerts */
    .alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 25px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        max-width: 90%;
        animation: fadeInOut 15s ease-in-out;
    }

    .alert-danger { background: rgba(231, 76, 60, 0.95); }
    .alert-success { background: rgba(46, 204, 113, 0.95); }

    @keyframes fadeInOut {
        0% { opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; }
    }

    /* Cards */
    .report-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(106, 139, 176, 0.2);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(106, 139, 176, 0.15);
    }

    .report-card h6 {
        color: #6a8bb0;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
    }

    .status-badge {
        background-color: rgba(201, 181, 195, 0.4);
        color: #5a4a5a;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 0;
        color: #555;
    }

    .report-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 576px) {
        .frosted-container {
            padding: 1.5rem;
        }
        .report-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="frosted-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Reports</h2>
        <a href="{{ route('poso.dashboard') }}" class="btn btn-primary btn-sm">
            📝 New Report
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0" style="padding-left: 1.25rem; color: white;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($reports->isEmpty())
        <div class="empty-state">
            <p>No reports yet.</p>
            <a href="{{ route('poso.dashboard') }}" class="btn btn-primary btn-sm">Create your first report</a>
        </div>
    @else
        <div class="report-grid">
            @foreach($reports as $report)
                <div class="report-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                        <h6>{{ $report->title }}</h6>
                        <span class="status-badge">{{ ucfirst($report->status) }}</span>
                    </div>
                    <p style="font-size: 0.85rem; color: #666; margin-bottom: 0.75rem;">
                        {{ $report->created_at->format('M d, Y • g:i A') }}
                    </p>
                    @if($report->description)
                        <p style="font-size: 0.95rem; color: #333; margin-bottom: 1rem;">
                            {{ Str::limit($report->description, 100) }}
                        </p>
                    @endif
                    <div style="margin-top: auto;">
                        <div style="display: flex; align-items: center; font-size: 0.85rem; color: #555; margin-bottom: 0.5rem;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 0.35rem; color: #6a8bb0;"></i>
                            {{ number_format($report->lat, 5) }}, {{ number_format($report->lng, 5) }}
                        </div>
                        <p style="font-size: 0.85rem; color: #444;">
                            <strong>Unit:</strong> {{ $report->unit }}<br>
                            <strong>Badge:</strong> {{ $report->badge_number }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection