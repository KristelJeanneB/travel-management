<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminIncidentController extends Controller
{
    // This can be used if you want a full page listing (optional)
    public function index()
    {
        $incidents = DB::table('incidents')->orderBy('created_at', 'desc')->get();

        return view('admin.incident', compact('incidents'));
    }

    // This is the API endpoint to fetch incidents for the modal (used in JS fetch)
    public function fetchIncidents()
    {
        $incidents = DB::table('incidents')->orderBy('created_at', 'desc')->get();

        return response()->json($incidents);
    }
}
