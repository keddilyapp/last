@extends('landlord.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
    <div>
        <a href="{{ route('landlord.tenants.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Tenant
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Total Tenants</h6>
                        <h2 class="mb-0 text-white">{{ $stats['total_tenants'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Active Tenants</h6>
                        <h2 class="mb-0 text-white">{{ $stats['active_tenants'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stats-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Total Users</h6>
                        <h2 class="mb-0 text-white">{{ $stats['total_users'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Monthly Revenue</h6>
                        <h2 class="mb-0 text-white">${{ number_format($stats['total_revenue'], 2) }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tenants -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Tenants</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_tenants']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Domain</th>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_tenants'] as $tenant)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <span class="text-white fw-bold">{{ substr($tenant->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $tenant->name }}</h6>
                                                <small class="text-muted">{{ $tenant->landlord->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code>{{ $tenant->domain }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $tenant->subscription_plan ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($tenant->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $tenant->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('landlord.tenants.show', $tenant) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tenants yet</h5>
                        <p class="text-muted">Create your first tenant to get started.</p>
                        <a href="{{ route('landlord.tenants.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Tenant
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Subscription Plans</h5>
            </div>
            <div class="card-body">
                @if($stats['subscription_plans']->count() > 0)
                    @foreach($stats['subscription_plans'] as $plan)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0">{{ $plan->name }}</h6>
                            <small class="text-muted">{{ $plan->billing_cycle_text }}</small>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 text-primary">{{ $plan->formatted_price }}</h6>
                            <small class="text-muted">{{ $plan->tenants()->count() }} tenants</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No subscription plans</p>
                    </div>
                @endif
                
                <div class="mt-3">
                    <a href="{{ route('landlord.subscription-plans.index') }}" class="btn btn-outline-primary btn-sm w-100">
                        Manage Plans
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('landlord.tenants.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Add New Tenant
                    </a>
                    <a href="{{ route('landlord.subscription-plans.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-credit-card me-2"></i>Create Plan
                    </a>
                    <a href="{{ route('landlord.reports.tenants') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-chart-bar me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    $(document).ready(function() {
        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            // You can add AJAX calls here to refresh stats
        }, 30000);
    });
</script>
@endpush 