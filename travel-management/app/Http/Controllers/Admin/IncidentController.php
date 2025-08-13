<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;

class IncidentController extends Controller
{
    public function show($id)
    {
        $incident = Incident::findOrFail($id);
        return view('admin.incident.show', compact('incidents'));
    }
}