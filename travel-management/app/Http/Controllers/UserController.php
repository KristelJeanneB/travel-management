<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function migrateUsers(FirebaseService $firebaseService)
    {
        $firebase = $firebaseService->getDatabase();
        $usersRef = $firebase->getReference('users');

        $users = User::all();

        foreach ($users as $user) {
            $usersRef->push([
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->toDateTimeString(),
            ]);
        }

        return "Users migrated to Firebase!";
    }
}
