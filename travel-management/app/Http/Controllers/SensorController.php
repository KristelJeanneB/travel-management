<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class SensorController extends Controller
{
    public function index()
    {
        $defaultCoords = [
            1 => ['name' => 'Route A', 'lat' => 16.029969002086467, 'lng' => 120.22734646082192],
            4 => ['name' => 'Route D', 'lat' => 16.030192476120416, 'lng' => 120.22811757860168],
        ];

        for ($i = 1; $i <= 4; $i++) {
            Sensor::firstOrCreate(
                ['id' => $i],
                [
                    'name' => $defaultCoords[$i]['name'],
                    'latitude' => $defaultCoords[$i]['lat'],
                    'longitude' => $defaultCoords[$i]['lng'],
                ]
            );
        }

        $sensors = Sensor::orderBy('id')->take(4)->get();
        return view('admin.sensor-locations', compact('sensors'));
    }

    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'latitude' => 'required|numeric|min:-90|max:90',
        'longitude' => 'required|numeric|min:-180|max:180',
        'start_lat' => 'required|numeric|min:-90|max:90',
        'start_lng' => 'required|numeric|min:-180|max:180',
        'end_lat' => 'required|numeric|min:-90|max:90',
        'end_lng' => 'required|numeric|min:-180|max:180',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $sensor = Sensor::updateOrCreate(
        ['id' => $id],
        [
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
        ]
    );

    // Sync ALL sensors to Firebase config
    $allSensors = Sensor::orderBy('id')->get();
    $sensorConfig = [];

    foreach ($allSensors as $s) {
        $sensorConfig[$s->sensor_id] = [
            // Main marker location
            'lat' => (float) $s->latitude,
            'lng' => (float) $s->longitude,
            // Detection zone
            'start' => [
                'lat' => (float) $s->start_lat,
                'lng' => (float) $s->start_lng,
            ],
            'end' => [
                'lat' => (float) $s->end_lat,
                'lng' => (float) $s->end_lng,
            ],
            // Optional: radius (if still used)
            'radius' => 100
        ];
    }

    try {
        $client = new Client();
        $client->put(
            "https://management-6d07b-default-rtdb.firebaseio.com/traffic_config/sensors.json",
            ['json' => $sensorConfig]
        );
    } catch (\Exception $e) {
        Log::error('Firebase sync failed: ' . $e->getMessage());
    }

    return redirect()->back()->with('success', "Sensor {$id} updated!");
}
public function show($id)
{
    $sensor = Sensor::findOrFail($id);
    return response()->json($sensor);
}
}