<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('dashboard.index');


Route::resource('products', ProductController::class);
Route::resource('expenses', ExpenseController::class);
