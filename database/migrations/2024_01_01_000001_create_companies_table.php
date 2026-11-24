<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('business_type')->default('manufacturer'); // manufacturer, wholesaler, distributor, exporter, trading_company
            $table->string('country'); // India, China, Germany, United Kingdom, etc.
            $table->string('country_code', 10)->default('GB');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('tax_id')->nullable(); // GST / VAT / Tax ID
            $table->string('year_established')->nullable();
            $table->string('employees_count')->nullable();
            $table->string('annual_turnover')->nullable();
            $table->string('export_markets')->nullable(); // e.g. "UK, Europe, North America, Middle East"
            $table->text('description')->nullable();
            $table->text('manufacturing_capabilities')->nullable();
            $table->json('certifications')->nullable(); // ISO 9001, CE, RoHS, etc.
            $table->string('website')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->json('verification_documents')->nullable();
            $table->decimal('rating', 3, 2)->default(4.8);
            $table->integer('reviews_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
