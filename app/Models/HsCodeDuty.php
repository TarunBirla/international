<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HsCodeDuty extends Model
{
    use HasFactory;

    protected $fillable = [
        'hs_code',
        'category_name',
        'description',
        'duty_rate_percent',
        'vat_rate_percent',
        'origin_country',
        'destination_country',
        'notes',
    ];

    protected $casts = [
        'duty_rate_percent' => 'float',
        'vat_rate_percent' => 'float',
    ];
}
