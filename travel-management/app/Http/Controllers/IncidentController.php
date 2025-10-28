<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class IncidentController extends Controller
{
    protected ?Database $firebase = null;

    public function __construct()
    {
        try {
            $credentialsPath = config('services.firebase.credentials');
            $databaseUri = config('services.firebase.database_uri');

            if (!file_exists($credentialsPath)) {
                Log::warning("Firebase credentials not found at: {$credentialsPath}");
                return;
            }

            $factory = (new Factory())->withServiceAccount($credentialsPath);
            $this->firebase = $factory->withDatabaseUri($databaseUri)->createDatabase();

            Log::info("✅ Firebase connected successfully");
        } catch (\Exception $e) {
            Log::error("🔴 Firebase init failed: " . $e->getMessage());
        }
    }

    /**
     * Store a new incident
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|string|in:accident,traffic_jam,road_closure,hazard',
        'description' => 'nullable|string|max:1000',
        'lat' => 'required|numeric|between:-90,90',
        'lng' => 'required|numeric|between:-180,180',
        'unit' => 'nullable|string',           
        'badge_number' => 'nullable|string',   
    ]);

    try {
        $incident = Incident::create([
            'user_id' => auth()->id(),
            'title' => ucfirst(str_replace('_', ' ', $validated['type'])),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'status' => 'reported',
            'reporter_role' => auth()->user()->role, 
            'unit' => $validated['unit'] ?? null,
            'badge_number' => $validated['badge_number'] ?? null,
        ]);

        $this->syncToFirebase($incident);

        return response()->json([
            'message' => 'Incident reported successfully!',
            'incident' => $incident
        ], 201);
    } catch (\Exception $e) {
        Log::error('💥 Failed to save incident', [
            'error' => $e->getMessage(),
        ]);
        return response()->json(['message' => 'Failed to report incident.'], 500);
    }
}

    public function fetch()
{
    $incidents = Incident::with('user:id,name,email')
        ->select(
            'id',
            'user_id',
            'title',
            'description',
            'lat',
            'lng',
            'status',
            'created_at',
            'reporter_role'
        )
        ->latest()
        ->get()
        ->map(function ($incident) {
            return [
                'id' => $incident->id,
                'title' => $incident->title,
                'description' => $incident->description,
                'lat' => $incident->lat,
                'lng' => $incident->lng,
                'status' => $incident->status,
                'created_at' => $incident->created_at,
                'reporter_role' => $incident->reporter_role,
                'reported_by' => [
                    'id' => $incident->user?->id,
                    'name' => $incident->user?->name ?? 'Unknown',
                    'email' => $incident->user?->email ?? null,
                ],
            ];
        });

    return response()->json($incidents);
}

    /**
     * Mark incident as resolved
     */
    public function resolve(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $incident->status = 'resolved';
        $incident->save();

        Log::info("✅ Incident marked as resolved", ['id' => $id]);


        try {
            $this->syncToFirebase($incident);
        } catch (\Exception $e) {
            Log::warning("⚠️ Firebase sync failed on resolve", [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Incident marked as resolved.'
        ]);
    }

    /**
     * Reverse geocode coordinates to address
     */
    public function reverseGeocode(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        if (!$lat || !$lon) {
            return response()->json(['error' => 'Latitude and longitude are required.'], 400);
        }

        try {
          
            $response = Http::withHeaders([
                'User-Agent' => 'TrafficMonitorApp/1.0' // Required by Nominatim
            ])->timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lon,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::warning('Nominatim API error', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Location service error.'], 502);

        } catch (\Exception $e) {
            Log::error('🔴 Reverse geocode failed: ' . $e->getMessage());
            return response()->json(['error' => 'Reverse geocoding failed.'], 500);
        }
    }

    // === PRIVATE: SYNC TO FIREBASE ===
    private function syncToFirebase($incident)
{
    if (!$this->firebase) {
        Log::warning("Firebase not initialized. Skipping sync.");
        return;
    }

    try {
        $reference = $this->firebase->getReference('incidents/' . $incident->id);
        $reference->set([
            'title' => $incident->title,
            'description' => $incident->description ?? '',
            'lat' => $incident->lat,
            'lng' => $incident->lng,
            'type' => $incident->type ?? 'unknown',
            'status' => $incident->status,
            'reporter_role' => $incident->reporter_role,
            'created_at' => $incident->created_at?->toISOString() ?? now()->toISOString(),
        ]);
        Log::info("🔥 Synced to Firebase", ['id' => $incident->id]);
    } catch (\Exception $e) {
        Log::error("❌ Firebase sync failed", [
            'id' => $incident->id,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}
    public function updateStatus(Request $request, $id)
{
    $incident = Incident::findOrFail($id);

    $request->validate(['status' => 'required|in:reported,resolved']);
    
    $oldStatus = $incident->status;
    $newStatus = $request->status;


    $incident->status = $newStatus;
    $incident->save();

    try {
        $factory = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(config('services.firebase.credentials'));

        $database = $factory->withDatabaseUri(config('services.firebase.database_uri'))->createDatabase();

        $reference = $database->getReference("incidents/{$id}");
        $resolvedRef = $database->getReference("resolved_incidents/{$id}");

        if ($newStatus === 'resolved' && $oldStatus !== 'resolved') {

            $snapshot = $reference->getSnapshot();
            if ($snapshot->exists()) {
                $data = $snapshot->getValue();
                $resolvedRef->set(array_merge($data, [
                    'resolved_at' => now()->toISOString()
                ]));
                $reference->remove(); 
            }
        } elseif ($newStatus === 'reported' && $oldStatus === 'resolved') {
        
            $snapshot = $resolvedRef->getSnapshot();
            if ($snapshot->exists()) {
                $data = $snapshot->getValue();
                unset($data['resolved_at']); 
                $reference->set($data);
                $resolvedRef->remove();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated and synced.',
            'status' => $newStatus
        ]);

    } catch (\Exception $e) {
        Log::error("Firebase move failed: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to sync with Firebase.'
        ], 500);
    }
}
public function destroy($id)
{
    $incident = Incident::findOrFail($id);
    $incident->delete(); 

    return response()->json(['success' => true]);
}
public function fetchPoso()
{
    $incidents = Incident::with('user:id,name,email')
        ->where('reporter_role', 'poso')
        ->select(
            'id',
            'user_id',
            'title',
            'description',
            'lat',
            'lng',
            'status',
            'created_at',
            'reporter_role',
            'unit',            
            'badge_number'     
        )
        ->latest()
        ->get()
        ->map(function ($incident) {
            return [
                'id' => $incident->id,
                'title' => $incident->title,
                'description' => $incident->description,
                'lat' => $incident->lat,
                'lng' => $incident->lng,
                'status' => $incident->status,
                'created_at' => $incident->created_at,
                'reporter_role' => $incident->reporter_role,
                'reported_by' => [
                    'id' => $incident->user?->id,
                    'name' => $incident->user?->name ?? 'Unknown',
                ],
                'unit' => $incident->unit ?? 'N/A',          
                'badge_number' => $incident->badge_number ?? 'N/A', 
            ];
        });

    return response()->json($incidents);
}
}