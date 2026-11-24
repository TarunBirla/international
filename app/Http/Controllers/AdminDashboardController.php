<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Rfq;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\CustomsClearance;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buyers' => User::where('role', 'buyer')->count(),
            'total_suppliers' => User::where('role', 'supplier')->count(),
            'verified_suppliers' => Company::where('is_verified', true)->count(),
            'total_products' => Product::count(),
            'total_rfqs' => Rfq::count(),
            'total_quotations' => Quotation::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'active_shipments' => Shipment::whereNotIn('status', ['delivered'])->count(),
            'pending_customs' => CustomsClearance::whereNotIn('status', ['cleared'])->count(),
        ];

        $recentOrders = Order::with(['buyer.company'])->latest()->take(5)->get();
        $recentRfqs = Rfq::with(['buyer.company'])->latest()->take(5)->get();
        $recentSuppliers = User::where('role', 'supplier')->with('company')->latest()->take(5)->get();

        return view('dashboards.admin.index', compact('stats', 'recentOrders', 'recentRfqs', 'recentSuppliers'));
    }

    // --- Supplier Management & Verification ---
    public function suppliers()
    {
        $suppliers = User::where('role', 'supplier')->with(['company', 'products'])->latest()->paginate(10);
        return view('dashboards.admin.suppliers', compact('suppliers'));
    }

    public function toggleSupplierVerification($id)
    {
        $company = Company::findOrFail($id);
        $company->is_verified = !$company->is_verified;
        $company->save();

        $status = $company->is_verified ? 'verified' : 'unverified';
        return back()->with('success', "Supplier {$company->company_name} is now {$status}.");
    }

    // --- Buyer Management ---
    public function buyers()
    {
        $buyers = User::where('role', 'buyer')->with(['company', 'orders', 'rfqs'])->latest()->paginate(10);
        return view('dashboards.admin.buyers', compact('buyers'));
    }

    // --- Product Management ---
    public function products()
    {
        $products = Product::with(['company', 'category'])->latest()->paginate(10);
        return view('dashboards.admin.products', compact('products'));
    }

    public function toggleProductFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        return back()->with('success', 'Product featured status updated.');
    }

    // --- Category Management ---
    public function categories()
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->withCount('products')->get();
        return view('dashboards.admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'parent_id' => $validated['parent_id'] ?? null,
            'icon' => $validated['icon'] ?? 'fa-cube',
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    // --- Orders Management ---
    public function orders()
    {
        $orders = Order::with(['buyer.company', 'orderItems.supplier.company', 'shipments', 'customsClearances'])->latest()->paginate(10);
        return view('dashboards.admin.orders', compact('orders'));
    }

    // --- RFQs Management ---
    public function rfqs()
    {
        $rfqs = Rfq::with(['buyer.company', 'category'])->withCount('quotations')->latest()->paginate(10);
        return view('dashboards.admin.rfqs', compact('rfqs'));
    }

    // --- Reports & CSV Export ---
    public function reports()
    {
        $countryWiseOrders = Order::selectRaw('destination_country, count(*) as count, sum(total_amount) as total')
            ->groupBy('destination_country')
            ->get();

        $salesByMonth = Order::selectRaw("strftime('%Y-%m', created_at) as month, sum(total_amount) as total")
            ->groupBy('month')
            ->get();

        return view('dashboards.admin.reports', compact('countryWiseOrders', 'salesByMonth'));
    }

    public function exportOrdersCsv()
    {
        $orders = Order::with(['buyer', 'orderItems'])->get();

        $filename = "perlcon_orders_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Order Number', 'Buyer Name', 'Total (£)', 'Currency', 'Payment Status', 'Order Status', 'Date'];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->buyer->name ?? 'N/A',
                    $order->total_amount,
                    $order->currency,
                    $order->payment_status,
                    $order->order_status,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
