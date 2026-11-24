<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'company_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'hs_code',
        'short_description',
        'full_description',
        'technical_specifications',
        'main_image',
        'gallery_images',
        'currency',
        'base_price',
        'price_on_request',
        'moq',
        'unit',
        'volume_pricing',
        'weight_kg',
        'dimensions',
        'lead_time_days',
        'manufacturing_capacity',
        'origin_country',
        'direct_purchase_enabled',
        'status',
        'is_featured',
        'stock_quantity',
        'views_count',
    ];

    protected $casts = [
        'technical_specifications' => 'array',
        'gallery_images' => 'array',
        'volume_pricing' => 'array',
        'price_on_request' => 'boolean',
        'direct_purchase_enabled' => 'boolean',
        'is_featured' => 'boolean',
        'base_price' => 'float',
        'weight_kg' => 'float',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function getEffectivePriceForQuantity(int $quantity): float
    {
        if ($this->price_on_request) {
            return 0.00;
        }

        if (!empty($this->volume_pricing) && is_array($this->volume_pricing)) {
            foreach ($this->volume_pricing as $tier) {
                if (isset($tier['min']) && isset($tier['price'])) {
                    $min = (int) $tier['min'];
                    $max = isset($tier['max']) && $tier['max'] > 0 ? (int) $tier['max'] : PHP_INT_MAX;
                    if ($quantity >= $min && $quantity <= $max) {
                        return (float) $tier['price'];
                    }
                }
            }
        }

        return (float) $this->base_price;
    }
}
