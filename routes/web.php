<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Organization\DashboardController as OrganizationDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (from Breeze)
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect to appropriate dashboard based on user role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('organization.dashboard');
        }
    })->name('dashboard');
    
    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Include admin routes from admin.php
    require __DIR__.'/admin.php';
});

// Organization routes
Route::middleware(['auth', 'organization'])->prefix('organization')->name('organization.')->group(function () {
    // Include organization routes from organization.php
    require __DIR__.'/organization.php';
});

// Language switcher
Route::get('/language/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'id'])) {
        $locale = 'id'; // Default to Indonesian
    }
    
    session()->put('locale', $locale);
    
    // Update user language setting if logged in
    if (auth()->check()) {
        $user = auth()->user();
        $languageSetting = $user->languageSetting;
        
        if ($languageSetting) {
            $languageSetting->update(['language' => $locale]);
        } else {
            $user->languageSetting()->create(['language' => $locale]);
        }
    }
    
    return redirect()->back();
})->name('language.switch');