<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

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

    public function create()
    {
        return view('incident.create');
    }

 
    public function store(Request $request)
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


    public function fetch()
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
}