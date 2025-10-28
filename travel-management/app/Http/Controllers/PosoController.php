<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Incident;

class PosoController extends Controller
{
    // ✅ Constructor to ensure user authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 🔥 Sync report to Firebase
    private function syncToFirebase($incident)
    {
        try {
            $credentialsPath = config('services.firebase.credentials');
            $databaseUri = config('services.firebase.database_uri');

            if (!file_exists($credentialsPath)) {
                Log::warning("Firebase credentials not found at: {$credentialsPath}");
                return;
            }

            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($credentialsPath);
            $database = $factory->withDatabaseUri($databaseUri)->createDatabase();

            $database->getReference('incidents/' . $incident->id)->set([
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
        }
    }

    // 🏠 Dashboard
    public function dashboard()
    {
        return view('poso.dashboard');
    }

    // 📝 Show the report form
    public function createReport()
    {
        return view('poso.report-create');
    }

    // 🚨 Store new incident report
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:accident,traffic_jam,road_closure,hazard,suspicious_activity',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'unit' => 'required|string',
            'badge_number' => 'required|string',
            'description' => 'nullable|string|max:1000',
        ]);

        $incident = Incident::create([
            'user_id' => auth()->id(),
            'title' => ucfirst(str_replace('_', ' ', $request->type)),
            'type' => $request->type,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'description' => $request->description,
            'reporter_role' => auth()->user()->role,
            'status' => 'reported',
            'unit' => $request->unit,
            'badge_number' => $request->badge_number,
        ]);

        // 🔥 Sync to Firebase
        $this->syncToFirebase($incident);

        // ✅ Redirect to history page
        return redirect()
            ->route('poso.report')
            ->with('success', 'Incident reported successfully!');
    }

    // 📋 Show report history
    public function report()
    {
        $reports = Incident::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('poso.report', compact('reports'));
    }
}
