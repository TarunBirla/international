<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_number',
        'product_id',
        'supplier_id',
        'buyer_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_company',
        'buyer_country',
        'subject',
        'message',
        'required_quantity',
        'unit',
        'status',
    ];

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

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
