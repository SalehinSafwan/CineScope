@extends('layouts.app')

@section('title', 'Signup')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh; padding: 2rem 1rem; font-family: system-ui, -apple-system, sans-serif;">
    <div style="width: 100%; max-width: 440px; background: rgba(22, 28, 45, 0.6); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 2.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 2.25rem;">
            <h1 style="margin: 0 0 0.5rem 0; font-size: 2rem; font-weight: 700; color: #ffffff; letter-spacing: -0.025em;">Create Account</h1>
            <p style="margin: 0; font-size: 0.875rem; color: #94a3b8;">Sign up to explore the CineScope universe.</p>
        </div>

        <form action="{{ route('register.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf

            <!-- Name Input -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="name" style="font-size: 0.875rem; font-weight: 500; color: #cbd5e1;">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" type="text" required autocomplete="name"
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; font-size: 0.95rem; color: #ffffff; outline: none; box-sizing: border-box; transition: all 0.2s;">
                @error('name')
                    <div style="font-size: 0.75rem; color: #f87171; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Input -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="email" style="font-size: 0.875rem; font-weight: 500; color: #cbd5e1;">Email Address</label>
                <input id="email" name="email" value="{{ old('email') }}" type="email" required autocomplete="email"
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; font-size: 0.95rem; color: #ffffff; outline: none; box-sizing: border-box; transition: all 0.2s;">
                @error('email')
                    <div style="font-size: 0.75rem; color: #f87171; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="password" style="font-size: 0.875rem; font-weight: 500; color: #cbd5e1;">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; font-size: 0.95rem; color: #ffffff; outline: none; box-sizing: border-box; transition: all 0.2s;">
                @error('password')
                    <div style="font-size: 0.75rem; color: #f87171; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Input -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label for="password_confirmation" style="font-size: 0.875rem; font-weight: 500; color: #cbd5e1;">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; font-size: 0.95rem; color: #ffffff; outline: none; box-sizing: border-box; transition: all 0.2s;">
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                <button type="submit" 
                        style="width: 100%; padding: 0.875rem; background: #f59e0b; color: #0f172a; border: none; border-radius: 8px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);">
                    Create Account
                </button>
                
                <a href="{{ route('login') }}" 
                   style="text-align: center; font-size: 0.875rem; color: #94a3b8; text-decoration: none; font-weight: 500; transition: color 0.2s; margin-top: 0.25rem;">
                    Already have an account? <span style="color: #f59e0b; text-decoration: underline;">Log in</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection