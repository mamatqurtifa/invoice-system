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
Route::patch('payment-methods/{paymentMethod}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');

// Invoice Templates Management
Route::resource('invoice-templates', InvoiceTemplateController::class);
Route::get('invoice-templates/{invoiceTemplate}/preview', [InvoiceTemplateController::class, 'preview'])->name('invoice-templates.preview');
Route::patch('invoice-templates/{invoiceTemplate}/set-default', [InvoiceTemplateController::class, 'setDefault'])->name('invoice-templates.set-default');
Route::get('invoice-templates/{invoiceTemplate}/duplicate', [InvoiceTemplateController::class, 'duplicate'])->name('invoice-templates.duplicate');
Route::get('invoice-templates/render-preview/{template}', [InvoiceTemplateController::class, 'renderPreview'])->name('invoice-templates.render-preview');

// Orders Management
Route::resource('orders', OrderController::class);
Route::get('orders/project/{project}/products', [OrderController::class, 'projectProducts'])->name('orders.project-products');
Route::patch('orders/{order}/mark-completed', [OrderController::class, 'markCompleted'])->name('orders.mark-completed');
Route::patch('orders/{order}/mark-cancelled', [OrderController::class, 'markCancelled'])->name('orders.mark-cancelled');
Route::get('orders/{order}/duplicate', [OrderController::class, 'duplicate'])->name('orders.duplicate');

// Invoices Management
Route::resource('invoices', InvoiceController::class)->except(['destroy']);
Route::get('invoices/create/{order}', [InvoiceController::class, 'createFromOrder'])->name('invoices.create-from-order');
Route::get('invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download-pdf');
Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
Route::get('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
Route::patch('invoices/{invoice}/mark-sent', [InvoiceController::class, 'markSent'])->name('invoices.mark-sent');
Route::patch('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
Route::patch('invoices/{invoice}/mark-cancelled', [InvoiceController::class, 'markCancelled'])->name('invoices.mark-cancelled');
Route::get('invoices/export/{format?}', [InvoiceController::class, 'export'])->name('invoices.export');

// Discounts Management
Route::resource('discounts', DiscountController::class);
Route::patch('discounts/{discount}/toggle', [DiscountController::class, 'toggle'])->name('discounts.toggle');
Route::get('discounts/{discount}/duplicate', [DiscountController::class, 'duplicate'])->name('discounts.duplicate');

// Taxes Management
Route::resource('taxes', TaxController::class);
Route::patch('taxes/{tax}/toggle', [TaxController::class, 'toggle'])->name('taxes.toggle');
Route::patch('taxes/{tax}/set-default', [TaxController::class, 'setDefault'])->name('taxes.set-default');
Route::patch('taxes/update-defaults', [TaxController::class, 'updateDefaults'])->name('taxes.update-defaults');

// Reports
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::get('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::get('reports/show/{report}', [ReportController::class, 'show'])->name('reports.show');
Route::get('reports/saved', [ReportController::class, 'saved'])->name('reports.saved');
Route::get('reports/run/{report}', [ReportController::class, 'run'])->name('reports.run');
Route::get('reports/edit/{report}', [ReportController::class, 'edit'])->name('reports.edit');
Route::post('reports', [ReportController::class, 'store'])->name('reports.store');
Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
Route::get('reports/duplicate/{report}', [ReportController::class, 'duplicate'])->name('reports.duplicate');
Route::get('reports/schedule/{report}', [ReportController::class, 'schedule'])->name('reports.schedule');
Route::get('reports/export/{id}/{format}', [ReportController::class, 'export'])->name('reports.export');

// Sales Reports
Route::get('reports/sales-summary', [ReportController::class, 'salesSummary'])->name('reports.sales-summary');
Route::get('reports/sales-by-product', [ReportController::class, 'salesByProduct'])->name('reports.sales-by-product');
Route::get('reports/sales-by-customer', [ReportController::class, 'salesByCustomer'])->name('reports.sales-by-customer');
Route::get('reports/sales-by-project', [ReportController::class, 'salesByProject'])->name('reports.sales-by-project');
Route::get('reports/export-sales-summary/{format}', [ReportController::class, 'exportSalesSummary'])->name('reports.export-sales-summary');

// Financial Reports
Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
Route::get('reports/tax', [ReportController::class, 'tax'])->name('reports.tax');
Route::get('reports/payment-collection', [ReportController::class, 'paymentCollection'])->name('reports.payment-collection');
Route::get('reports/outstanding-invoices', [ReportController::class, 'outstandingInvoices'])->name('reports.outstanding-invoices');

// Customer Reports
Route::get('reports/customer-acquisition', [ReportController::class, 'customerAcquisition'])->name('reports.customer-acquisition');
Route::get('reports/customer-retention', [ReportController::class, 'customerRetention'])->name('reports.customer-retention');
Route::get('reports/top-customers', [ReportController::class, 'topCustomers'])->name('reports.top-customers');

// Inventory Reports
Route::get('reports/stock-levels', [ReportController::class, 'stockLevels'])->name('reports.stock-levels');
Route::get('reports/product-performance', [ReportController::class, 'productPerformance'])->name('reports.product-performance');
Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');

// Project Reports
Route::get('reports/project-performance', [ReportController::class, 'projectPerformance'])->name('reports.project-performance');
Route::get('reports/project-profitability', [ReportController::class, 'projectProfitability'])->name('reports.project-profitability');