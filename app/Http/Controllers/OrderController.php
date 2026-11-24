<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\CustomsClearance;
use App\Models\TrackingUpdate;
use App\Models\User;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Show Checkout Form
     */
    public function checkout()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with(['product.company', 'product.supplier'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your procurement cart is empty.');
        }

        $subtotal = 0;
        $totalWeight = 0;
        $suppliers = [];

        foreach ($cartItems as $item) {
            $unitPrice = $item->product->getEffectivePriceForQuantity($item->quantity);
            $subtotal += $unitPrice * $item->quantity;
            $totalWeight += ($item->product->weight_kg ?? 1) * $item->quantity;
            $suppliers[$item->product->supplier_id] = $item->product->supplier;
        }

        $shipping = count($suppliers) * 150 + ($totalWeight * 2.5);
        $duty = $subtotal * 0.025;
        $vat = ($subtotal + $shipping + $duty) * 0.20;
        $total = $subtotal + $shipping + $duty + $vat;

        $user = Auth::user();

        return view('orders.checkout', compact('cartItems', 'subtotal', 'totalWeight', 'suppliers', 'shipping', 'duty', 'vat', 'total', 'user'));
    }

    /**
     * Process Checkout & Multi-Supplier Split Order Generation
     */
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:50',
            'country' => 'required|string|max:100',
            'contact_phone' => 'required|string|max:50',
            'payment_method' => 'required|in:stripe,bank_transfer,letter_of_credit',
            'notes' => 'nullable|string',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->with(['product.company', 'product.supplier'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = 0;
        $totalWeight = 0;
        $supplierGroups = [];

        foreach ($cartItems as $item) {
            $unitPrice = $item->product->getEffectivePriceForQuantity($item->quantity);
            $subtotal += $unitPrice * $item->quantity;
            $totalWeight += ($item->product->weight_kg ?? 1) * $item->quantity;
            $supplierGroups[$item->product->supplier_id][] = [
                'item' => $item,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $item->quantity,
            ];
        }

        $shipping = count($supplierGroups) * 150 + ($totalWeight * 2.5);
        $duty = $subtotal * 0.025;
        $vat = ($subtotal + $shipping + $duty) * 0.20;
        $total = $subtotal + $shipping + $duty + $vat;

        $orderNumber = 'ORD-' . date('Y') . '-' . rand(1000, 9999);

        // 1. Create Parent Order
        $order = Order::create([
            'order_number' => $orderNumber,
            'buyer_id' => Auth::id(),
            'subtotal_amount' => $subtotal,
            'shipping_amount' => $shipping,
            'customs_duty_amount' => $duty,
            'vat_amount' => $vat,
            'discount_amount' => 0.00,
            'total_amount' => $total,
            'currency' => 'GBP',
            'payment_status' => 'paid', // Simulated instant checkout for smooth testing
            'payment_method' => $validated['payment_method'],
            'payment_reference' => 'TXN-' . strtoupper(Str::random(12)),
            'order_status' => 'payment_confirmed',
            'shipping_address' => $validated,
            'destination_country' => $validated['country'],
            'tracking_number' => 'PRL-UK-' . date('Y') . '-' . rand(1000, 9999),
            'notes' => $validated['notes'] ?? null,
        ]);

        // 2. Create Split Purchase Orders for each supplier
        $supplierShippingShare = $shipping / max(1, count($supplierGroups));
        foreach ($supplierGroups as $supplierId => $groupItems) {
            $supplier = User::find($supplierId);
            $countryCode = strtoupper(substr($supplier->country ?? 'GLB', 0, 3));
            $poNumber = 'PO-' . rand(1000, 9999) . '-' . $countryCode;

            foreach ($groupItems as $g) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'purchase_order_number' => $poNumber,
                    'supplier_id' => $supplierId,
                    'product_id' => $g['item']->product_id,
                    'product_name' => $g['item']->product->name,
                    'sku' => $g['item']->product->sku,
                    'hs_code' => $g['item']->product->hs_code,
                    'unit_price' => $g['unit_price'],
                    'quantity' => $g['item']->quantity,
                    'unit' => $g['item']->product->unit,
                    'subtotal' => $g['subtotal'],
                    'supplier_shipping_share' => round($supplierShippingShare / count($groupItems), 2),
                    'supplier_status' => 'accepted',
                ]);
            }
        }

        // 3. Create Shipment Booking with Logistics
        $logisticsUser = User::where('role', 'logistics_partner')->first();
        $shipment = Shipment::create([
            'shipment_number' => 'SHP-' . date('Y') . '-' . rand(1000, 9999),
            'order_id' => $order->id,
            'logistics_partner_id' => $logisticsUser->id ?? null,
            'carrier_name' => $totalWeight > 500 ? 'Consolidated Sea Freight (LCL)' : 'DHL Express / Air Freight',
            'shipment_type' => $totalWeight > 500 ? 'sea_lcl' : 'air_freight',
            'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
            'origin_country' => 'Multiple Origins (India, China, Europe)',
            'destination_country' => $validated['country'],
            'destination_port_city' => 'Port of Felixstowe / London Gateway',
            'total_weight_kg' => $totalWeight,
            'packages_count' => count($cartItems),
            'estimated_dispatch_date' => now()->addDays(5),
            'estimated_delivery_date' => now()->addDays(20),
            'current_location' => 'Order Confirmed - Production Queue',
            'status' => 'booked',
        ]);

        // 4. Create Customs Clearance Request (Junaid's Company)
        $customsUser = User::where('role', 'customs_partner')->first();
        CustomsClearance::create([
            'clearance_reference' => 'CUS-UK-' . date('Y') . '-' . rand(1000, 9999),
            'order_id' => $order->id,
            'shipment_id' => $shipment->id,
            'customs_partner_id' => $customsUser->id ?? null,
            'port_of_entry' => 'Port of Felixstowe',
            'country_of_origin' => 'Multiple (India / China / EU)',
            'hs_codes' => '8479.89.97, 8501.52.20',
            'declared_cif_value' => $subtotal + $shipping,
            'applicable_duty_rate' => 2.50,
            'duty_payable_amount' => $duty,
            'vat_rate' => 20.00,
            'vat_payable_amount' => $vat,
            'clearance_service_fee' => 120.00,
            'total_customs_charges' => $duty + $vat + 120.00,
            'currency' => 'GBP',
            'status' => 'declaration_filed',
            'partner_notes' => 'Commercial invoices queued for HMRC CDS declaration.',
        ]);

        // 5. Initial Tracking Timeline updates
        TrackingUpdate::create([
            'order_id' => $order->id,
            'shipment_id' => $shipment->id,
            'stage' => 'order_placed',
            'title' => 'Purchase Order Placed',
            'description' => "Order {$orderNumber} successfully received and verified.",
            'location' => 'Perlcon International Portal',
            'updated_by_user_id' => Auth::id(),
            'occurred_at' => now(),
        ]);

        TrackingUpdate::create([
            'order_id' => $order->id,
            'shipment_id' => $shipment->id,
            'stage' => 'payment_confirmed',
            'title' => 'Trade Assurance Escrow Payment Confirmed',
            'description' => 'Payment secured in Perlcon trade assurance escrow account.',
            'location' => 'London, UK Payment Hub',
            'updated_by_user_id' => Auth::id(),
            'occurred_at' => now()->addMinutes(2),
        ]);

        // 6. Clear user cart
        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order->id)->with('success', "Order {$orderNumber} confirmed! Multi-supplier purchase orders and shipment tracking have been initiated.");
    }

    /**
     * Show Order Details with 14-Stage Tracking Stepper
     */
    public function show($id)
    {
        $order = Order::with([
            'buyer.company',
            'quotation',
            'orderItems.supplier.company',
            'orderItems.product',
            'shipments.logisticsPartner',
            'customsClearances.customsPartner',
            'trackingUpdates'
        ])->findOrFail($id);

        $user = Auth::user();

        // Permission check
        if ($user->isBuyer() && $order->buyer_id !== $user->id) {
            abort(403);
        }

        // 14 stages list
        $allStages = [
            'order_placed' => ['label' => 'Order Placed', 'icon' => 'fa-receipt'],
            'payment_confirmed' => ['label' => 'Payment Confirmed', 'icon' => 'fa-credit-card'],
            'supplier_confirmed' => ['label' => 'Supplier Confirmed', 'icon' => 'fa-building-circle-check'],
            'processing' => ['label' => 'In Production & QC', 'icon' => 'fa-gears'],
            'ready_for_dispatch' => ['label' => 'Ready for Dispatch', 'icon' => 'fa-boxes-packing'],
            'dispatched' => ['label' => 'Dispatched from Factory', 'icon' => 'fa-truck-arrow-right'],
            'in_transit' => ['label' => 'International Transit', 'icon' => 'fa-plane-departure'],
            'customs_pending' => ['label' => 'Customs Pending', 'icon' => 'fa-file-shield'],
            'customs_cleared' => ['label' => 'Customs Cleared', 'icon' => 'fa-shield-halved'],
            'arrived_in_uk' => ['label' => 'Arrived in UK', 'icon' => 'fa-location-dot'],
            'out_for_delivery' => ['label' => 'Out for UK Delivery', 'icon' => 'fa-truck-fast'],
            'delivered' => ['label' => 'Delivered to Buyer', 'icon' => 'fa-box-open'],
        ];

        return view('orders.show', compact('order', 'allStages'));
    }

    /**
     * Advance order status (For Admin, Supplier, Logistics, or Customs)
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        if ($user->isBuyer()) {
            abort(403, 'Buyers cannot update internal tracking status.');
        }

        $validated = $request->validate([
            'stage' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        $order->update(['order_status' => $validated['stage']]);

        TrackingUpdate::create([
            'order_id' => $order->id,
            'shipment_id' => $order->shipments->first()->id ?? null,
            'stage' => $validated['stage'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? 'International Transit Hub',
            'updated_by_user_id' => $user->id,
            'occurred_at' => now(),
        ]);

        return back()->with('success', "Order tracking updated to {$validated['stage']}.");
    }
}
