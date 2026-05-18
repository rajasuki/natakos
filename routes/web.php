<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\KosProfileController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Public\RoomController as PublicRoomController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\PaymentProofController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomeController::class)->name('home');
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room:slug}', [PublicRoomController::class, 'show'])->name('rooms.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [AuthenticatedSessionController::class, 'dashboard'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::resource('facilities', FacilityController::class)->except('show');
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/kos-profile', [KosProfileController::class, 'edit'])->name('kos-profile.edit');
            Route::match(['put', 'patch'], '/kos-profile', [KosProfileController::class, 'update'])->name('kos-profile.update');
        });
        Route::get('payments/{payment}/proof', [PaymentController::class, 'proof'])->name('payments.proof');
        Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
        Route::get('payments/{payment}/review', [PaymentController::class, 'review'])->name('payments.review');
        Route::match(['put', 'patch'], 'payments/{payment}/review', [PaymentController::class, 'updateReview'])->name('payments.review.update');
        Route::resource('payments', PaymentController::class)->except('show');
        Route::get('rooms/export', [RoomController::class, 'export'])->name('rooms.export');
        Route::get('rooms/{room}/images', [RoomImageController::class, 'index'])->name('rooms.images.index');
        Route::post('rooms/{room}/images', [RoomImageController::class, 'store'])->name('rooms.images.store');
        Route::delete('rooms/{room}/images/{image}', [RoomImageController::class, 'destroy'])->name('rooms.images.destroy');
        Route::resource('rooms', RoomController::class)->except('show');
        Route::get('tenants/export', [AdminTenantController::class, 'export'])->name('tenants.export');
        Route::get('tenants/history', [AdminTenantController::class, 'history'])->name('tenants.history');
        Route::get('tenants/{tenant}/checkout', [AdminTenantController::class, 'checkout'])->name('tenants.checkout');
        Route::match(['put', 'patch'], 'tenants/{tenant}/checkout', [AdminTenantController::class, 'processCheckout'])->name('tenants.checkout.update');
        Route::get('tenants/assign', [AdminTenantController::class, 'createExisting'])->name('tenants.create-existing');
        Route::post('tenants/assign', [AdminTenantController::class, 'storeExisting'])->name('tenants.store-existing');
        Route::resource('tenants', AdminTenantController::class)->except('show');
    });

    Route::prefix('tenant')->name('tenant.')->middleware('tenant')->group(function () {
        Route::get('/dashboard', TenantDashboardController::class)->name('dashboard');
        Route::post('payments/{payment}/proof', [PaymentProofController::class, 'store'])->name('payments.proof.store');
    });
});
