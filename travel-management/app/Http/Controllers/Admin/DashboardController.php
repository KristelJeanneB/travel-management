<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function deleteUser($id)
{
    $user = User::findOrFail($id);

    if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot delete the last admin.'
        ]);
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'User deleted successfully.'
    ]);
}
}
