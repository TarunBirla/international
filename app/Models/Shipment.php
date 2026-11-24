<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_number',
        'order_id',
        'logistics_partner_id',
        'carrier_name',
        'shipment_type',
        'tracking_number',
        'origin_country',
        'origin_port_city',
        'destination_country',
        'destination_port_city',
        'total_weight_kg',
        'volume_cbm',
        'packages_count',
        'estimated_dispatch_date',
        'estimated_delivery_date',
        'actual_delivery_date',
        'current_location',
        'status',
        'shipping_documents',
    ];

    protected $casts = [
        'shipping_documents' => 'array',
        'total_weight_kg' => 'float',
        'volume_cbm' => 'float',
        'estimated_dispatch_date' => 'date',
        'estimated_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function logisticsPartner()
    {
        return $this->belongsTo(User::class, 'logistics_partner_id');
    }

    public function trackingUpdates()
    {
        return $this->hasMany(TrackingUpdate::class);
    }
}
