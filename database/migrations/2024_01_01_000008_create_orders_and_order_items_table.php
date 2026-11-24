<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->onDelete('set null');
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('shipping_amount', 12, 2)->default(0.00);
            $table->decimal('customs_duty_amount', 12, 2)->default(0.00);
            $table->decimal('vat_amount', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 10)->default('GBP');
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method')->default('stripe'); // stripe, bank_transfer, letter_of_credit
            $table->string('payment_reference')->nullable();
            $table->string('order_status')->default('order_placed'); 
            // 14 stages: order_placed, payment_confirmed, supplier_confirmed, processing, ready_for_dispatch, 
            // dispatched, in_transit, customs_pending, customs_cleared, arrived_in_uk, out_for_delivery, delivered, cancelled
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();
            $table->string('destination_country')->default('United Kingdom');
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('purchase_order_number')->nullable(); // Supplier-specific PO number
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('hs_code')->nullable();
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->string('unit', 50)->default('Pieces');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('supplier_shipping_share', 12, 2)->default(0.00);
            $table->string('supplier_status')->default('pending'); // pending, accepted, in_production, ready_for_dispatch, dispatched
            $table->string('supplier_tracking_number')->nullable();
            $table->text('supplier_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
