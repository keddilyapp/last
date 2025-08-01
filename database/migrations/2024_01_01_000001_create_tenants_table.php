<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('database')->nullable();
            $table->string('subscription_plan')->nullable();
            $table->enum('subscription_status', ['active', 'inactive', 'suspended', 'cancelled'])->default('inactive');
            $table->timestamp('subscription_ends_at')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->integer('max_users')->default(10);
            $table->integer('max_products')->default(100);
            $table->integer('max_storage_gb')->default(1);
            $table->json('features')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}; 