<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Opens the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Checks the email and password and logs the user in
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

        // Admins go to the CRUD area, users go back to the home page
        return redirect()->route(Auth::user()->role === 'admin' ? 'movies.index' : 'home');
    }

    // Opens the signup form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Creates a standard user account automatically
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // 'role' is hardcoded here to 'user' for safety, eliminating form manipulation risks
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => 'user', 
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // New registrations always redirect to the home page since they are always users
        return redirect()->route('home');
    }

    // Clear the session out and log out
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}