<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'purchase_order_number',
        'supplier_id',
        'product_id',
        'product_name',
        'sku',
        'hs_code',
        'unit_price',
        'quantity',
        'unit',
        'subtotal',
        'supplier_shipping_share',
        'supplier_status',
        'supplier_tracking_number',
        'supplier_notes',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'subtotal' => 'float',
        'supplier_shipping_share' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
