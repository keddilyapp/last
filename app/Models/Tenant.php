<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'database',
        'subscription_plan',
        'subscription_status',
        'subscription_ends_at',
        'settings',
        'is_active',
        'created_by',
        'max_users',
        'max_products',
        'max_storage_gb',
        'features',
    ];

    protected $casts = [
        'settings' => 'array',
        'features' => 'array',
        'subscription_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function sellers()
    {
        return $this->hasMany(Seller::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isSubscriptionActive()
    {
        if ($this->subscription_status === 'active') {
            return true;
        }

        if ($this->subscription_ends_at && $this->subscription_ends_at->isFuture()) {
            return true;
        }

        return false;
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->features ?? []);
    }

    public function getSetting($key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->update(['settings' => $settings]);
    }
} 