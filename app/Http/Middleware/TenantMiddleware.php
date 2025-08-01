<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Landlords can access everything
        if ($user->isLandlord()) {
            return $next($request);
        }

        // Tenant users must have a tenant_id
        if (!$user->tenant_id) {
            return redirect()->route('home')->with('error', 'No tenant associated with this account.');
        }

        // Check if tenant is active
        $tenant = Tenant::find($user->tenant_id);
        if (!$tenant || !$tenant->is_active) {
            return redirect()->route('home')->with('error', 'Your tenant account is inactive.');
        }

        // Check subscription status
        if (!$tenant->isSubscriptionActive()) {
            return redirect()->route('home')->with('error', 'Your subscription has expired.');
        }

        return $next($request);
    }
} 