<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',
        'supplier_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'is_verified_buyer',
    ];

    protected $casts = [
        'is_verified_buyer' => 'boolean',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
