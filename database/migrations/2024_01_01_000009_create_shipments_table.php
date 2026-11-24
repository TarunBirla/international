<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('logistics_partner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('carrier_name')->default('DHL Express'); // DHL, UPS, FedEx, Air Freight LCL, Sea Freight FCL
            $table->string('shipment_type')->default('small_parcel'); // small_parcel, air_freight, sea_lcl, sea_fcl
            $table->string('tracking_number')->nullable();
            $table->string('origin_country')->default('India');
            $table->string('origin_port_city')->nullable();
            $table->string('destination_country')->default('United Kingdom');
            $table->string('destination_port_city')->default('Felixstowe Port / Heathrow');
            $table->decimal('total_weight_kg', 10, 2)->default(10.00);
            $table->decimal('volume_cbm', 10, 3)->nullable();
            $table->integer('packages_count')->default(1);
            $table->date('estimated_dispatch_date')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->string('current_location')->nullable();
            $table->string('status')->default('booked'); // booked, picked_up, dispatched, in_transit, customs_hold, customs_released, out_for_delivery, delivered
            $table->json('shipping_documents')->nullable(); // Bill of Lading, Airway Bill, Packing List
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
