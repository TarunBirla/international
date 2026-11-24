<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable();
            $table->string('hs_code')->default('8479.89.97'); // Harmonized System Code for customs
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->json('technical_specifications')->nullable();
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('currency', 10)->default('GBP'); // GBP, USD, EUR, INR, CNY
            $table->decimal('base_price', 12, 2)->default(0.00);
            $table->boolean('price_on_request')->default(false);
            $table->integer('moq')->default(1); // Minimum Order Quantity
            $table->string('unit', 50)->default('Pieces'); // Pieces, Sets, Metric Tons, Kg, Meters
            $table->json('volume_pricing')->nullable(); // [{"min": 10, "max": 49, "price": 450}, ...]
            $table->decimal('weight_kg', 10, 2)->default(5.00);
            $table->string('dimensions')->nullable(); // e.g. "50 x 30 x 40 cm"
            $table->integer('lead_time_days')->default(15);
            $table->string('manufacturing_capacity')->nullable(); // e.g. "5000 units/month"
            $table->string('origin_country')->default('India');
            $table->boolean('direct_purchase_enabled')->default(true);
            $table->string('status')->default('active'); // active, draft, out_of_stock
            $table->boolean('is_featured')->default(false);
            $table->integer('stock_quantity')->default(100);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
