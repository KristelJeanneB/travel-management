@extends('layouts.app')

@section('title', 'Account Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
    $previous = request()->get('previous', 'home');
    $backRoute = $previous === 'map' ? route('map') : route('home');
@endphp

<div class="settings-wrapper">

    <nav class="settings-nav">
        <a href="{{ $backRoute }}" class="nav-back" aria-label="Go back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Account Settings</h1>
        <form action="{{ route('settings.toggleTheme') }}" method="POST" class="theme-toggle-form" aria-label="Toggle Theme">
            @csrf
            <button class="theme-toggle-btn" type="submit" title="Toggle Theme">
                <i class="fas fa-adjust"></i>
            </button>
        </form>
    </nav>

    <main class="settings-panel" role="main" aria-labelledby="account-heading">

        <!-- My Account -->
        <section class="setting-section" aria-labelledby="account-heading">
            <h2 id="account-heading">My Account</h2>
            <div class="form-container">
                <div class="avatar-preview">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar of {{ $user->name }}">
                    @else
                        <img src="{{ asset('images/default.webp') }}" alt="Default Avatar">
                    @endif
                </div>
                <div class="user-info">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Member Since:</strong> {{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </section>

        <!-- Preferences -->
        <section class="setting-section" aria-label="User preferences">
            <h2>Preferences</h2>
            <div class="settings-buttons-group">
                <button class="setting-button" disabled><i class="fas fa-bell"></i> Notifications</button>
                <button class="setting-button" disabled><i class="fas fa-volume-up"></i> Sounds & Alerts</button>
                <button class="setting-button" disabled><i class="fas fa-user-shield"></i> Privacy</button>
            </div>
        </section>

        <!-- Account Controls -->
        <section class="setting-section" aria-label="Account controls">
            <h2>Account Controls</h2>
            <div class="settings-buttons-group">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="setting-button logout-button">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

                <button class="setting-button" type="button" onclick="document.getElementById('editModal').style.display='block'">
                    <i class="fas fa-user-cog"></i> Edit Profile
                </button>
            </div>
        </section>
    </main>
</div>

<!-- Edit Profile Modal -->
<div id="editModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="editProfileHeading">
    <div class="modal-content">
        <button aria-label="Close modal" class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</button>
        <h2 id="editProfileHeading">Edit Profile</h2>
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>

            <label for="avatar">Avatar</label>
            <input id="avatar" type="file" name="avatar" accept="image/*">

            <label for="theme">Theme</label>
            <select id="theme" name="theme" required>
                <option value="light" {{ $user->theme === 'light' ? 'selected' : '' }}>Light</option>
                <option value="dark" {{ $user->theme === 'dark' ? 'selected' : '' }}>Dark</option>
            </select>

            <button type="submit" class="setting-button save-btn">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<script>
// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

@endsection
