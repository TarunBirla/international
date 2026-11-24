<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('enquiry_number')->unique();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone')->nullable();
            $table->string('buyer_company')->nullable();
            $table->string('buyer_country')->default('United Kingdom');
            $table->string('subject');
            $table->text('message');
            $table->integer('required_quantity')->nullable();
            $table->string('unit', 50)->default('Pieces');
            $table->string('status')->default('new'); // new, in_discussion, quoted, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
