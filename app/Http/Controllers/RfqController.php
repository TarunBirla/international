<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rfq;
use App\Models\Category;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\CustomsClearance;
use App\Models\TrackingUpdate;
use Illuminate\Support\Str;

class RfqController extends Controller
{
    /**
     * Public or Buyer RFQ List
     */
    public function index(Request $request)
    {
        $query = Rfq::with(['category', 'buyer.company'])->withCount('quotations');

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $rfqs = $query->latest()->paginate(10);
        $categories = Category::whereNull('parent_id')->get();

        return view('rfqs.index', compact('rfqs', 'categories'));
    }

    /**
     * Show form to create an RFQ
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        return view('rfqs.create', compact('categories'));
    }

    /**
     * Store new RFQ
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'target_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'preferred_countries' => 'nullable|array',
            'required_delivery_date' => 'nullable|date',
            'technical_specifications' => 'required|string',
            'additional_requirements' => 'nullable|string',
            'destination_country' => 'required|string|max:100',
            'destination_port_city' => 'nullable|string|max:100',
        ]);

        $rfqNumber = 'RFQ-' . date('Y') . '-' . strtoupper(Str::random(5));

        $rfq = Rfq::create([
            'rfq_number' => $rfqNumber,
            'buyer_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'product_name' => $validated['product_name'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'target_price' => $validated['target_price'] ?? null,
            'currency' => $validated['currency'],
            'preferred_countries' => $validated['preferred_countries'] ?? ['India', 'China', 'Germany', 'United Kingdom'],
            'required_delivery_date' => $validated['required_delivery_date'] ?? null,
            'technical_specifications' => $validated['technical_specifications'],
            'additional_requirements' => $validated['additional_requirements'] ?? null,
            'destination_country' => $validated['destination_country'],
            'destination_port_city' => $validated['destination_port_city'] ?? 'London Gateway / Felixstowe',
            'status' => 'open',
        ]);

        return redirect()->route('rfqs.show', $rfq->id)->with('success', 'Your Request for Quotation (RFQ) has been published to verified international suppliers!');
    }

    /**
     * View RFQ details
     */
    public function show($id)
    {
        $rfq = Rfq::with(['category', 'buyer.company', 'quotations.supplier.company'])->findOrFail($id);

        $userQuotation = null;
        if (Auth::check() && Auth::user()->isSupplier()) {
            $userQuotation = Quotation::where('rfq_id', $rfq->id)
                ->where('supplier_id', Auth::id())
                ->first();
        }

        return view('rfqs.show', compact('rfq', 'userQuotation'));
    }

