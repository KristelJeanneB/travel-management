<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; 

class AlertsController extends Controller
{
    public function index()
    {
        $newUsers = User::where('created_at', '>=', now()->subDays(7))->get();
        $allUsers = User::all();
        $failedAttempts = collect(); 

        return view('admin.alerts', compact('newUsers', 'allUsers', 'failedAttempts'));
    }
}