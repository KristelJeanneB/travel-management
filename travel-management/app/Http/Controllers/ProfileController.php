<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('settings', ['user' => Auth::user()]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();

        $data = $request->only(['name', 'email', 'theme']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()->route('settings')->with('success', 'Profile updated!');
    }

    public function toggleTheme()
    {
        $user = Auth::user();
        $user->theme = $user->theme === 'light' ? 'dark' : 'light';
        $user->save();
        return back();
    }
}
