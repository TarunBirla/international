<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomsClearance;
use App\Models\TrackingUpdate;

class CustomsDashboardController extends Controller
{
    public function index()
    {
        $clearances = CustomsClearance::with(['order.buyer.company', 'shipment'])->latest()->paginate(10);

        $stats = [
            'total_declarations' => CustomsClearance::count(),
            'pending_review' => CustomsClearance::whereNotIn('status', ['cleared', 'released'])->count(),
            'total_cleared' => CustomsClearance::whereIn('status', ['cleared', 'released'])->count(),
            'total_duty_vat_collected' => CustomsClearance::sum('total_customs_charges'),
        ];

        return view('dashboards.customs.index', compact('clearances', 'stats'));
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
