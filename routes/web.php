<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RuasJalanController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('frontend.dashboard');
})->name('frontend.dashboard');


Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard/ruasjalan', [DashboardController::class, 'getRuasJalan'])->name('dashboard.ruasjalan');


Route::get('/form', function () {
    return view('map.form');
})->name('form');

Route::post('/form', [MapController::class, 'store'])->name('ruasjalan.store');
Route::post('/polyline/store', [RuasJalanController::class, 'store'])->name('polyline.store');


