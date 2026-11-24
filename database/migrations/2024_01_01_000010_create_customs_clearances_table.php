<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customs_clearances', function (Blueprint $table) {
            $table->id();
            $table->string('clearance_reference')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained('shipments')->onDelete('set null');
            $table->foreignId('customs_partner_id')->nullable()->constrained('users')->onDelete('set null'); // e.g. Junaid's Company
            $table->string('port_of_entry')->default('Felixstowe Port'); // Felixstowe, London Gateway, Heathrow Airport, Southampton
            $table->string('country_of_origin')->default('India');
            $table->string('hs_codes')->nullable(); // e.g. "8479.89.97, 8501.10.99"
            $table->decimal('declared_cif_value', 12, 2); // Cost + Insurance + Freight
            $table->decimal('applicable_duty_rate', 5, 2)->default(2.50); // e.g. 2.5%
            $table->decimal('duty_payable_amount', 12, 2)->default(0.00);
            $table->decimal('vat_rate', 5, 2)->default(20.00); // UK VAT 20%
            $table->decimal('vat_payable_amount', 12, 2)->default(0.00);
            $table->decimal('clearance_service_fee', 10, 2)->default(120.00);
            $table->decimal('total_customs_charges', 12, 2)->default(0.00);
            $table->string('currency', 10)->default('GBP');
            $table->string('status')->default('declaration_filed'); 
            // declaration_filed, under_review, documents_required, duty_paid, cleared, physical_inspection, released
            $table->string('commercial_invoice_file')->nullable();
            $table->string('packing_list_file')->nullable();
            $table->string('certificate_of_origin_file')->nullable();
            $table->string('c88_entry_number')->nullable(); // UK Customs C88 / CDS Entry number
            $table->text('partner_notes')->nullable();
            $table->timestamp('cleared_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customs_clearances');
    }
};
