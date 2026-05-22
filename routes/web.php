<?php

use App\Http\Controllers\Admin\AdminEnrollmentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tentor\DashboardController as TentorDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/tentor/dashboard', [TentorDashboardController::class, 'index'])
        ->middleware('role:tentor')
        ->name('tentor.dashboard');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])
            ->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('users.store');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])
            ->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');

        Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])
            ->name('enrollments.index');
        Route::post('/enrollments', [AdminEnrollmentController::class, 'store'])
            ->name('enrollments.store');
        Route::delete('/enrollments/{id}', [AdminEnrollmentController::class, 'destroy'])
            ->name('enrollments.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
