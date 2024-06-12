<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('/register', function () {
//     return view('auth.register');
// });

// Route::get('/dashboard', function () {
//     return view('frontend.dashboard');
// })->name('frontend.dashboard');

// //auth controller
// Route::post('/register', [AuthController::class, 'register']);
// // Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//polyline


// Route::get('/dashboard/ruasjalan', [DashboardController::class, 'getRuasJalan'])->name('dashboard.ruasjalan');


// //form
// Route::get('/form', [MapController::class, 'create'])->name('form.create');
// Route::post('/form/store', [MapController::class, 'store'])->name('form.store');
// Route::post('/form/store', [MapController::class, 'store'])->name('form.store');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/landing-page', function () {
    return view('frontend.dashboard');
});

Route::get('/lala', function () {
    return view('frontend.dashboard');
});

Route::post('/register-user', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', [MainController::class, 'view']);
Route::get('/add-ruasjalan', [MainController::class, 'showAddRuasJalan']);
Route::get('/edit-ruasjalan', [MainController::class, 'getRuasJalanForEdit'])->name('getRuasJalanForEdit');
Route::put('/ruasjalan/update/{id}', [MainController::class, 'updateRuasJalan'])->name('ruasjalan.update');
Route::delete('/ruasjalan/delete/{id}', [MainController::class, 'deleteRuasJalan'])->name('ruasjalan.delete');;
Route::post('/submit-ruasjalan', [MainController::class, 'submitRuasJalan'])->name('submitRuasJalan');


