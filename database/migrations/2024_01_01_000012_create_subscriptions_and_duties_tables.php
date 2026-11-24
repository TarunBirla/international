<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free Plan, Basic Plan, Premium Plan
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('GBP');
            $table->string('billing_period')->default('monthly'); // monthly, yearly
            $table->integer('product_limit')->default(10);
            $table->integer('monthly_enquiry_limit')->default(25);
            $table->boolean('featured_supplier_badge')->default(false);
            $table->boolean('priority_rfq_access')->default(false);
            $table->boolean('dedicated_account_manager')->default(false);
            $table->boolean('customs_logistics_discount')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('supplier_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at')->nullable();
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });

        Schema::create('hs_code_duties', function (Blueprint $table) {
            $table->id();
            $table->string('hs_code'); // e.g. "8479.89.97"
            $table->string('category_name'); // e.g. "Industrial Machinery"
            $table->text('description');
            $table->decimal('duty_rate_percent', 5, 2)->default(2.50);
            $table->decimal('vat_rate_percent', 5, 2)->default(20.00);
            $table->string('origin_country')->default('All'); // India, China, Germany, All
            $table->string('destination_country')->default('United Kingdom');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hs_code_duties');
        Schema::dropIfExists('supplier_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
