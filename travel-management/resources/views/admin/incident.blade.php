@extends('layouts.admin')

@section('content')

<h2>Incident Details</h2>

@if($incident)
    <p><strong>Title:</strong> {{ $incident->title }}</p>
    <p><strong>Description:</strong> {{ $incident->description }}</p>
    <p><strong>Status:</strong> {{ $incident->status }}</p>
    <p><strong>Location:</strong> {{ $incident->lat }}, {{ $incident->lng }}</p>
@else
    <p>No incident found.</p>
@endif

<div id="incident-map" style="height: 400px;"></div>

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const map = L.map('incident-map').setView([{{ $incident->lat }}, {{ $incident->lng }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $incident->lat }}, {{ $incident->lng }}])
        .addTo(map)
        .bindPopup("{{ $incident->title }}")
        .openPopup();
</script>
@endsection