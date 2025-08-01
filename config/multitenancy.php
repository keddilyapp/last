<?php

return [
    /*
     * This class is responsible for determining which tenant should be current
     * for the given request.
     *
     * This class should extend `Spatie\Multitenancy\TenantFinder\TenantFinder`
     *
     */
    'tenant_finder' => Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,

    /*
     * These fields are used by tenant:artisan command to match one or more tenant
     */
    'tenant_artisan_search_fields' => [
        'id',
        'name',
        'domain',
    ],

    /*
     * These tasks will be executed when switching between a tenant.
     *
     * A valid task is any class that implements Spatie\Multitenancy\Tasks\SwitchTenantTask
     */
    'switch_tenant_tasks' => [
        Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
        Spatie\Multitenancy\Tasks\SwitchRouteCacheTask::class,
    ],

    /*
     * This class is the model used for storing configuration on tenants.
     *
     * It must be or extend `Spatie\Multitenancy\Models\Tenant::class`
     */
    'tenant_model' => \App\Models\Tenant::class,

    /*
     * If there is a current tenant when dispatching a job, the name of the current tenant
     * will be automatically set on the job. When the job is executed, the tenant
     * will be automatically set as the current one.
     */
    'queues_are_tenant_aware_by_default' => true,

    /*
     * The connection name to the database where the `tenants` table is stored.
     *
     * Set to `null` to use the default connection.
     */
    'landlord_connection' => null,

    /*
     * This key will be used to bind the current tenant in the container.
     */
    'current_tenant_container_key' => 'currentTenant',

    /*
     * You can override some of the models of this package. Here you can
     * override the tenant model.
     */
    'models' => [
        'tenant' => \App\Models\Tenant::class,
    ],

    /*
     * The middleware that ensures each request has a valid tenant.
     *
     * This middleware should be in the `middleware_group` config of your RouteServiceProvider
     */
    'ensure_valid_tenant_session_middleware' => Spatie\Multitenancy\Middleware\EnsureValidTenantSession::class,

    /*
     * The name of the header that contains the tenant key.
     */
    'header_name' => 'X-Tenant',

    /*
     * The name of the query parameter that contains the tenant key.
     */
    'query_parameter_name' => 'tenant',

    /*
     * The name of the session key that contains the tenant key.
     */
    'session_key' => 'tenant',

    /*
     * The name of the cookie that contains the tenant key.
     */
    'cookie_name' => 'tenant',

    /*
     * The name of the database column that contains the tenant key.
     */
    'tenant_key_name' => 'id',

    /*
     * The name of the database column that contains the tenant domain.
     */
    'domain_name' => 'domain',

    /*
     * The name of the database column that contains the tenant database.
     */
    'database_name' => 'database',

    /*
     * The name of the database column that contains the tenant name.
     */
    'name_name' => 'name',

    /*
     * The name of the database column that contains the tenant slug.
     */
    'slug_name' => 'slug',

    /*
     * The name of the database column that contains the tenant settings.
     */
    'settings_name' => 'settings',

    /*
     * The name of the database column that contains the tenant features.
     */
    'features_name' => 'features',

    /*
     * The name of the database column that contains the tenant subscription plan.
     */
    'subscription_plan_name' => 'subscription_plan',

    /*
     * The name of the database column that contains the tenant subscription status.
     */
    'subscription_status_name' => 'subscription_status',

    /*
     * The name of the database column that contains the tenant subscription ends at.
     */
    'subscription_ends_at_name' => 'subscription_ends_at',

    /*
     * The name of the database column that contains the tenant is active.
     */
    'is_active_name' => 'is_active',

    /*
     * The name of the database column that contains the tenant created by.
     */
    'created_by_name' => 'created_by',

    /*
     * The name of the database column that contains the tenant max users.
     */
    'max_users_name' => 'max_users',

    /*
     * The name of the database column that contains the tenant max products.
     */
    'max_products_name' => 'max_products',

    /*
     * The name of the database column that contains the tenant max storage gb.
     */
    'max_storage_gb_name' => 'max_storage_gb',
]; 