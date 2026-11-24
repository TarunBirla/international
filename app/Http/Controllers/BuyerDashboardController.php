<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rfq;
use App\Models\Quotation;
use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Product;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'rfqs_count' => Rfq::where('buyer_id', $user->id)->count(),
            'quotations_count' => Quotation::where('buyer_id', $user->id)->count(),
            'enquiries_count' => Enquiry::where('buyer_id', $user->id)->count(),
            'orders_count' => Order::where('buyer_id', $user->id)->count(),
            'total_spent' => Order::where('buyer_id', $user->id)->where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recentRfqs = Rfq::where('buyer_id', $user->id)->withCount('quotations')->latest()->take(5)->get();
        $recentQuotations = Quotation::where('buyer_id', $user->id)->with(['rfq', 'supplier.company'])->latest()->take(5)->get();
        $recentOrders = Order::where('buyer_id', $user->id)->with(['orderItems.supplier'])->latest()->take(5)->get();

        return view('dashboards.buyer.index', compact('user', 'stats', 'recentRfqs', 'recentQuotations', 'recentOrders'));
    }

    public function rfqs()
    {
        $rfqs = Rfq::where('buyer_id', Auth::id())->with(['category', 'quotations.supplier.company'])->latest()->paginate(10);
        return view('dashboards.buyer.rfqs', compact('rfqs'));
    }

    public function quotations()
    {
        $quotations = Quotation::where('buyer_id', Auth::id())->with(['rfq', 'supplier.company', 'product'])->latest()->paginate(10);
        return view('dashboards.buyer.quotations', compact('quotations'));
    }

    public function enquiries()
    {
        $enquiries = Enquiry::where('buyer_id', Auth::id())->with(['product.company', 'supplier.company'])->latest()->paginate(10);
        return view('dashboards.buyer.enquiries', compact('enquiries'));
    }

    public function orders()
    {
        $orders = Order::where('buyer_id', Auth::id())->with(['orderItems.supplier.company', 'shipments'])->latest()->paginate(10);
        return view('dashboards.buyer.orders', compact('orders'));
    }

    public function profile()
    {
        $user = Auth::user()->load('company');
        return view('dashboards.buyer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            // Company
            'company_name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'country' => $validated['country'],
        ]);

        if ($user->company) {
            $user->company->update([
                'company_name' => $validated['company_name'],
                'tax_id' => $validated['tax_id'],
                'address' => $validated['address'],
                'description' => $validated['description'],
            ]);
        }

        return back()->with('success', 'Profile and company information updated successfully.');
    }
}
