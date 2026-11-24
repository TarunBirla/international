<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shipment;
use App\Models\TrackingUpdate;

class LogisticsDashboardController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['order.buyer.company', 'trackingUpdates'])->latest()->paginate(10);

        $stats = [
            'total_shipments' => Shipment::count(),
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
        ];

        return view('dashboards.logistics.index', compact('shipments', 'stats'));
    }

    public function updateShipment(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $validated = $request->validate([
            'carrier_name' => 'required|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'current_location' => 'required|string|max:255',
            'status' => 'required|in:booked,picked_up,dispatched,in_transit,customs_hold,customs_released,out_for_delivery,delivered',
        ]);

        $shipment->update($validated);

        // Also add tracking update to order timeline
        TrackingUpdate::create([
            'order_id' => $shipment->order_id,
            'shipment_id' => $shipment->id,
            'stage' => $validated['status'] === 'in_transit' ? 'in_transit' : ($validated['status'] === 'delivered' ? 'delivered' : 'dispatched'),
            'title' => 'Carrier Logistics Update: ' . ucfirst(str_replace('_', ' ', $validated['status'])),
            'description' => "Location: {$validated['current_location']}. Carrier: {$validated['carrier_name']}.",
            'location' => $validated['current_location'],
            'updated_by_user_id' => Auth::id(),
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'Shipment status and location updated.');
    }
}
