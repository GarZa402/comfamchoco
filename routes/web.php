<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PoliticaController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('password/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'forgotPassword'])->name('password.email');

Route::get('password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Employee routes
    // Usuarios autenticados pueden crear su propia ficha de empleado
    // IMPORTANTE: Definir create/store ANTES del resource para evitar que 'empleados/{empleado}' capture 'create'
    Route::get('empleados/create', [EmpleadoController::class, 'create'])
        ->middleware(['auth'])
        ->name('empleados.create');
    Route::post('empleados', [EmpleadoController::class, 'store'])
        ->middleware(['auth'])
        ->name('empleados.store');

    // RRHH puede gestionar empleados (excepto crear y guardar)
    Route::resource('empleados', EmpleadoController::class)
        ->middleware(['auth', 'role:rrhh'])
        ->except(['create', 'store']);
    
    // Permission routes
    Route::resource('permisos', PermisoController::class)->middleware(['auth']);
    
    // Policy routes (only for RRHH)
    Route::resource('politicas', PoliticaController::class)->middleware(['auth', 'role:rrhh']);
    
    // Employee-specific permission routes
    Route::get('mis-permisos', [PermisoController::class, 'misPermisos'])->name('mis.permisos');
    Route::post('mis-permisos/{permiso}/aprobar', [PermisoController::class, 'aprobar'])->name('mis.permisos.aprobar');
    Route::post('mis-permisos/{permiso}/rechazar', [PermisoController::class, 'rechazar'])->name('mis.permisos.rechazar');
    
    // Calendario compartido de ausencias
    Route::get('calendario', [App\Http\Controllers\CalendarioController::class, 'index'])->name('calendario.index');
});

// Admin routes (only for RRHH)
Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'adminDashboard'])->middleware('role:rrhh')->name('admin.dashboard');
    
    // Reports
    Route::get('reportes/permisos', [PermisoController::class, 'reportePermisos'])->middleware('role:rrhh')->name('reportes.permisos');
    Route::get('reportes/empleados', [EmpleadoController::class, 'reporteEmpleados'])->middleware('role:rrhh')->name('reportes.empleados');
});
