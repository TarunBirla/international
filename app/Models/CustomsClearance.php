<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomsClearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'clearance_reference',
        'order_id',
        'shipment_id',
        'customs_partner_id',
        'port_of_entry',
        'country_of_origin',
        'hs_codes',
        'declared_cif_value',
        'applicable_duty_rate',
        'duty_payable_amount',
        'vat_rate',
        'vat_payable_amount',
        'clearance_service_fee',
        'total_customs_charges',
        'currency',
        'status',
        'commercial_invoice_file',
        'packing_list_file',
        'certificate_of_origin_file',
        'c88_entry_number',
        'partner_notes',
        'cleared_at',
    ];

    protected $casts = [
        'declared_cif_value' => 'float',
        'applicable_duty_rate' => 'float',
        'duty_payable_amount' => 'float',
        'vat_rate' => 'float',
        'vat_payable_amount' => 'float',
        'clearance_service_fee' => 'float',
        'total_customs_charges' => 'float',
        'cleared_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function customsPartner()
    {
        return $this->belongsTo(User::class, 'customs_partner_id');
    }
}
