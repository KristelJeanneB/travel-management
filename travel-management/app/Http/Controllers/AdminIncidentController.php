<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Incident;


class AdminIncidentController extends Controller
{

     public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $incident = Incident::create([
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'latitude' => $validated['lat'],
            'longitude' => $validated['lng'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Incident reported successfully.',
            'incident' => $incident,
        ]);
    }
    public function index()
    {
        $incidents = DB::table('incidents')->orderBy('created_at', 'desc')->get();

        return view('admin.incident', compact('incidents'));
    }


    public function fetchIncidents()
    {
        $incidents = DB::table('incidents')->orderBy('created_at', 'desc')->get();

        return response()->json($incidents);
    }
}
