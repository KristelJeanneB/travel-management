<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;

class PosoController extends Controller
{
    // Only authenticated users (POSO personnel)
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show POSO Dashboard
     */
    public function dashboard()
    {
        return view('poso.dashboard');
    }

    /**
     * Show the incident report form
     */
    public function createReport()
    {
        return view('poso.report-create');
    }

    /**
     * Store a new incident report
     */
    public function store(Request $request)
{
    $request->validate([
        'type' => 'required|string',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'unit' => 'required|string',
        'badge_number' => 'required|string',
        'description' => 'nullable|string',
    ]);

    $incident = Incident::create([
        'user_id' => auth()->id(),
        'type' => $request->type,
        'lat' => $request->lat,
        'lng' => $request->lng,
        'description' => $request->description,
        'reporter_role' => auth()->user()->role, // e.g., 'poso'
        'status' => 'active',
        'extra_data' => json_encode([
            'unit' => $request->unit,
            'badge_number' => $request->badge_number
        ])
    ]);

    // Sync to Firebase (use your existing method)
    $this->syncToFirebase($incident);

    return response()->json([
        'message' => 'Incident reported successfully!',
        'incident' => $incident
    ]);
}
}
