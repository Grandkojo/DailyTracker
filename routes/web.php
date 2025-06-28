<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminActivityController;

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/activities', [DashboardController::class, 'getActivitiesByDate'])->name('dashboard.activities');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // Activities
    Route::resource('activities', ActivityController::class);
    Route::post('/activities/{activity}/status', [ActivityController::class, 'updateStatus'])->name('activities.update-status');
    Route::get('/activities/{activity}/updates', [ActivityController::class, 'getUpdates'])->name('activities.updates');

    // Admin
    Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('activities', AdminActivityController::class);
        Route::post('/activities/{activity}/status', [AdminActivityController::class, 'updateStatus'])->name('activities.update-status');
        Route::get('/activities/{activity}/updates', [AdminActivityController::class, 'getUpdates'])->name('activities.updates');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/activities', [AdminDashboardController::class, 'getActivitiesByDate'])->name('dashboard.activities');
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/activity', [ReportController::class, 'activityReport'])->name('reports.activity');
        Route::get('/reports/performance', [ReportController::class, 'userPerformance'])->name('reports.performance');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/reports/export-performance', [ReportController::class, 'exportPerformance'])->name('reports.export-performance');
    });
    
    // Redirect root to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});
