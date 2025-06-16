<?php

use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Admin Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Users Management
Route::resource('users', UserController::class);

// Organizations Management
Route::resource('organizations', OrganizationController::class);

// Couriers Management
Route::resource('couriers', CourierController::class);

// Invoices (View Only)
Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download-pdf');
Route::get('invoices/{invoice}/download-image', [InvoiceController::class, 'downloadImage'])->name('invoices.download-image');