<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect dashboard berdasarkan role
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'userDestroy'])->name('users.destroy');

    // Kelola pendaftaran
    Route::get('/pendaftarans', [AdminController::class, 'pendaftarans'])->name('pendaftarans');
    Route::get('/pendaftarans/{pendaftaran}', [AdminController::class, 'pendaftaranShow'])->name('pendaftarans.show');
    Route::get('/pendaftarans/{pendaftaran}/edit', [AdminController::class, 'pendaftaranEdit'])->name('pendaftarans.edit');
    Route::put('/pendaftarans/{pendaftaran}', [AdminController::class, 'pendaftaranUpdate'])->name('pendaftarans.update');
    Route::delete('/pendaftarans/{pendaftaran}', [AdminController::class, 'pendaftaranDestroy'])->name('pendaftarans.destroy');
});

// Mahasiswa routes
Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [PendaftaranController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftaran/buat', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/detail', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::get('/pendaftaran/pdf', [PendaftaranController::class, 'exportPdf'])->name('pendaftaran.pdf');
});

// API
Route::get('/api/kabupatens/{provinsiId}', [PendaftaranController::class, 'kabupatenByProvinsi'])->middleware('auth');
