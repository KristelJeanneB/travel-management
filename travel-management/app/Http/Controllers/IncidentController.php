<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;

class IncidentController extends Controller
{
    protected FirebaseService $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

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

        try {
            $database = $this->firebaseService->getDatabase();
            $reference = $database->getReference('incidents/' . $incident->id);
            $reference->set([
                'title' => $incident->title,
                'description' => $incident->description,
                'lat' => (float)$incident->lat,
                'lng' => (float)$incident->lng,
                'created_at' => $incident->created_at->toIso8601String(),
                'status' => $incident->status,
            ]);
        } catch (\Exception $e) {
            Log::error("Firebase sync failed", ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Incident reported successfully!',
            'incident' => $incident
        ], 201);
    }

    /**
     * Migrate all existing incidents from SQL to Firebase.
     */
    public function migrateIncidents()
    {
        $database = $this->firebaseService->getDatabase();

        $incidents = Incident::all();

        foreach ($incidents as $incident) {
            try {
                $reference = $database->getReference('incidents/' . $incident->id);
                $reference->set([
                    'title' => $incident->title,
                    'description' => $incident->description,
                    'lat' => (float)$incident->lat,
                    'lng' => (float)$incident->lng,
                    'created_at' => $incident->created_at->toIso8601String(),
                    'status' => $incident->status,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to migrate incident ID {$incident->id}", ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'message' => 'All incidents migrated to Firebase successfully!'
        ]);
    }

    // ... your fetch() and destroy() methods remain unchanged ...
}
