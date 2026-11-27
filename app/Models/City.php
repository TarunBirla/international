<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'image',
        'status',
        'sort_order',
        'show_on_home',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset(ltrim($this->image, '/'));
        }

        // Clean default industrial city skyline image
        return 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&h=400&fit=crop';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOnHome($query)
    {
        return $query->where('show_on_home', true);
    }
}
