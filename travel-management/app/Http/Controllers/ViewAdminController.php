<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewAdminController extends Controller
{
    public function index()
    {
        return view('admin.view');
    }
}