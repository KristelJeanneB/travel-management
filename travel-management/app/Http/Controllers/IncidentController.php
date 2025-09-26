<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

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

        // Save to SQL
        $incident = Incident::create([
            'title' => ucfirst(str_replace('_', ' ', $validated['type'])),
            'description' => $validated['description'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'status' => 'reported',
        ]);

        // === SYNC TO FIREBASE ===
        $credentialsPath = config('services.firebase.credentials');

        // Resolve absolute path
        if (!file_exists($credentialsPath)) {
            $absolutePath = base_path($credentialsPath);
            if (!file_exists($absolutePath)) {
                Log::error("Firebase credentials not found", [
                    'relative' => $credentialsPath,
                    'absolute' => $absolutePath
                ]);
                return response()->json([
                    'message' => 'Incident saved locally (Firebase unreachable)',
                    'incident' => $incident
                ], 201);
            }
            $credentialsPath = $absolutePath;
        }

        try {
            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($credentialsPath);
            
             $factory = (new Factory)->withServiceAccount($credentialsPath);
    $database = $factory->createDatabase('https://traffic-management-7b675-default-rtdb.firebaseio.com');

    $reference = $database->getReference('incidents/' . $incident->id);
    $reference->set([
        'title' => $incident->title,
        'description' => $incident->description,
        'lat' => (float)$incident->lat,
        'lng' => (float)$incident->lng,
        'created_at' => now()->toISOString(),
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

    /**
     * Remove the specified incident from storage (SQL + Firebase).
     */
    public function destroy($id): JsonResponse
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();

        try {
            $credentialsPath = config('services.firebase.credentials');
            if (!file_exists($credentialsPath)) {
                $credentialsPath = base_path($credentialsPath);
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
    $database = $factory->createDatabase('https://traffic-management-7b675-default-rtdb.firebaseio.com');
    
    $database->getReference('incidents/' . $id)->remove();
} catch (\Exception $e) {
    Log::warning("Failed to delete from Firebase", ['error' => $e->getMessage()]);
}

        return response()->json([
            'success' => true,
            'message' => 'Incident removed successfully.'
        ]);
    }
}