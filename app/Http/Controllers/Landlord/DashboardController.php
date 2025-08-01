<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('is_active', true)->count(),
            'total_users' => User::where('is_landlord', false)->count(),
            'total_revenue' => Tenant::where('subscription_status', 'active')->sum('subscription_plan'),
            'recent_tenants' => Tenant::with('landlord')->latest()->take(5)->get(),
            'subscription_plans' => SubscriptionPlan::active()->ordered()->get(),
        ];

        return view('landlord.dashboard.index', compact('stats'));
    }

    public function tenants()
    {
        $tenants = Tenant::with(['landlord', 'users'])
            ->withCount(['users', 'products', 'orders'])
            ->latest()
            ->paginate(20);

        return view('landlord.tenants.index', compact('tenants'));
    }

    public function tenantDetails(Tenant $tenant)
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

    public function subscriptionPlans()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        return view('landlord.subscription-plans.index', compact('plans'));
    }

    public function createSubscriptionPlan()
    {
        return view('landlord.subscription-plans.create');
    }

    public function storeSubscriptionPlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'max_users' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:1',
            'max_storage_gb' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('landlord.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function editSubscriptionPlan(SubscriptionPlan $plan)
    {
        return view('landlord.subscription-plans.edit', compact('plan'));
    }

    public function updateSubscriptionPlan(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'max_users' => 'required|integer|min:1',
            'max_products' => 'required|integer|min:1',
            'max_storage_gb' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $plan->update($validated);

        return redirect()->route('landlord.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function deleteSubscriptionPlan(SubscriptionPlan $plan)
    {
        if ($plan->tenants()->count() > 0) {
            return back()->with('error', 'Cannot delete plan with active tenants.');
        }

        $plan->delete();

        return redirect()->route('landlord.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }
} 