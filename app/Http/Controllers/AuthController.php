<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // this opens the login form, nothing fancy
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // this checks the email and password and then logs the person in
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return back()
                ->withErrors(['email' => 'Wrong email or password.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // admins go to the CRUD area, users go back to the home page
        return redirect()->route(Auth::user()->role === 'admin' ? 'movies.index' : 'home');
    }

    // this opens the signup form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // this makes a new user or admin account
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // same idea here, admin gets the admin pages and user gets the home page
        return redirect()->route($user->role === 'admin' ? 'movies.index' : 'home');
    }

    // this is the logout button route, so we wipe the session and go home
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}