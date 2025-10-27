@extends('layouts.app')
@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
    <h1>⏳ Pending Incident Approvals</h1>
    
    @if(session('success'))
        <div style="background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Type</th>
                <th>Reporter</th>
                <th>Description</th>
                <th>Location</th>
                <th>Reported At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidents as $incident)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $incident->type)) }}</td>
                <td>
                    {{ $incident->user->name }}<br>
                    <small>({{ ucfirst($incident->reporter_role) }})</small>
                </td>
                <td>{{ $incident->description ?? 'N/A' }}</td>
                <td>{{ $incident->lat }}, {{ $incident->lng }}</td>
                <td>{{ $incident->created_at->format('M d, Y H:i') }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.incidents.approve', $incident->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px;">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.incidents.reject', $incident->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; margin-left: 5px;">Reject</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No pending incidents</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection