<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Public\RoomController as PublicRoomController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomeController::class)->name('home');
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room:slug}', [PublicRoomController::class, 'show'])->name('rooms.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [AuthenticatedSessionController::class, 'dashboard'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::resource('facilities', FacilityController::class)->except('show');
        Route::resource('payments', PaymentController::class)->except('show');
        Route::resource('rooms', RoomController::class)->except('show');
        Route::resource('tenants', AdminTenantController::class)->except('show');
    });

    Route::prefix('tenant')->name('tenant.')->middleware('tenant')->group(function () {
        Route::get('/dashboard', TenantDashboardController::class)->name('dashboard');
    });
});
