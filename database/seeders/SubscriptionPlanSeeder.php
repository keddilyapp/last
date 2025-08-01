<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small businesses getting started',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'features' => [
                    'basic_ecommerce',
                    'up_to_100_products',
                    'up_to_10_users',
                    '1gb_storage',
                    'email_support',
                ],
                'max_users' => 10,
                'max_products' => 100,
                'max_storage_gb' => 1,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Ideal for growing businesses with more needs',
                'price' => 79.99,
                'billing_cycle' => 'monthly',
                'features' => [
                    'advanced_ecommerce',
                    'up_to_1000_products',
                    'up_to_50_users',
                    '10gb_storage',
                    'priority_support',
                    'advanced_analytics',
                    'multi_vendor',
                    'custom_domain',
                ],
                'max_users' => 50,
                'max_products' => 1000,
                'max_storage_gb' => 10,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large businesses with unlimited needs',
                'price' => 199.99,
                'billing_cycle' => 'monthly',
                'features' => [
                    'full_ecommerce_suite',
                    'unlimited_products',
                    'unlimited_users',
                    '100gb_storage',
                    'dedicated_support',
                    'advanced_analytics',
                    'multi_vendor',
                    'custom_domain',
                    'white_label',
                    'api_access',
                    'custom_integrations',
                ],
                'max_users' => -1, // Unlimited
                'max_products' => -1, // Unlimited
                'max_storage_gb' => 100,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
} 