<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    // Show sensor management dashboard WITH data
    public function dashboard()
    {
        // Ensure 4 sensors exist (A, B, C, D)
        $sensorNames = [
            'Route A - West Approach',
            'Route B - South Approach',
            'Route C - North Approach',
            'Route D - East Approach'
        ];

        foreach ($sensorNames as $index => $name) {
            Sensor::updateOrCreate(
                ['id' => $index + 1],
                [
                    'name' => $name,
                    // ✅ Use default 0.0 instead of null
                    'latitude' => 0.0,
                    'longitude' => 0.0
                ]
            );
        }

        $sensors = Sensor::orderBy('id')->get();
        return view('superadmin.dashboard', compact('sensors'));
    }

    // API: Fetch sensors for the map
    public function fetchSensors()
    {
        return response()->json(Sensor::all());
    }

    public function updateSensor(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            // ✅ Fix validation syntax (use ":" not "=")
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
        ]);

        $sensor = Sensor::findOrFail($id);
        $sensor->update($request->only('name', 'latitude', 'longitude'));

        // ✅ Sync to Firebase
        $this->syncSensorToFirebase($sensor);

        return back()->with('success', 'Sensor location updated!');
    }

    private function syncSensorToFirebase($sensor)
    {
        try {
            $database = app('firebase.database');
            
            // Map sensor ID to letter (1→A, 2→B, etc.)
            $letter = chr(64 + $sensor->id); // A, B, C, D
            
            // Update sensor_locations in traffic_logs
            $database->getReference('traffic_logs/sensor_locations/' . $letter)
                ->set([
                    'lat' => $sensor->latitude,
                    'lng' => $sensor->longitude,
                    'name' => $sensor->name
                ]);

            // Optional: Also update latest traffic_logs entry
            $latestKey = $database->getReference('traffic_logs')
                ->orderByKey()
                ->limitToLast(1)
                ->getValue();

            if ($latestKey) {
                $key = array_key_first($latestKey);
                $database->getReference("traffic_logs/$key/sensor_locations/$letter")
                    ->set([
                        'lat' => $sensor->latitude,
                        'lng' => $sensor->longitude
                    ]);
            }
        } catch (\Exception $e) {
            Log::error("Firebase sync failed: " . $e->getMessage());
        }
    }
}
