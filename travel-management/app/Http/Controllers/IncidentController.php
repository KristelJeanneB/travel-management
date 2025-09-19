<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IncidentController extends Controller
{
    /**
     * Display a listing of incidents.
     */
    public function index()
    {
        $incidents = Incident::all();
        return view('incident.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new incident.
     */
    public function create()
    {
        return view('incident.create');
    }

    /**
     * Store a newly created incident in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:accident,traffic_jam,road_closure,hazard',
            'description' => 'nullable|string|max:1000',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $incident = Incident::create([
            'title' => ucfirst(str_replace('_', ' ', $validated['type'])),
            'description' => $validated['description'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'status' => 'reported',
        ]);

        return response()->json([
            'message' => 'Incident reported successfully!',
            'incident' => $incident
        ], 201);
    }

    /**
     * Fetch all incidents (for admin dashboard)
     */
    public function fetch(): JsonResponse
    {
        $incidents = Incident::latest()->get([
            'id',
            'title',
            'description',
            'lat',
            'lng',
            'created_at'
        ]);

        return response()->json($incidents);
    }

    public function destroy($id): JsonResponse
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();

        return response()->json([
            'success' => true,
            'message' => 'Incident removed successfully.'
        ]);
    }
}