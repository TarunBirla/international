<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique();
            $table->foreignId('rfq_id')->nullable()->constrained('rfqs')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('unit_price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0.00);
            $table->decimal('insurance_cost', 12, 2)->default(0.00);
            $table->decimal('estimated_customs_duty', 12, 2)->default(0.00);
            $table->decimal('estimated_vat', 12, 2)->default(0.00);
            $table->decimal('total_landed_cost', 12, 2);
            $table->string('currency', 10)->default('GBP');
            $table->string('shipping_method')->default('Air Freight LCL'); // DHL Express, Air Freight, Sea LCL, Sea FCL
            $table->integer('estimated_delivery_days')->default(14);
            $table->date('validity_date')->nullable();
            $table->text('payment_terms')->nullable(); // 30% advance, 70% against BL / Letter of Credit
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('sent'); // sent, accepted, rejected, expired, converted_to_order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
