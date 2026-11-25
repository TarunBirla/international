<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'avatar',
        'country',
        'city',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            return asset(ltrim($this->avatar, '/'));
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0284c7&color=fff&bold=true';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    public function isSupplier(): bool
    {
        return $this->role === 'supplier';
    }

    public function isLogistics(): bool
    {
        return $this->role === 'logistics_partner';
    }

    public function isCustoms(): bool
    {
        return $this->role === 'customs_partner';
    }

    public function getDashboardLayout(): string
    {
        if ($this->isAdmin()) {
            return 'layouts.admin';
        }
        if ($this->isBuyer()) {
            return 'layouts.buyer';
        }
        if ($this->isSupplier()) {
            return 'layouts.supplier';
        }
        if ($this->isLogistics()) {
            return 'layouts.logistics';
        }
        if ($this->isCustoms()) {
            return 'layouts.customs';
        }
        return 'layouts.app';
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class, 'buyer_id');
    }

    public function submittedQuotations()
    {
        return $this->hasMany(Quotation::class, 'supplier_id');
    }

    public function receivedQuotations()
    {
        return $this->hasMany(Quotation::class, 'buyer_id');
    }

    public function sentEnquiries()
    {
        return $this->hasMany(Enquiry::class, 'buyer_id');
    }

    public function receivedEnquiries()
    {
        return $this->hasMany(Enquiry::class, 'supplier_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function supplierOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'supplier_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function savedProducts()
    {
        return $this->belongsToMany(Product::class, 'saved_products');
    }

    public function favoriteSuppliers()
    {
        return $this->belongsToMany(User::class, 'favorite_suppliers', 'user_id', 'supplier_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(SupplierSubscription::class, 'supplier_id')->where('status', 'active')->latest();
    }
}
