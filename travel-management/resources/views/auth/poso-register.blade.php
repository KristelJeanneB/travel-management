@extends('layouts.app')
@section('content')
<div style="max-width: 500px; margin: 3rem auto; padding: 0 1rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1>👮 Poso Personnel Registration</h1>
        <p style="color: #666;">Official use only. Credentials will be verified.</p>
    </div>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 1rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('poso.register') }}">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 4px;">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 4px;">Official Email (@poso.gov.ph)</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 4px;">Password</label>
            <input type="password" name="password" required
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 4px;">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" style="width: 100%; padding: 10px; background: #0d6efd; color: white; border: none; border-radius: 4px; font-size: 16px;">
            Register as Poso Personnel
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="{{ route('login') }}" style="color: #0d6efd; text-decoration: none;">← Back to Login</a>
    </div>
</div>
@endsection