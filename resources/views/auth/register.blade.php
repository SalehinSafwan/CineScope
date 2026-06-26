@extends('layouts.app')

@section('title', 'Signup')

@section('content')
    <div class="form-shell" style="max-width: 560px; margin: 0 auto;">
        <h1 style="margin-top: 0;">Signup</h1>
        <p class="tiny">Pick user if you only want the homepage, or admin if you need CRUD access.</p>

        {{-- yeah, the role is picked here so we can make user and admin accounts from one form --}}
        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" type="text" required>
                @error('name')<div class="tiny">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" value="{{ old('email') }}" type="email" required>
                @error('email')<div class="tiny">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                @error('password')<div class="tiny">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <div class="field">
                <label>Account type</label>
                <label><input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }}> User</label>
                <label><input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}> Admin</label>
                @error('role')<div class="tiny">{{ $message }}</div>@enderror
            </div>

            <div class="actions">
                <button class="btn" type="submit">Create account</button>
                <a class="btn-link" href="{{ route('login') }}">Go to login</a>
            </div>
        </form>
    </div>
@endsection