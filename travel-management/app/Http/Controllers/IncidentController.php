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
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:accident,traffic_jam,road_closure,hazard',
            'description' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        Incident::create([
            'title' => ucfirst(str_replace('_', ' ', $request->type)),
            'description' => $request->description,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => 'reported',
        ]);

        return response()->json(['message' => 'Incident reported successfully!']);
    }
}
