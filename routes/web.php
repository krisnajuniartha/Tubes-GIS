<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
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


Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard/ruasjalan', [DashboardController::class, 'getRuasJalan'])->name('dashboard.ruasjalan');


Route::post('/ruasjalan', [RuasJalanController::class, 'store'])->name('ruasjalan.store');


// Route::group(['middleware' => ['web', 'redirect.unauthenticated']], function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });



// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// });
