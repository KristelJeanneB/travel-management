<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{

    public function index()
    {

        for ($i = 1; $i <= 4; $i++) {
            Sensor::firstOrCreate(
                ['id' => $i],
                [
                    'name' => 'Sensor ' . $i,
                    'latitude' => 0.0,
                    'longitude' => 0.0,
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
        'longitude' => 'required|numeric|min:-180|max:180', // ✅ colon, not equals
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
        $client = new \GuzzleHttp\Client();
        $client->put(
            "https://management-6d07b-default-rtdb.firebaseio.com/traffic_logs.json", 
            ['json' => $firebaseData]
        );
    } catch (\Exception $e) {
        Log::error('Firebase sync failed: ' . $e->getMessage());
    }

    return redirect()->back()->with('success', 'Sensor ' . $id . ' updated!');
}

}