    /**
     * Supplier submits quotation for an RFQ
     */
    public function submitQuotation(Request $request, $id)
    {
        $rfq = Rfq::findOrFail($id);

        if (!Auth::user()->isSupplier()) {
            abort(403, 'Only registered suppliers can submit quotations.');
        }

        $validated = $request->validate([
            'unit_price' => 'required|numeric|min:0.01',
            'shipping_cost' => 'required|numeric|min:0',
            'insurance_cost' => 'nullable|numeric|min:0',
            'estimated_customs_duty' => 'nullable|numeric|min:0',
            'estimated_vat' => 'nullable|numeric|min:0',
            'shipping_method' => 'required|string',
            'estimated_delivery_days' => 'required|integer|min:1',
            'validity_days' => 'required|integer|min:1',
            'payment_terms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $subtotal = $validated['unit_price'] * $rfq->quantity;
        $insurance = $validated['insurance_cost'] ?? round($subtotal * 0.008, 2);
        $duty = $validated['estimated_customs_duty'] ?? round($subtotal * 0.025, 2);
        $vat = $validated['estimated_vat'] ?? round(($subtotal + $validated['shipping_cost'] + $duty) * 0.20, 2);
        $totalLanded = $subtotal + $validated['shipping_cost'] + $insurance + $duty + $vat;

        $qtnNumber = 'QTN-' . date('Y') . '-' . strtoupper(Str::random(6));

        Quotation::create([
            'quotation_number' => $qtnNumber,
            'rfq_id' => $rfq->id,
            'supplier_id' => Auth::id(),
            'buyer_id' => $rfq->buyer_id,
            'unit_price' => $validated['unit_price'],
            'quantity' => $rfq->quantity,
            'subtotal_amount' => $subtotal,
            'shipping_cost' => $validated['shipping_cost'],
            'insurance_cost' => $insurance,
            'estimated_customs_duty' => $duty,
            'estimated_vat' => $vat,
            'total_landed_cost' => $totalLanded,
            'currency' => $rfq->currency,
            'shipping_method' => $validated['shipping_method'],
            'estimated_delivery_days' => $validated['estimated_delivery_days'],
            'validity_date' => now()->addDays((int) $validated['validity_days']),
            'payment_terms' => $validated['payment_terms'] ?? '30% Advance, 70% against Bill of Lading',
            'notes' => $validated['notes'] ?? null,
            'status' => 'sent',
        ]);

        // Update RFQ status to quoted
        $rfq->update(['status' => 'quoted']);

        return back()->with('success', "Your binding quotation ({$qtnNumber}) has been delivered to the buyer.");
    }

    /**
     * Buyer compares quotations for their RFQ
     */
    public function compare($id)
    {
        $rfq = Rfq::with(['quotations.supplier.company'])->findOrFail($id);

        if (Auth::id() !== $rfq->buyer_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('rfqs.compare', compact('rfq'));
    }

    /**
     * Buyer accepts a quotation and converts it into a purchase order
     */
    public function acceptQuotation($id)
    {
        $quotation = Quotation::with(['rfq', 'supplier.company', 'buyer.company'])->findOrFail($id);

        if (Auth::id() !== $quotation->buyer_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $quotation->update(['status' => 'accepted']);
        if ($quotation->rfq) {
            $quotation->rfq->update(['status' => 'awarded']);
            // Reject other quotations for this RFQ
            Quotation::where('rfq_id', $quotation->rfq_id)
                ->where('id', '!=', $quotation->id)
                ->update(['status' => 'rejected']);
        }

        // Convert accepted quotation to Purchase Order
        $orderNumber = 'ORD-' . date('Y') . '-' . rand(1000, 9999);
        $order = Order::create([
            'order_number' => $orderNumber,
            'buyer_id' => $quotation->buyer_id,
            'quotation_id' => $quotation->id,
            'subtotal_amount' => $quotation->subtotal_amount,
            'shipping_amount' => $quotation->shipping_cost,
            'customs_duty_amount' => $quotation->estimated_customs_duty,
            'vat_amount' => $quotation->estimated_vat,
            'discount_amount' => 0.00,
            'total_amount' => $quotation->total_landed_cost,
            'currency' => $quotation->currency,
            'payment_status' => 'pending',
            'payment_method' => 'stripe',
            'order_status' => 'order_placed',
            'shipping_address' => [
                'recipient_name' => $quotation->buyer->name,
                'company_name' => $quotation->buyer->company->company_name ?? 'UK Procurement',
                'address_line_1' => $quotation->buyer->company->address ?? 'Port of Felixstowe Logistics Hub',
                'city' => $quotation->buyer->city ?? 'London',
                'country' => 'United Kingdom',
            ],
            'destination_country' => 'United Kingdom',
            'notes' => "Generated from accepted Quotation {$quotation->quotation_number}",
        ]);

        // Create Split PO item
        OrderItem::create([
            'order_id' => $order->id,
            'purchase_order_number' => 'PO-' . rand(1000, 9999) . '-' . strtoupper(substr($quotation->supplier->country ?? 'GLB', 0, 3)),
            'supplier_id' => $quotation->supplier_id,
            'product_name' => $quotation->rfq ? $quotation->rfq->product_name : 'Custom Machined Components',
            'unit_price' => $quotation->unit_price,
            'quantity' => $quotation->quantity,
            'unit' => $quotation->rfq ? $quotation->rfq->unit : 'Pieces',
            'subtotal' => $quotation->subtotal_amount,
            'supplier_shipping_share' => $quotation->shipping_cost,
            'supplier_status' => 'accepted',
        ]);

        // Create initial tracking update
        TrackingUpdate::create([
            'order_id' => $order->id,
            'stage' => 'order_placed',
            'title' => 'Purchase Order Generated & Quotation Accepted',
            'description' => "Buyer accepted Quotation {$quotation->quotation_number}. Production order allocated to supplier.",
            'location' => 'Perlcon International Portal',
            'updated_by_user_id' => Auth::id(),
            'occurred_at' => now(),
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', "Quotation accepted! Purchase Order {$orderNumber} has been successfully generated.");
    }
}
