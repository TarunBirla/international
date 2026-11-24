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
use App\Models\TrackingUpdate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_buyers' => User::where('role', 'buyer')->count(),
            'total_suppliers' => User::where('role', 'supplier')->count(),
            'verified_suppliers' => Company::where('is_verified', true)->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_rfqs' => Rfq::count(),
            'total_quotations' => Quotation::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'active_shipments' => Shipment::whereNotIn('status', ['delivered'])->count(),
            'pending_customs' => CustomsClearance::whereNotIn('status', ['cleared'])->count(),
        ];

        $recentOrders = Order::with(['buyer.company'])->latest()->take(6)->get();
        $recentRfqs = Rfq::with(['buyer.company'])->latest()->take(5)->get();
        $recentSuppliers = User::where('role', 'supplier')->with('company')->latest()->take(5)->get();
        $recentUsers = User::latest()->take(6)->get();

        return view('dashboards.admin.index', compact('stats', 'recentOrders', 'recentRfqs', 'recentSuppliers', 'recentUsers'));
    }

    // =========================================================================
    // 1. ALL USERS CRUD
    // =========================================================================
    public function users(Request $request)
    {
        $query = User::with('company');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = ['super_admin', 'admin', 'buyer', 'supplier', 'logistics_partner', 'customs_partner'];

        return view('dashboards.admin.users.index', compact('users', 'roles'));
    }

    public function createUser()
    {
        $roles = [
            'super_admin' => 'Super Administrator',
            'admin' => 'Operations Admin',
            'buyer' => 'Verified Buyer',
            'supplier' => 'Industrial Supplier / Manufacturer',
            'logistics_partner' => 'Logistics & Freight Partner',
            'customs_partner' => 'Customs Clearance Partner (Junaid & Co.)',
        ];
        return view('dashboards.admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:super_admin,admin,buyer,supplier,logistics_partner,customs_partner',
            'password' => 'required|string|min:6',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'is_verified' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['company_name'])) {
            Company::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'slug' => Str::slug($validated['company_name']) . '-' . rand(100, 999),
                'business_type' => $validated['business_type'] ?? 'Manufacturer',
                'country' => $validated['country'],
                'city' => $validated['city'] ?? null,
                'is_verified' => $request->boolean('is_verified'),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' created successfully.");
    }

    public function editUser($id)
    {
        $user = User::with('company')->findOrFail($id);
        $roles = [
            'super_admin' => 'Super Administrator',
            'admin' => 'Operations Admin',
            'buyer' => 'Verified Buyer',
            'supplier' => 'Industrial Supplier / Manufacturer',
            'logistics_partner' => 'Logistics & Freight Partner',
            'customs_partner' => 'Customs Clearance Partner (Junaid & Co.)',
        ];
        return view('dashboards.admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:super_admin,admin,buyer,supplier,logistics_partner,customs_partner',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'nullable|string|min:6',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'is_verified' => 'nullable|boolean',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if (!empty($validated['company_name'])) {
            $company = Company::firstOrNew(['user_id' => $user->id]);
            $company->company_name = $validated['company_name'];
            if (!$company->exists) {
                $company->slug = Str::slug($validated['company_name']) . '-' . rand(100, 999);
            }
            $company->business_type = $validated['business_type'] ?? 'Manufacturer';
            $company->country = $validated['country'];
            $company->city = $validated['city'] ?? null;
            $company->is_verified = $request->boolean('is_verified');
            $company->save();
        }

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' updated successfully.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own logged-in administrator account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User '{$name}' deleted successfully.");
    }

    // =========================================================================
    // 2. SUPPLIERS CRUD & VERIFICATION
    // =========================================================================
    public function suppliers(Request $request)
    {
        $query = User::where('role', 'supplier')->with(['company', 'products']);

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%");
                  });
            });
        }

        $suppliers = $query->latest()->paginate(12)->withQueryString();
        return view('dashboards.admin.suppliers', compact('suppliers'));
    }

    public function createSupplier()
    {
        return view('dashboards.admin.suppliers_create');
    }

    public function storeSupplier(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'export_markets' => 'nullable|string|max:255',
            'manufacturing_capabilities' => 'nullable|string',
            'description' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'supplier',
            'password' => Hash::make($validated['password']),
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Company::create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'],
            'slug' => Str::slug($validated['company_name']) . '-' . rand(100, 999),
            'business_type' => $validated['business_type'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'export_markets' => $validated['export_markets'] ?? 'United Kingdom, Europe, USA',
            'manufacturing_capabilities' => $validated['manufacturing_capabilities'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_verified' => $request->boolean('is_verified'),
        ]);

        return redirect()->route('admin.suppliers')->with('success', "Supplier '{$validated['company_name']}' created successfully.");
    }

    public function editSupplier($id)
    {
        $supplier = User::where('role', 'supplier')->with('company')->findOrFail($id);
        return view('dashboards.admin.suppliers_edit', compact('supplier'));
    }

    public function updateSupplier(Request $request, $id)
    {
        $supplier = User::where('role', 'supplier')->with('company')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $supplier->id,
            'phone' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'export_markets' => 'nullable|string|max:255',
            'manufacturing_capabilities' => 'nullable|string',
            'description' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
        ]);

        $supplier->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
        ]);

        if ($supplier->company) {
            $supplier->company->update([
                'company_name' => $validated['company_name'],
                'business_type' => $validated['business_type'],
                'country' => $validated['country'],
                'city' => $validated['city'] ?? null,
                'export_markets' => $validated['export_markets'] ?? null,
                'manufacturing_capabilities' => $validated['manufacturing_capabilities'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_verified' => $request->boolean('is_verified'),
            ]);
        }

        return redirect()->route('admin.suppliers')->with('success', "Supplier details updated successfully.");
    }

    public function deleteSupplier($id)
    {
        $supplier = User::where('role', 'supplier')->findOrFail($id);
        $name = $supplier->company->company_name ?? $supplier->name;
        $supplier->delete();

        return redirect()->route('admin.suppliers')->with('success', "Supplier '{$name}' deleted successfully.");
    }

    public function toggleSupplierVerification($id)
    {
        $company = Company::findOrFail($id);
        $company->is_verified = !$company->is_verified;
        $company->save();

        $status = $company->is_verified ? 'verified' : 'unverified';
        return back()->with('success', "Supplier {$company->company_name} is now {$status}.");
    }

    // =========================================================================
    // 3. BUYERS CRUD
    // =========================================================================
    public function buyers(Request $request)
    {
        $query = User::where('role', 'buyer')->with(['company', 'orders', 'rfqs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $buyers = $query->latest()->paginate(12)->withQueryString();
        return view('dashboards.admin.buyers', compact('buyers'));
    }

    public function createBuyer()
    {
        return view('dashboards.admin.buyers_create');
    }

    public function storeBuyer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'buyer',
            'password' => Hash::make($validated['password']),
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['company_name'])) {
            Company::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'slug' => Str::slug($validated['company_name']) . '-' . rand(100, 999),
                'business_type' => $validated['business_type'] ?? 'Procurement Contractor',
                'country' => $validated['country'],
                'city' => $validated['city'] ?? null,
                'is_verified' => true,
            ]);
        }

        return redirect()->route('admin.buyers')->with('success', "Buyer '{$user->name}' created successfully.");
    }

    public function editBuyer($id)
    {
        $buyer = User::where('role', 'buyer')->with('company')->findOrFail($id);
        return view('dashboards.admin.buyers_edit', compact('buyer'));
    }

    public function updateBuyer(Request $request, $id)
    {
        $buyer = User::where('role', 'buyer')->with('company')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $buyer->id,
            'phone' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
        ]);

        $buyer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
        ]);

        if (!empty($validated['company_name'])) {
            $company = Company::firstOrNew(['user_id' => $buyer->id]);
            $company->company_name = $validated['company_name'];
            if (!$company->exists) {
                $company->slug = Str::slug($validated['company_name']) . '-' . rand(100, 999);
            }
            $company->business_type = $validated['business_type'] ?? 'Procurement Contractor';
            $company->country = $validated['country'];
            $company->city = $validated['city'] ?? null;
            $company->save();
        }

        return redirect()->route('admin.buyers')->with('success', "Buyer updated successfully.");
    }

    public function deleteBuyer($id)
    {
        $buyer = User::where('role', 'buyer')->findOrFail($id);
        $name = $buyer->name;
        $buyer->delete();

        return redirect()->route('admin.buyers')->with('success', "Buyer '{$name}' deleted successfully.");
    }

    // =========================================================================
    // 4. CATEGORIES CRUD
    // =========================================================================
    public function categories()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['subcategories' => function ($q) {
                $q->withCount('products');
            }])
            ->withCount('products')
            ->get();

        $allParents = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('dashboards.admin.categories', compact('categories', 'allParents'));
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

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($validated['parent_id'] && $validated['parent_id'] == $category->id) {
            return back()->with('error', 'Category cannot be its own parent.');
        }

        $category->update([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'icon' => $validated['icon'] ?? $category->icon,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return back()->with('success', "Category '{$category->name}' updated successfully.");
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;

        // Move subcategories to root
        Category::where('parent_id', $category->id)->update(['parent_id' => null]);

        $category->delete();

        return back()->with('success', "Category '{$name}' deleted successfully.");
    }

    // =========================================================================
    // 5. PRODUCTS CRUD
    // =========================================================================
    public function products(Request $request)
    {
        $query = Product::with(['company', 'category']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('country')) {
            $query->where('origin_country', $request->country);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('hs_code', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('dashboards.admin.products', compact('products', 'categories'));
    }

    public function createProduct()
    {
        $suppliers = User::where('role', 'supplier')->with('company')->get();
        $categories = Category::whereNotNull('parent_id')->orWhereDoesntHave('subcategories')->get();
        return view('dashboards.admin.products_create', compact('suppliers', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'hs_code' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'moq' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'origin_country' => 'required|string|max:100',
            'short_description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'weight_kg' => 'nullable|numeric',
            'lead_time_days' => 'nullable|integer',
            'main_image' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'direct_purchase_enabled' => 'nullable|boolean',
        ]);

        $supplier = User::with('company')->findOrFail($validated['supplier_id']);

        $product = Product::create([
            'supplier_id' => $supplier->id,
            'company_id' => $supplier->company->id ?? null,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'sku' => $validated['sku'] ?? 'PRD-' . strtoupper(Str::random(6)),
            'hs_code' => $validated['hs_code'],
            'base_price' => $validated['base_price'],
            'currency' => $validated['currency'],
            'moq' => $validated['moq'],
            'unit' => $validated['unit'],
            'origin_country' => $validated['origin_country'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'] ?? null,
            'weight_kg' => $validated['weight_kg'] ?? 10.0,
            'lead_time_days' => $validated['lead_time_days'] ?? 14,
            'main_image' => $validated['main_image'] ?? 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&q=80',
            'is_featured' => $request->boolean('is_featured'),
            'direct_purchase_enabled' => $request->boolean('direct_purchase_enabled', true),
            'status' => 'active',
            'stock_quantity' => 100,
        ]);

        return redirect()->route('admin.products')->with('success', "Product '{$product->name}' created successfully.");
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $suppliers = User::where('role', 'supplier')->with('company')->get();
        $categories = Category::all();
        return view('dashboards.admin.products_edit', compact('product', 'suppliers', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'hs_code' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'moq' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'origin_country' => 'required|string|max:100',
            'short_description' => 'required|string|max:500',
            'full_description' => 'nullable|string',
            'weight_kg' => 'nullable|numeric',
            'lead_time_days' => 'nullable|integer',
            'main_image' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'direct_purchase_enabled' => 'nullable|boolean',
            'status' => 'required|in:active,inactive,out_of_stock',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'] ?? $product->sku,
            'hs_code' => $validated['hs_code'],
            'base_price' => $validated['base_price'],
            'currency' => $validated['currency'],
            'moq' => $validated['moq'],
            'unit' => $validated['unit'],
            'origin_country' => $validated['origin_country'],
            'short_description' => $validated['short_description'],
            'full_description' => $validated['full_description'] ?? null,
            'weight_kg' => $validated['weight_kg'] ?? $product->weight_kg,
            'lead_time_days' => $validated['lead_time_days'] ?? $product->lead_time_days,
            'main_image' => $validated['main_image'] ?? $product->main_image,
            'is_featured' => $request->boolean('is_featured'),
            'direct_purchase_enabled' => $request->boolean('direct_purchase_enabled'),
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.products')->with('success', "Product '{$product->name}' updated successfully.");
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products')->with('success', "Product '{$name}' deleted successfully.");
    }

    public function toggleProductFeatured($id)
    {
        $product = Product::findOrFail($id);
        $product->is_featured = !$product->is_featured;
        $product->save();

        return back()->with('success', 'Product featured status updated.');
    }

    // =========================================================================
    // 6. ORDERS MANAGEMENT
    // =========================================================================
    public function orders(Request $request)
    {
        $query = Order::with(['buyer.company', 'orderItems.supplier.company', 'shipments', 'customsClearances']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(12)->withQueryString();
        return view('dashboards.admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,customs_clearance,out_for_delivery,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,partial,refunded',
        ]);

        $order->update($validated);

        return back()->with('success', "Order #{$order->order_number} status updated.");
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $num = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders')->with('success', "Order #{$num} deleted successfully.");
    }

    // =========================================================================
    // 7. RFQS MANAGEMENT
    // =========================================================================
    public function rfqs(Request $request)
    {
        $query = Rfq::with(['buyer.company', 'category'])->withCount('quotations');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('rfq_number', 'like', "%{$search}%");
            });
        }

        $rfqs = $query->latest()->paginate(12)->withQueryString();
        return view('dashboards.admin.rfqs', compact('rfqs'));
    }

    public function updateRfqStatus(Request $request, $id)
    {
        $rfq = Rfq::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:open,under_review,closed,awarded',
        ]);

        $rfq->update($validated);

        return back()->with('success', "RFQ #{$rfq->rfq_number} status updated.");
    }

    public function deleteRfq($id)
    {
        $rfq = Rfq::findOrFail($id);
        $num = $rfq->rfq_number;
        $rfq->delete();

        return redirect()->route('admin.rfqs')->with('success', "RFQ #{$num} deleted successfully.");
    }

    // =========================================================================
    // 8. SHIPMENTS & CUSTOMS CLEARANCES
    // =========================================================================
    public function shipments()
    {
        $shipments = Shipment::with(['order.buyer.company', 'logisticsPartner', 'trackingUpdates'])->latest()->paginate(12);
        return view('dashboards.admin.shipments', compact('shipments'));
    }

    public function updateShipment(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:booked,dispatched,in_transit,port_arrival,customs_cleared,out_for_delivery,delivered',
            'current_location' => 'nullable|string|max:255',
            'status_note' => 'nullable|string|max:500',
        ]);

        $shipment->update([
            'status' => $validated['status'],
            'current_location' => $validated['current_location'] ?? $shipment->current_location,
        ]);

        if (!empty($validated['status_note'])) {
            TrackingUpdate::create([
                'shipment_id' => $shipment->id,
                'status' => $validated['status'],
                'location' => $validated['current_location'] ?? 'In Transit',
                'description' => $validated['status_note'],
                'updated_by' => auth()->id(),
            ]);
        }

        return back()->with('success', "Shipment #{$shipment->tracking_number} updated successfully.");
    }

    public function customs()
    {
        $customs = CustomsClearance::with(['order.buyer', 'shipment'])->latest()->paginate(12);
        return view('dashboards.admin.customs', compact('customs'));
    }

    public function updateCustoms(Request $request, $id)
    {
        $declaration = CustomsClearance::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,docs_submitted,inspection,duty_assessed,cleared,rejected',
            'notes' => 'nullable|string',
        ]);

        $declaration->update($validated);

        return back()->with('success', "Customs filing #{$declaration->c88_reference} updated.");
    }

    // =========================================================================
    // 9. REPORTS & CSV EXPORT
    // =========================================================================
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
