<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'currency',
        'billing_period',
        'product_limit',
        'monthly_enquiry_limit',
        'featured_supplier_badge',
        'priority_rfq_access',
        'dedicated_account_manager',
        'customs_logistics_discount',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'featured_supplier_badge' => 'boolean',
        'priority_rfq_access' => 'boolean',
        'dedicated_account_manager' => 'boolean',
        'customs_logistics_discount' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(SupplierSubscription::class, 'plan_id');
    }
}
