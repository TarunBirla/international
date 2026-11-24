<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Message;
use Illuminate\Support\Str;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'required_quantity' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_email' => 'nullable|email|max:255',
            'buyer_phone' => 'nullable|string|max:50',
            'buyer_company' => 'nullable|string|max:255',
            'buyer_country' => 'nullable|string|max:100',
        ]);

        $product = Product::with(['supplier'])->findOrFail($validated['product_id']);

        $enquiryNumber = 'ENQ-' . date('Y') . '-' . strtoupper(Str::random(5));

        $buyerName = Auth::check() ? Auth::user()->name : ($validated['buyer_name'] ?? 'Guest Buyer');
        $buyerEmail = Auth::check() ? Auth::user()->email : ($validated['buyer_email'] ?? 'guest@example.com');
        $buyerPhone = Auth::check() ? Auth::user()->phone : ($validated['buyer_phone'] ?? null);
        $buyerCompany = Auth::check() && Auth::user()->company ? Auth::user()->company->company_name : ($validated['buyer_company'] ?? null);
        $buyerCountry = Auth::check() ? Auth::user()->country : ($validated['buyer_country'] ?? 'United Kingdom');

        $enquiry = Enquiry::create([
            'enquiry_number' => $enquiryNumber,
            'product_id' => $product->id,
            'supplier_id' => $product->supplier_id,
            'buyer_id' => Auth::id(),
            'buyer_name' => $buyerName,
            'buyer_email' => $buyerEmail,
            'buyer_phone' => $buyerPhone,
            'buyer_company' => $buyerCompany,
            'buyer_country' => $buyerCountry,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'required_quantity' => $validated['required_quantity'],
            'unit' => $validated['unit'] ?? $product->unit,
            'status' => 'new',
        ]);

        // Also create initial internal message thread if authenticated
        if (Auth::check()) {
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $product->supplier_id,
                'enquiry_id' => $enquiry->id,
                'message' => $validated['message'],
                'is_read' => false,
            ]);
        }

        return back()->with('success', "Enquiry {$enquiryNumber} sent directly to {$product->supplier->name}! You will receive supplier quotations and replies via your dashboard and email.");
    }
}
