@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="form-shell" style="max-width: 560px; margin: 0 auto;">
        <h1 style="margin-top: 0;">Login</h1>
        <p class="tiny">This form works for both normal users and admins.</p>

        {{-- simple login form, because we do not need a giant one right now --}}
        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" value="{{ old('email') }}" type="email" required autofocus>
                @error('email')<div class="tiny">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                @error('password')<div class="tiny">{{ $message }}</div>@enderror
            </div>

            <div class="actions">
                <button class="btn" type="submit">Login</button>
                <a class="btn-link" href="{{ route('register') }}">Go to signup</a>
            </div>
        </form>
    </div>
@endsection