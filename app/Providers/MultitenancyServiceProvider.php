<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Multitenancy\MultitenancyServiceProvider as SpatieMultitenancyServiceProvider;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\DomainTenantFinder;
use Illuminate\Support\Facades\Route;

class MultitenancyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(SpatieMultitenancyServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure tenant finder
        $this->configureTenantFinder();
        
        // Configure tenant switching
        $this->configureTenantSwitching();
        
        // Configure routes
        $this->configureRoutes();
    }

    protected function configureTenantFinder()
    {
        // Use domain-based tenant finding
        $this->app->singleton(DomainTenantFinder::class, function () {
            return new DomainTenantFinder();
        });
    }

    protected function configureTenantSwitching()
    {
        // Configure what happens when switching tenants
        Tenant::creating(function ($tenant) {
            // Set default values for new tenants
            if (!$tenant->database) {
                $tenant->database = 'tenant_' . strtolower(str_replace([' ', '-'], '_', $tenant->domain));
            }
        });
    }

    protected function configureRoutes()
    {
        // Add tenant parameter to routes
        Route::bind('tenant', function ($value) {
            return Tenant::where('id', $value)
                ->orWhere('domain', $value)
                ->orWhere('slug', $value)
                ->firstOrFail();
        });
    }
} 