@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #0d6efd; font-weight: 700;">My Reports</h2>
        <a href="{{ route('poso.dashboard') }}" class="btn btn-primary btn-sm" style="border-radius: 4px;">
            📝 New Report
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 6px;">
            <ul class="mb-0" style="padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    @if($reports->isEmpty())
        <div class="text-center" style="padding: 3rem 0;">
            <p class="text-muted">No reports yet.</p>
            <a href="{{ route('poso.dashboard') }}" class="btn btn-primary btn-sm">Create your first report</a>
        </div>
    @else
        <div class="row" style="gap: 1.5rem; row-gap: 1.5rem; margin-left: -0.75rem; margin-right: -0.75rem;">
            @foreach($reports as $report)
                <div class="col-12 col-md-6 col-lg-4" style="padding-left: 0.75rem; padding-right: 0.75rem;">
                    <div class="card" style="height: 100%; border: 1px solid #e9ecef; border-radius: 8px; padding: 1rem; background: white;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                            <h6 style="font-weight: 600; margin: 0; font-size: 1.1rem;">{{ $report->title }}</h6>
                            <span style="background-color: #ffeaa7; color: #555; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: 500;">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>

                        <p style="color: #6c757d; font-size: 0.85rem; margin-bottom: 0.75rem;">
                            {{ $report->created_at->format('M d, Y • g:i A') }}
                        </p>

                        @if($report->description)
                            <p style="color: #495057; font-size: 0.95rem; margin-bottom: 1rem; flex-grow: 1;">
                                {{ Str::limit($report->description, 100) }}
                            </p>
                        @endif

                        <div style="margin-top: auto;">
                            <div style="display: flex; align-items: center; color: #6c757d; font-size: 0.85rem; margin-bottom: 0.5rem;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 0.35rem;"></i>
                                {{ number_format($report->lat, 5) }}, {{ number_format($report->lng, 5) }}
                            </div>
                            <p style="color: #6c757d; font-size: 0.85rem; margin-bottom: 0;">
                                <strong>Unit:</strong> {{ $report->unit }}<br>
                                <strong>Badge:</strong> {{ $report->badge_number }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection