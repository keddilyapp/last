<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['landlord', 'users'])
            ->withCount(['users', 'products', 'orders'])
            ->latest()
            ->paginate(20);

        return view('landlord.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $subscriptionPlans = SubscriptionPlan::active()->ordered()->get();
        return view('landlord.tenants.create', compact('subscriptionPlans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants,domain',
            'subscription_plan' => 'required|exists:subscription_plans,slug',
            'landlord_email' => 'required|email|unique:users,email',
            'landlord_name' => 'required|string|max:255',
            'landlord_password' => 'required|string|min:8',
            'max_users' => 'integer|min:1',
            'max_products' => 'integer|min:1',
            'max_storage_gb' => 'integer|min:1',
            'features' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Create landlord user
            $landlord = User::create([
                'name' => $validated['landlord_name'],
                'email' => $validated['landlord_email'],
                'password' => Hash::make($validated['landlord_password']),
                'is_landlord' => true,
                'email_verified_at' => now(),
            ]);

            // Get subscription plan
            $plan = SubscriptionPlan::where('slug', $validated['subscription_plan'])->first();

            // Create tenant
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'database' => 'tenant_' . Str::slug($validated['domain']),
                'subscription_plan' => $validated['subscription_plan'],
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addMonth(),
                'created_by' => $landlord->id,
                'max_users' => $validated['max_users'] ?? $plan->max_users,
                'max_products' => $validated['max_products'] ?? $plan->max_products,
                'max_storage_gb' => $validated['max_storage_gb'] ?? $plan->max_storage_gb,
                'features' => $validated['features'] ?? $plan->features,
                'settings' => $validated['settings'] ?? [],
                'is_active' => true,
            ]);

            // Update landlord with tenant_id
            $landlord->update(['tenant_id' => $tenant->id]);

            DB::commit();

            return redirect()->route('landlord.tenants.index')
                ->with('success', 'Tenant created successfully. Database: ' . $tenant->database);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['landlord', 'users', 'products', 'orders']);
        
        $stats = [
            'total_users' => $tenant->users()->count(),
            'total_products' => $tenant->products()->count(),
            'total_orders' => $tenant->orders()->count(),
            'total_revenue' => $tenant->orders()->sum('grand_total'),
        ];

        return view('landlord.tenants.show', compact('tenant', 'stats'));
    }

    public function edit(Tenant $tenant)
    {
        $subscriptionPlans = SubscriptionPlan::active()->ordered()->get();
        return view('landlord.tenants.edit', compact('tenant', 'subscriptionPlans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants,domain,' . $tenant->id,
            'subscription_plan' => 'required|exists:subscription_plans,slug',
            'subscription_status' => 'required|in:active,inactive,suspended,cancelled',
            'subscription_ends_at' => 'nullable|date',
            'max_users' => 'integer|min:1',
            'max_products' => 'integer|min:1',
            'max_storage_gb' => 'integer|min:1',
            'features' => 'nullable|array',
            'settings' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $tenant->update($validated);

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        // Check if tenant has data
        if ($tenant->users()->count() > 0 || $tenant->products()->count() > 0) {
            return back()->with('error', 'Cannot delete tenant with existing data.');
        }

        $tenant->delete();

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }

    public function suspend(Tenant $tenant)
    {
        $tenant->update([
            'subscription_status' => 'suspended',
            'is_active' => false,
        ]);

        return back()->with('success', 'Tenant suspended successfully.');
    }

    public function activate(Tenant $tenant)
    {
        $tenant->update([
            'subscription_status' => 'active',
            'is_active' => true,
        ]);

        return back()->with('success', 'Tenant activated successfully.');
    }

    public function createDatabase(Tenant $tenant)
    {
        try {
            // Create tenant database
            $databaseName = $tenant->database;
            
            // Create database if it doesn't exist
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            
            // Run migrations for tenant
            $this->runTenantMigrations($tenant);
            
            return back()->with('success', 'Tenant database created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create database: ' . $e->getMessage());
        }
    }

    private function runTenantMigrations($tenant)
    {
        // This would run the tenant-specific migrations
        // Implementation depends on your migration strategy
    }
} 