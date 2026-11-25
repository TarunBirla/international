<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Quotation;
use App\Models\OrderItem;
use App\Models\Company;
use App\Models\SubscriptionPlan;
use App\Models\SupplierSubscription;
use App\Models\Rfq;
use Illuminate\Support\Str;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'products_count' => Product::where('supplier_id', $user->id)->count(),
            'enquiries_count' => Enquiry::where('supplier_id', $user->id)->count(),
            'quotations_count' => Quotation::where('supplier_id', $user->id)->count(),
            'accepted_quotations' => Quotation::where('supplier_id', $user->id)->where('status', 'accepted')->count(),
            'order_items_count' => OrderItem::where('supplier_id', $user->id)->count(),
            'total_sales' => OrderItem::where('supplier_id', $user->id)->sum('subtotal'),
        ];

        $recentEnquiries = Enquiry::where('supplier_id', $user->id)->with('product')->latest()->take(5)->get();
        $recentQuotations = Quotation::where('supplier_id', $user->id)->with(['rfq', 'buyer'])->latest()->take(5)->get();
        $recentOrders = OrderItem::where('supplier_id', $user->id)->with(['order.buyer', 'product'])->latest()->take(5)->get();

        return view('dashboards.supplier.index', compact('user', 'stats', 'recentEnquiries', 'recentQuotations', 'recentOrders'));
    }

    // --- Product Management ---
    public function products()
    {
        $products = Product::where('supplier_id', Auth::id())->with('category')->latest()->paginate(10);
        return view('dashboards.supplier.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        return view('dashboards.supplier.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100',
            'hs_code' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'price_on_request' => 'nullable|boolean',
            'moq' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'weight_kg' => 'required|numeric|min:0.1',
            'dimensions' => 'nullable|string|max:100',
            'lead_time_days' => 'required|integer|min:1',
            'manufacturing_capacity' => 'nullable|string|max:100',
            'origin_country' => 'required|string|max:100',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'main_image' => 'nullable',
            'direct_purchase_enabled' => 'nullable|boolean',
            'status' => 'required|in:active,draft,out_of_stock',
        ]);

        $mainImagePath = 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&h=600&fit=crop';
        $uploadedFile = $request->file('image') ?? $request->file('main_image');
        if ($uploadedFile && $uploadedFile->isValid()) {
            $uploadDir = public_path('uploads/products');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move($uploadDir, $filename);
            $mainImagePath = 'uploads/products/' . $filename;
        } elseif ($request->filled('main_image') && is_string($request->input('main_image'))) {
            $mainImagePath = $request->input('main_image');
        }

        $company = Auth::user()->company;

        $product = Product::create([
            'supplier_id' => Auth::id(),
            'company_id' => $company->id ?? null,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'sku' => $validated['sku'] ?? 'PRD-' . rand(1000, 9999),
            'hs_code' => $validated['hs_code'],
            'base_price' => $validated['base_price'],
            'currency' => $validated['currency'],
            'price_on_request' => $request->boolean('price_on_request'),
            'moq' => $validated['moq'],
            'unit' => $validated['unit'],
            'weight_kg' => $validated['weight_kg'],
            'dimensions' => $validated['dimensions'],
            'lead_time_days' => $validated['lead_time_days'],
            'manufacturing_capacity' => $validated['manufacturing_capacity'],
            'origin_country' => $validated['origin_country'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'],
            'main_image' => $mainImagePath,
            'direct_purchase_enabled' => $request->boolean('direct_purchase_enabled', true),
            'status' => $validated['status'],
        ]);

        return redirect()->route('supplier.products')->with('success', 'Product listed successfully on Perlcon marketplace!');
    }

    public function editProduct($id)
    {
        $product = Product::where('supplier_id', Auth::id())->findOrFail($id);
        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        return view('dashboards.supplier.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::where('supplier_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100',
            'hs_code' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'moq' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'weight_kg' => 'required|numeric|min:0.1',
            'dimensions' => 'nullable|string|max:100',
            'lead_time_days' => 'required|integer|min:1',
            'manufacturing_capacity' => 'nullable|string|max:100',
            'origin_country' => 'required|string|max:100',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'main_image' => 'nullable',
            'status' => 'required|in:active,draft,out_of_stock',
        ]);

        $mainImagePath = $product->getRawOriginal('main_image') ?? $product->main_image;
        $uploadedFile = $request->file('image') ?? $request->file('main_image');
        if ($uploadedFile && $uploadedFile->isValid()) {
            $uploadDir = public_path('uploads/products');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move($uploadDir, $filename);
            $mainImagePath = 'uploads/products/' . $filename;
        } elseif ($request->filled('main_image') && is_string($request->input('main_image'))) {
            $mainImagePath = $request->input('main_image');
        }

        $product->update(array_merge($validated, [
            'main_image' => $mainImagePath,
            'price_on_request' => $request->boolean('price_on_request'),
            'direct_purchase_enabled' => $request->boolean('direct_purchase_enabled'),
        ]));

        return redirect()->route('supplier.products')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::where('supplier_id', Auth::id())->findOrFail($id);
        $product->delete();
        return back()->with('success', 'Product removed from catalogue.');
    }

    // --- Enquiry Management ---
    public function enquiries()
    {
        $enquiries = Enquiry::where('supplier_id', Auth::id())->with(['product', 'buyer'])->latest()->paginate(10);
        return view('dashboards.supplier.enquiries', compact('enquiries'));
    }

    // --- Quotations Management ---
    public function quotations()
    {
        $quotations = Quotation::where('supplier_id', Auth::id())->with(['rfq', 'buyer.company', 'product'])->latest()->paginate(10);
        return view('dashboards.supplier.quotations', compact('quotations'));
    }

    // --- Orders Management ---
    public function orders()
    {
        $orderItems = OrderItem::where('supplier_id', Auth::id())->with(['order.buyer.company', 'product'])->latest()->paginate(10);
        return view('dashboards.supplier.orders', compact('orderItems'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $orderItem = OrderItem::where('supplier_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'supplier_status' => 'required|in:pending,accepted,in_production,ready_for_dispatch,dispatched',
            'supplier_tracking_number' => 'nullable|string|max:100',
            'supplier_notes' => 'nullable|string',
        ]);

        $orderItem->update($validated);

        return back()->with('success', 'Order processing status updated.');
    }

    // --- Company Profile ---
    public function profile()
    {
        $user = Auth::user()->load('company');
        return view('dashboards.supplier.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string',
            'country' => 'required|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'year_established' => 'nullable|string',
            'employees_count' => 'nullable|string',
            'annual_turnover' => 'nullable|string',
            'export_markets' => 'nullable|string',
            'description' => 'nullable|string',
            'manufacturing_capabilities' => 'nullable|string',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'company_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/companies'), $filename);
            $logoPath = 'uploads/companies/' . $filename;
            $validated['logo'] = $logoPath;
            $user->update(['avatar' => $logoPath]);
        }

        if ($company) {
            $company->update($validated);
        }

        return back()->with('success', 'Company profile and factory capabilities updated.');
    }

    // --- Subscriptions ---
    public function subscriptions()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $currentSub = Auth::user()->activeSubscription;
        return view('dashboards.supplier.subscriptions', compact('plans', 'currentSub'));
    }

    public function subscribe(Request $request, $planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $supplier = Auth::user();

        // Expire any existing active subscription
        SupplierSubscription::where('supplier_id', $supplier->id)->update(['status' => 'expired']);

        // Create new active subscription
        SupplierSubscription::create([
            'supplier_id' => $supplier->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => 'active',
            'payment_reference' => 'SUB-DEMO-' . rand(1000, 9999),
        ]);

        return back()->with('success', "Subscribed to {$plan->name} successfully!");
    }
}
