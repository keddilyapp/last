# Laravel SaaS Multitenancy Setup Guide

This guide will help you transform your Laravel CMS ecommerce multivendor project into a multitenancy SaaS application.

## Overview

The transformation includes:
- **Landlord System**: Central admin to manage all tenants
- **Tenant Isolation**: Each tenant gets their own ecommerce instance
- **Subscription Management**: Different pricing tiers with feature limits
- **Database Separation**: Each tenant has isolated data
- **Feature Control**: Granular feature access based on subscription

## Architecture

```
Landlord (SaaS Admin)
├── Manages all tenants
├── Creates subscription plans
├── Monitors usage and revenue
└── Controls feature access

Tenants (Ecommerce Instances)
├── Isolated databases
├── Custom domains
├── Feature-limited based on plan
└── Independent user management
```

## Installation Steps

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Subscription Plans

```bash
php artisan db:seed --class=SubscriptionPlanSeeder
```

### 3. Create Landlord User

```bash
php artisan landlord:create
```

Or with parameters:
```bash
php artisan landlord:create --name="Admin" --email="admin@saas.com" --password="password123"
```

### 4. Register Middleware

Add the following to `app/Http/Kernel.php` in the `$routeMiddleware` array:

```php
'landlord' => \App\Http\Middleware\LandlordMiddleware::class,
'tenant' => \App\Http\Middleware\TenantMiddleware::class,
```

### 5. Register Service Provider

Add to `config/app.php` in the `providers` array:

```php
App\Providers\MultitenancyServiceProvider::class,
```

### 6. Include Landlord Routes

Add to `routes/web.php`:

```php
require __DIR__.'/landlord.php';
```

## Database Structure

### New Tables Created

1. **tenants** - Stores tenant information
   - `id`, `name`, `domain`, `database`
   - `subscription_plan`, `subscription_status`
   - `max_users`, `max_products`, `max_storage_gb`
   - `features`, `settings`, `is_active`

2. **subscription_plans** - Pricing tiers
   - `id`, `name`, `slug`, `price`
   - `billing_cycle`, `features`
   - `max_users`, `max_products`, `max_storage_gb`

3. **users** - Updated with tenant support
   - Added `tenant_id`, `is_landlord` fields

## Usage

### Landlord Dashboard

Access the landlord dashboard at `/landlord` after logging in as a landlord user.

**Features:**
- View all tenants and their statistics
- Create new tenants with subscription plans
- Manage subscription plans and pricing
- Monitor usage and revenue
- Suspend/activate tenants

### Creating Tenants

1. Login as landlord
2. Go to Tenants → Create New Tenant
3. Fill in tenant details:
   - Name and domain
   - Subscription plan
   - Landlord user credentials
4. System creates:
   - Tenant record
   - Landlord user for tenant
   - Isolated database (if configured)

### Tenant Features

Each tenant gets:
- **Isolated Database**: Separate data storage
- **Custom Domain**: Unique subdomain
- **Feature Limits**: Based on subscription plan
- **User Management**: Independent user system
- **Ecommerce Features**: Full ecommerce functionality

## Subscription Plans

### Default Plans

1. **Starter** ($29.99/month)
   - Up to 100 products
   - Up to 10 users
   - 1GB storage
   - Basic ecommerce features

2. **Professional** ($79.99/month)
   - Up to 1,000 products
   - Up to 50 users
   - 10GB storage
   - Advanced features + multi-vendor

3. **Enterprise** ($199.99/month)
   - Unlimited products/users
   - 100GB storage
   - All features + white-label + API

## Configuration

### Environment Variables

Add to `.env`:

```env
# Multitenancy
TENANT_DATABASE_PREFIX=tenant_
TENANT_DOMAIN_SUFFIX=.yourdomain.com
```

### Database Configuration

For tenant database isolation, configure in `config/database.php`:

```php
'connections' => [
    'tenant' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

## API Endpoints

### Landlord API

```
GET  /landlord/api/tenants
POST /landlord/api/tenants
GET  /landlord/api/tenants/{id}
PUT  /landlord/api/tenants/{id}
DELETE /landlord/api/tenants/{id}

GET  /landlord/api/subscription-plans
POST /landlord/api/subscription-plans
```

### Tenant API

```
GET  /api/products
POST /api/products
GET  /api/orders
POST /api/orders
```

## Security Considerations

1. **Tenant Isolation**: Each tenant's data is completely isolated
2. **Role-Based Access**: Landlords vs Tenant users
3. **Subscription Validation**: Features limited by plan
4. **Database Security**: Separate databases per tenant

## Monitoring & Analytics

### Landlord Dashboard Metrics

- Total tenants and active tenants
- Revenue by subscription plan
- Usage statistics per tenant
- System health and performance

### Tenant Analytics

- Product and order statistics
- User activity and engagement
- Revenue and growth metrics
- Feature usage tracking

## Troubleshooting

### Common Issues

1. **Tenant not found**: Check domain configuration
2. **Database connection error**: Verify tenant database exists
3. **Feature access denied**: Check subscription plan limits
4. **Landlord access denied**: Verify user has landlord role

### Commands

```bash
# Check tenant status
php artisan tenant:list

# Create tenant database
php artisan tenant:create-database {tenant_id}

# Reset tenant data
php artisan tenant:reset {tenant_id}
```

## Next Steps

1. **Customize Subscription Plans**: Modify plans in `SubscriptionPlanSeeder`
2. **Add Payment Integration**: Integrate with Stripe/PayPal
3. **Implement Usage Tracking**: Monitor resource usage
4. **Add White-label Features**: Custom branding per tenant
5. **API Documentation**: Create comprehensive API docs

## Support

For issues and questions:
- Check the Laravel documentation
- Review the Spatie Multitenancy package docs
- Create an issue in the project repository

---

**Note**: This is a basic implementation. For production use, consider:
- Advanced security measures
- Automated backup systems
- Load balancing for multiple tenants
- Advanced monitoring and alerting
- Compliance with data protection regulations 