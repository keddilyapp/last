<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landlord\DashboardController;
use App\Http\Controllers\Landlord\TenantController;

Route::middleware(['auth', 'landlord'])->prefix('landlord')->name('landlord.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tenants
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
    Route::post('tenants/{tenant}/create-database', [TenantController::class, 'createDatabase'])->name('tenants.create-database');
    
    // Subscription Plans
    Route::get('subscription-plans', [DashboardController::class, 'subscriptionPlans'])->name('subscription-plans.index');
    Route::get('subscription-plans/create', [DashboardController::class, 'createSubscriptionPlan'])->name('subscription-plans.create');
    Route::post('subscription-plans', [DashboardController::class, 'storeSubscriptionPlan'])->name('subscription-plans.store');
    Route::get('subscription-plans/{plan}/edit', [DashboardController::class, 'editSubscriptionPlan'])->name('subscription-plans.edit');
    Route::put('subscription-plans/{plan}', [DashboardController::class, 'updateSubscriptionPlan'])->name('subscription-plans.update');
    Route::delete('subscription-plans/{plan}', [DashboardController::class, 'deleteSubscriptionPlan'])->name('subscription-plans.destroy');
    
    // Reports
    Route::get('reports/tenants', [DashboardController::class, 'tenantReport'])->name('reports.tenants');
    Route::get('reports/revenue', [DashboardController::class, 'revenueReport'])->name('reports.revenue');
    Route::get('reports/usage', [DashboardController::class, 'usageReport'])->name('reports.usage');
    
    // Settings
    Route::get('settings', [DashboardController::class, 'settings'])->name('settings.index');
    Route::put('settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
    
    // User Management
    Route::get('users', [DashboardController::class, 'users'])->name('users.index');
    Route::get('users/{user}', [DashboardController::class, 'userDetails'])->name('users.show');
    Route::put('users/{user}', [DashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('users/{user}', [DashboardController::class, 'deleteUser'])->name('users.destroy');
}); 