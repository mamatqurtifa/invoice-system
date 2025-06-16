<?php

use App\Http\Controllers\Organization\CustomerController;
use App\Http\Controllers\Organization\DashboardController;
use App\Http\Controllers\Organization\DiscountController;
use App\Http\Controllers\Organization\InvoiceController;
use App\Http\Controllers\Organization\InvoiceTemplateController;
use App\Http\Controllers\Organization\OrderController;
use App\Http\Controllers\Organization\PaymentMethodController;
use App\Http\Controllers\Organization\ProductController;
use App\Http\Controllers\Organization\ProfileController;
use App\Http\Controllers\Organization\ProjectController;
use App\Http\Controllers\Organization\ReportController;
use App\Http\Controllers\Organization\TaxController;
use Illuminate\Support\Facades\Route;

// Organization Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Organization Profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

// Projects Management
Route::resource('projects', ProjectController::class);
Route::get('projects/{project}/add-products', [ProjectController::class, 'addProducts'])->name('projects.add-products');
Route::post('projects/{project}/store-products', [ProjectController::class, 'storeProducts'])->name('projects.store-products');
Route::delete('projects/{project}/products/{projectProduct}', [ProjectController::class, 'removeProduct'])->name('projects.remove-product');

// Products Management
Route::resource('products', ProductController::class);

// Customers Management
Route::resource('customers', CustomerController::class);
Route::get('customers/import', [CustomerController::class, 'importForm'])->name('customers.import-form');
Route::post('customers/import', [CustomerController::class, 'import'])->name('customers.import');

// Payment Methods Management
Route::resource('payment-methods', PaymentMethodController::class);
Route::patch('payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');

// Invoice Templates Management
Route::resource('invoice-templates', InvoiceTemplateController::class);
Route::get('invoice-templates/{invoiceTemplate}/preview', [InvoiceTemplateController::class, 'preview'])->name('invoice-templates.preview');
Route::patch('invoice-templates/{invoiceTemplate}/set-default', [InvoiceTemplateController::class, 'setDefault'])->name('invoice-templates.set-default');

// Orders Management
Route::resource('orders', OrderController::class);
Route::get('orders/projects/{project}/products', [OrderController::class, 'getProjectProducts'])->name('orders.get-project-products');
Route::put('orders/{order}/items', [OrderController::class, 'updateItems'])->name('orders.update-items');
Route::patch('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');

// Invoices Management
Route::resource('invoices', InvoiceController::class)->only(['index', 'show', 'edit', 'update']);
Route::get('invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download-pdf');
Route::get('invoices/{invoice}/download-image', [InvoiceController::class, 'downloadImage'])->name('invoices.download-image');
Route::post('invoices/download-multiple', [InvoiceController::class, 'downloadMultiple'])->name('invoices.download-multiple');
Route::get('invoices/projects/{project}/download', [InvoiceController::class, 'downloadProjectInvoices'])->name('invoices.download-project');

// Discounts Management
Route::resource('discounts', DiscountController::class);

// Taxes Management
Route::resource('taxes', TaxController::class);

// Reports
Route::resource('reports', ReportController::class)->except(['edit', 'update', 'destroy']);
Route::get('reports/{report}/download-pdf', [ReportController::class, 'downloadPdf'])->name('reports.download-pdf');
Route::get('reports/sales-summary', [ReportController::class, 'salesSummary'])->name('reports.sales-summary');