<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'rfq_id',
        'product_id',
        'supplier_id',
        'buyer_id',
        'unit_price',
        'quantity',
        'subtotal_amount',
        'shipping_cost',
        'insurance_cost',
        'estimated_customs_duty',
        'estimated_vat',
        'total_landed_cost',
        'currency',
        'shipping_method',
        'estimated_delivery_days',
        'validity_date',
        'payment_terms',
        'notes',
        'attachment_path',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'subtotal_amount' => 'float',
        'shipping_cost' => 'float',
        'insurance_cost' => 'float',
        'estimated_customs_duty' => 'float',
        'estimated_vat' => 'float',
        'total_landed_cost' => 'float',
        'validity_date' => 'date',
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
