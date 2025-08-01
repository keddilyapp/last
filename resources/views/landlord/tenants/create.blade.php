@extends('landlord.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Create New Tenant</h1>
    <a href="{{ route('landlord.tenants.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Tenants
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tenant Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('landlord.tenants.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tenant Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="domain" class="form-label">Domain *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('domain') is-invalid @enderror" 
                                           id="domain" name="domain" value="{{ old('domain') }}" required>
                                    <span class="input-group-text">.yourdomain.com</span>
                                </div>
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">This will be the subdomain for the tenant's store.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="subscription_plan" class="form-label">Subscription Plan *</label>
                        <select class="form-select @error('subscription_plan') is-invalid @enderror" 
                                id="subscription_plan" name="subscription_plan" required>
                            <option value="">Select a plan</option>
                            @foreach($subscriptionPlans as $plan)
                                <option value="{{ $plan->slug }}" {{ old('subscription_plan') == $plan->slug ? 'selected' : '' }}>
                                    {{ $plan->name }} - ${{ $plan->price }}/{{ $plan->billing_cycle }}
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_plan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h6 class="mb-3">Landlord User Information</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="landlord_name" class="form-label">Landlord Name *</label>
                                <input type="text" class="form-control @error('landlord_name') is-invalid @enderror" 
                                       id="landlord_name" name="landlord_name" value="{{ old('landlord_name') }}" required>
                                @error('landlord_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="landlord_email" class="form-label">Landlord Email *</label>
                                <input type="email" class="form-control @error('landlord_email') is-invalid @enderror" 
                                       id="landlord_email" name="landlord_email" value="{{ old('landlord_email') }}" required>
                                @error('landlord_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="landlord_password" class="form-label">Landlord Password *</label>
                        <input type="password" class="form-control @error('landlord_password') is-invalid @enderror" 
                               id="landlord_password" name="landlord_password" required>
                        @error('landlord_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>

                    <hr>

                    <h6 class="mb-3">Advanced Settings (Optional)</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_users" class="form-label">Max Users</label>
                                <input type="number" class="form-control @error('max_users') is-invalid @enderror" 
                                       id="max_users" name="max_users" value="{{ old('max_users') }}" min="1">
                                @error('max_users')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to use plan default</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_products" class="form-label">Max Products</label>
                                <input type="number" class="form-control @error('max_products') is-invalid @enderror" 
                                       id="max_products" name="max_products" value="{{ old('max_products') }}" min="1">
                                @error('max_products')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to use plan default</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="max_storage_gb" class="form-label">Max Storage (GB)</label>
                                <input type="number" class="form-control @error('max_storage_gb') is-invalid @enderror" 
                                       id="max_storage_gb" name="max_storage_gb" value="{{ old('max_storage_gb') }}" min="1">
                                @error('max_storage_gb')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to use plan default</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('landlord.tenants.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Tenant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Subscription Plans</h5>
            </div>
            <div class="card-body">
                @foreach($subscriptionPlans as $plan)
                <div class="border rounded p-3 mb-3">
                    <h6 class="mb-1">{{ $plan->name }}</h6>
                    <p class="text-muted small mb-2">{{ $plan->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0 text-primary">${{ $plan->price }}</span>
                        <span class="badge bg-secondary">{{ $plan->billing_cycle }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted">Users</small>
                            <div class="fw-bold">{{ $plan->max_users == -1 ? '∞' : $plan->max_users }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Products</small>
                            <div class="fw-bold">{{ $plan->max_products == -1 ? '∞' : $plan->max_products }}</div>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Storage</small>
                            <div class="fw-bold">{{ $plan->max_storage_gb }}GB</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">What happens next?</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Tenant record created
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Landlord user account created
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Database will be created
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Ecommerce store will be accessible
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate domain from tenant name
    $('#name').on('input', function() {
        let name = $(this).val();
        let domain = name.toLowerCase()
            .replace(/[^a-z0-9]/g, '')
            .substring(0, 20);
        $('#domain').val(domain);
    });
    
    // Show plan details when selected
    $('#subscription_plan').on('change', function() {
        let selectedPlan = $(this).val();
        if (selectedPlan) {
            // You can add AJAX call here to get plan details
            console.log('Selected plan:', selectedPlan);
        }
    });
});
</script>
@endpush 