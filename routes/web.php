<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

// this is the real home page, so the welcome screen is back again
Route::get('/', [HomeController::class, 'index'])->name('home');

// yep, this one also opens the same welcome page, just in case you want it directly
Route::get('/welcome', [HomeController::class, 'index'])->name('welcome');

// guest stuff is only for people who are not logged in yet
Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
	Route::post('/login', [AuthController::class, 'login'])->name('login.store');

	Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
	Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// logout is tiny but it still needs a route
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// only admins should touch the movie CRUD pages
Route::middleware(['auth', 'role:admin'])->group(function () {
	Route::resource('movies', MovieController::class)->except(['show']);
});
