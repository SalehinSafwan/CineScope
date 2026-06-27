<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
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

// user reviews belong here so people can rate movies from the list
Route::middleware(['auth', 'role:user'])->group(function () {
	Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
	Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// only admins should touch the movie CRUD pages
Route::middleware(['auth', 'role:admin'])->group(function () {
	Route::resource('movies', MovieController::class)->except(['show']);
});
