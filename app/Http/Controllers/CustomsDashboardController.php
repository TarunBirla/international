<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomsClearance;
use App\Models\TrackingUpdate;
use App\Models\Order;

class CustomsDashboardController extends Controller
{
    private function ensureClearanceForOrders()
    {
        $customsUser = Auth::user();
        $unlinkedOrders = Order::doesntHave('customsClearances')->get();
        foreach ($unlinkedOrders as $o) {
            CustomsClearance::create([
                'clearance_reference' => 'CUS-UK-' . date('Y') . '-' . rand(1000, 9999),
                'order_id' => $o->id,
                'shipment_id' => $o->shipments->first()->id ?? null,
                'customs_partner_id' => $customsUser->id ?? null,
                'port_of_entry' => 'Port of Felixstowe',
                'country_of_origin' => 'India / China / Europe',
                'hs_codes' => '8457.10.90',
                'declared_cif_value' => $o->subtotal_amount + $o->shipping_amount,
                'applicable_duty_rate' => 2.50,
                'duty_payable_amount' => $o->customs_duty_amount ?? round(($o->subtotal_amount + $o->shipping_amount) * 0.025, 2),
                'vat_rate' => 20.00,
                'vat_payable_amount' => $o->vat_amount ?? round(($o->subtotal_amount + $o->shipping_amount + ($o->customs_duty_amount ?? 0)) * 0.20, 2),
                'clearance_service_fee' => 120.00,
                'total_customs_charges' => ($o->customs_duty_amount ?? 0) + ($o->vat_amount ?? 0) + 120.00,
                'currency' => $o->currency ?? 'GBP',
                'status' => 'declaration_filed',
                'partner_notes' => 'Customs declaration pre-lodged for international consignment.',
            ]);
        }
    }

    public function index()
    {
        $this->ensureClearanceForOrders();

        $clearances = CustomsClearance::with(['order.buyer.company', 'shipment'])->latest()->paginate(10);

        $stats = [
            'total_declarations' => CustomsClearance::count(),
            'pending_review' => CustomsClearance::whereNotIn('status', ['cleared', 'released'])->count(),
            'total_cleared' => CustomsClearance::whereIn('status', ['cleared', 'released'])->count(),
            'total_duty_vat_collected' => CustomsClearance::sum('total_customs_charges'),
        ];

        return view('dashboards.customs.index', compact('clearances', 'stats'));
    }

    public function orders()
    {
        $this->ensureClearanceForOrders();

        $orders = Order::with(['buyer.company', 'orderItems', 'shipments', 'customsClearances'])
            ->latest()
            ->paginate(12);

        return view('dashboards.customs.orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $clearance = CustomsClearance::findOrFail($id);

        $validated = $request->validate([
            'c88_entry_number' => 'nullable|string|max:100',
            'status' => 'required|in:declaration_filed,under_review,documents_required,duty_paid,cleared,physical_inspection,released',
            'partner_notes' => 'nullable|string',
        ]);

        $clearance->update(array_merge($validated, [
            'cleared_at' => in_array($validated['status'], ['cleared', 'released']) ? now() : $clearance->cleared_at,
        ]));

        // Add to order tracking
        $stage = in_array($validated['status'], ['cleared', 'released']) ? 'customs_cleared' : 'customs_pending';
        TrackingUpdate::create([
            'order_id' => $clearance->order_id,
            'shipment_id' => $clearance->shipment_id,
            'stage' => $stage,
            'title' => 'Customs Status: ' . ucfirst(str_replace('_', ' ', $validated['status'])),
            'description' => $validated['partner_notes'] ?? "Processed by Junaid & Co. Customs Clearance (C88 Entry: {$validated['c88_entry_number']})",
            'location' => $clearance->port_of_entry ?? 'Port of Felixstowe',
            'updated_by_user_id' => Auth::id(),
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'Customs declaration status updated successfully.');
    }
}
