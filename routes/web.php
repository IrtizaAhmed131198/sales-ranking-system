<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\SalesController;

use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NoticeController;

// Authentication routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('targets', TargetController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('benchmarks', BenchmarkController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('notices', NoticeController::class);
});
