<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'buyer_id',
        'quotation_id',
        'subtotal_amount',
        'shipping_amount',
        'customs_duty_amount',
        'vat_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_status',
        'payment_method',
        'payment_reference',
        'order_status',
        'shipping_address',
        'billing_address',
        'destination_country',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal_amount' => 'float',
        'shipping_amount' => 'float',
        'customs_duty_amount' => 'float',
        'vat_amount' => 'float',
        'discount_amount' => 'float',
        'total_amount' => 'float',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function customsClearances()
    {
        return $this->hasMany(CustomsClearance::class);
    }

    public function trackingUpdates()
    {
        return $this->hasMany(TrackingUpdate::class)->orderBy('occurred_at', 'asc');
    }

    public function getStageIndexAttribute(): int
    {
        $stages = [
            'order_placed' => 1,
            'payment_confirmed' => 2,
            'supplier_confirmed' => 3,
            'processing' => 4,
            'ready_for_dispatch' => 5,
            'dispatched' => 6,
            'in_transit' => 7,
            'customs_pending' => 8,
            'customs_cleared' => 9,
            'arrived_in_uk' => 10,
            'out_for_delivery' => 11,
            'delivered' => 12,
            'cancelled' => 0,
        ];

        return $stages[$this->order_status] ?? 1;
    }
}
