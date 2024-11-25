<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StatusUpdateController;
use Illuminate\Support\Facades\Route;

// Public routes for guests only
Route::middleware('guest')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    Route::view('/login', 'auth.login')->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/reservation-form', [ReservationController::class, 'reservationForm'])->name('reservation-form.form');
    Route::post('/reservation-form/store', [ReservationController::class, 'reservationStore'])->name('reservation-form.store');
    Route::get('/check-status', [HomeController::class, 'showCheckStatusForm'])->name('check.status.form');
    Route::post('/check-status', [HomeController::class, 'checkStatus'])->name('check.status');
});

// Protected routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('sales', SaleController::class);
    Route::get('/sale/sales', [SaleController::class, 'saleIndex'])->name('sales.list');
    Route::get('/sale/sales/{saleId}', [SaleController::class, 'showSaleDetails'])->name('sales.showDetails');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/pending', [ReservationController::class, 'pendingIndex'])->name('reservations.pending');
    Route::get('/reservations/processing', [ReservationController::class, 'processingIndex'])->name('reservations.processing');
    Route::get('/reservations/ready-to-pickup', [ReservationController::class, 'readyToPickUpIndex'])->name('reservations.ready-to-pickup');
    Route::get('/reservations/complete', [ReservationController::class, 'completeIndex'])->name('reservations.complete');
    Route::get('/reservations/all', [ReservationController::class, 'allIndex'])->name('reservations.all');

    Route::patch('/reservations/{order}/process', [StatusUpdateController::class, 'process'])->name('reservations.process');
    Route::patch('/reservations/{order}/ready-to-pickup', [StatusUpdateController::class, 'readyToPickup'])->name('reservations.ready-to-pickup.update');
    Route::patch('/reservations/{order}/complete', [StatusUpdateController::class, 'complete'])->name('reservations.complete.update');

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add-item', [PosController::class, 'addItem'])->name('pos.add-item');
    Route::delete('/pos/remove-item', [PosController::class, 'removeItem'])->name('pos.remove-item');
    Route::post('/pos/apply-discount', [PosController::class, 'applyDiscount'])->name('pos.apply-discount');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{sale_id}', [PosController::class, 'receipt'])->name('pos.receipt');

    Route::get('products/{product}/inventory/edit', [InventoryController::class, 'edit'])->name('inventories.edit');
    Route::put('/products/{product}/inventory', [InventoryController::class, 'update'])->name('inventories.update');
});
