<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('unit', 50)->default('Pieces');
            $table->decimal('target_price', 12, 2)->nullable();
            $table->string('currency', 10)->default('GBP');
            $table->json('preferred_countries')->nullable(); // ["India", "China", "Germany", "UK"]
            $table->date('required_delivery_date')->nullable();
            $table->text('technical_specifications')->nullable();
            $table->text('additional_requirements')->nullable();
            $table->string('destination_country')->default('United Kingdom');
            $table->string('destination_port_city')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('open'); // open, under_review, quoted, closed, awarded
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfqs');
    }
};
