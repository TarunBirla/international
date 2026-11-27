<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'logo',
        'banner',
        'business_type',
        'country',
        'country_code',
        'city',
        'state',
        'address',
        'postal_code',
        'tax_id',
        'year_established',
        'employees_count',
        'annual_turnover',
        'export_markets',
        'description',
        'manufacturing_capabilities',
        'certifications',
        'website',
        'is_verified',
        'verification_documents',
        'rating',
        'reviews_count',
    ];

    protected $casts = [
        'certifications' => 'array',
        'verification_documents' => 'array',
        'is_verified' => 'boolean',
        'rating' => 'float',
    ];

    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->logo)) {
            if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
                return $this->logo;
            }
            return asset(ltrim($this->logo, '/'));
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->company_name) . '&background=059669&color=fff&bold=true';
    }

    public function getCountryFlagAttribute(): string
    {
        $country = trim($this->country ?? '');
        return match ($country) {
            'India' => '🇮🇳',
            'China' => '🇨🇳',
            'United Kingdom', 'UK', 'GB', 'Great Britain' => '🇬🇧',
            'Germany', 'Deutschland' => '🇩🇪',
            'United States', 'USA', 'US' => '🇺🇸',
            'Japan' => '🇯🇵',
            'Italy', 'Italia' => '🇮🇹',
            'France' => '🇫🇷',
            'Spain' => '🇪🇸',
            default => '🌐',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'company_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'supplier_id', 'user_id');
    }
}
