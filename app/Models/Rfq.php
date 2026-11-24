<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfq_number',
        'buyer_id',
        'category_id',
        'product_name',
        'quantity',
        'unit',
        'target_price',
        'currency',
        'preferred_countries',
        'required_delivery_date',
        'technical_specifications',
        'additional_requirements',
        'destination_country',
        'destination_port_city',
        'attachment_path',
        'status',
    ];

    protected $casts = [
        'preferred_countries' => 'array',
        'required_delivery_date' => 'date',
        'target_price' => 'float',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
