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
            2 => ['name' => 'Route B', 'lat' => 16.030020362249363, 'lng' => 120.22773928488766],
            3 => ['name' => 'Route C', 'lat' => 16.030323444510252, 'lng' => 120.22774256414759],
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sensor = Sensor::findOrFail($id);
        $sensor->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $allSensors = Sensor::orderBy('id')->take(4)->get();
        $routeLetters = ['A', 'B', 'C', 'D'];
        $sensorLocations = [];

        foreach ($allSensors as $index => $s) {
            $letter = $routeLetters[$index];
            $sensorLocations[$letter] = [
                'lat' => (float) $s->latitude,
                'lng' => (float) $s->longitude
            ];
        }

        $firebaseData = [
            'created_at' => now()->toDateTimeString(),
            'sensorA' => ['traffic' => false],
            'sensorB' => ['traffic' => false],
            'sensorC' => ['traffic' => false],
            'sensorD' => ['traffic' => false],
            'sensor_locations' => $sensorLocations
        ];

       try {
    $client = new Client();
    // Update the CONFIG node (persistent)
    $client->put(
        "https://management-6d07b-default-rtdb.firebaseio.com/traffic_config/sensor_locations.json",
        ['json' => $sensorLocations]
    );
} catch (\Exception $e) {
    Log::error('Firebase sync failed: ' . $e->getMessage());
}

        return redirect()->back()->with('success', 'Sensor ' . $id . ' updated!');
    }
}