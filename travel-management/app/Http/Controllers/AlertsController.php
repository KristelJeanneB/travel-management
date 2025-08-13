<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class AlertsController extends Controller
{
public function index()
{
    $alerts = Incident::all();
    return view('admin.alerts', compact('alerts'));
}
}