<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\KosProfileController;
use App\Http\Controllers\Admin\MaintenanceRequestController as AdminMaintenanceRequestController;
use App\Http\Controllers\Admin\OperationalExpenseController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UtilityBillController as AdminUtilityBillController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\BookingController as PublicBookingController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Public\RoomController as PublicRoomController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\MaintenanceRequestController as TenantMaintenanceRequestController;
use App\Http\Controllers\Tenant\PaymentProofController;
use App\Http\Controllers\Tenant\ProfileController as TenantProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomeController::class)->name('home');
Route::get('/rooms', [PublicRoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room:slug}', [PublicRoomController::class, 'show'])->name('rooms.show');

Route::middleware('auth')->group(function () {
    Route::get('/rooms/{room:slug}/book', [PublicBookingController::class, 'create'])->name('rooms.book');
    Route::post('/rooms/{room:slug}/book', [PublicBookingController::class, 'store'])->name('rooms.book.store');
});

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
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::match(['get', 'put', 'patch'], '/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
        Route::match(['get', 'put', 'patch'], '/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
        Route::resource('facilities', FacilityController::class)->except('show');
        Route::get('payments/{payment}/proof', [PaymentController::class, 'proof'])->name('payments.proof');
        Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
        Route::get('payments/export-csv', [PaymentController::class, 'exportCsv'])->name('payments.export-csv');
        Route::get('payments/{payment}/review', [PaymentController::class, 'review'])->name('payments.review');
        Route::match(['put', 'patch'], 'payments/{payment}/review', [PaymentController::class, 'updateReview'])->name('payments.review.update');
        Route::resource('payments', PaymentController::class)->except('show');
        Route::get('rooms/export', [RoomController::class, 'export'])->name('rooms.export');
        Route::get('rooms/export-csv', [RoomController::class, 'exportCsv'])->name('rooms.export-csv');
        Route::get('rooms/{room}/images', [RoomImageController::class, 'index'])->name('rooms.images.index');
        Route::post('rooms/{room}/images', [RoomImageController::class, 'store'])->name('rooms.images.store');
        Route::delete('rooms/{room}/images/{image}', [RoomImageController::class, 'destroy'])->name('rooms.images.destroy');
        Route::resource('rooms', RoomController::class)->except('show');
        Route::get('tenants/export', [AdminTenantController::class, 'export'])->name('tenants.export');
        Route::get('tenants/export-csv', [AdminTenantController::class, 'exportCsv'])->name('tenants.export-csv');
        Route::get('tenants/history', [AdminTenantController::class, 'history'])->name('tenants.history');
        Route::get('tenants/{tenant}/checkout', [AdminTenantController::class, 'checkout'])->name('tenants.checkout');
        Route::match(['put', 'patch'], 'tenants/{tenant}/checkout', [AdminTenantController::class, 'processCheckout'])->name('tenants.checkout.update');
        Route::get('tenants/{tenant}/transfer', [AdminTenantController::class, 'transfer'])->name('tenants.transfer');
        Route::match(['put', 'patch'], 'tenants/{tenant}/transfer', [AdminTenantController::class, 'processTransfer'])->name('tenants.transfer.update');
        Route::get('tenants/assign', [AdminTenantController::class, 'createExisting'])->name('tenants.create-existing');
        Route::post('tenants/assign', [AdminTenantController::class, 'storeExisting'])->name('tenants.store-existing');
        Route::resource('tenants', AdminTenantController::class)->except('show');
        Route::get('/profile', [KosProfileController::class, 'edit'])->name('kos-profile.edit');
        Route::match(['put', 'patch'], '/profile', [KosProfileController::class, 'update'])->name('kos-profile.update');
        Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('utility-bills/export', [AdminUtilityBillController::class, 'export'])->name('utility-bills.export');
        Route::get('utility-bills/export-csv', [AdminUtilityBillController::class, 'exportCsv'])->name('utility-bills.export-csv');
        Route::resource('utility-bills', AdminUtilityBillController::class)->except('show');
        Route::resource('maintenance-requests', AdminMaintenanceRequestController::class)->except('create', 'store', 'show');
        Route::get('operational-expenses/export', [OperationalExpenseController::class, 'export'])->name('operational-expenses.export');
        Route::get('operational-expenses/export-csv', [OperationalExpenseController::class, 'exportCsv'])->name('operational-expenses.export-csv');
        Route::resource('operational-expenses', OperationalExpenseController::class)->except('show');
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    });

    Route::prefix('tenant')->name('tenant.')->middleware('tenant')->group(function () {
        Route::get('/dashboard', TenantDashboardController::class)->name('dashboard');
        Route::post('payments/{payment}/proof', [PaymentProofController::class, 'store'])->name('payments.proof.store');
        Route::get('maintenance-requests', [TenantMaintenanceRequestController::class, 'index'])->name('maintenance-requests.index');
        Route::get('maintenance-requests/create', [TenantMaintenanceRequestController::class, 'create'])->name('maintenance-requests.create');
        Route::post('maintenance-requests', [TenantMaintenanceRequestController::class, 'store'])->name('maintenance-requests.store');
        Route::get('profile', [TenantProfileController::class, 'edit'])->name('profile.edit');
        Route::match(['put', 'patch'], 'profile', [TenantProfileController::class, 'update'])->name('profile.update');
    });
});
