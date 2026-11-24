<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use App\Models\Rfq;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Featured Top Categories
        $featuredCategories = Category::whereNull('parent_id')
            ->where('is_featured', true)
            ->with(['subcategories'])
            ->take(8)
            ->get();

        $allParentCategories = Category::whereNull('parent_id')
            ->with('subcategories')
            ->orderBy('sort_order', 'asc')
            ->get();

        // 2. Featured Products
        $featuredProducts = Product::where('status', 'active')
            ->where('is_featured', true)
            ->with(['company', 'supplier'])
            ->latest()
            ->take(8)
            ->get();

        // 3. Products by Country
        $indiaProducts = Product::where('status', 'active')
            ->where('origin_country', 'India')
            ->with(['company', 'supplier'])
            ->take(4)
            ->get();

        $chinaProducts = Product::where('status', 'active')
            ->where('origin_country', 'China')
            ->with(['company', 'supplier'])
            ->take(4)
            ->get();

        $europeProducts = Product::where('status', 'active')
            ->whereIn('origin_country', ['Germany', 'United Kingdom', 'Italy', 'France'])
            ->with(['company', 'supplier'])
            ->take(4)
            ->get();

        // 4. Verified Suppliers
        $verifiedSuppliers = Company::where('is_verified', true)
            ->whereHas('user', function ($q) {
                $q->where('role', 'supplier');
            })
            ->with(['user', 'products'])
            ->take(6)
            ->get();

        // 5. Recent Public RFQs
        $recentRfqs = Rfq::with(['category', 'buyer'])
            ->latest()
            ->take(4)
            ->get();

        // 6. Platform Stats
        $stats = [
            'products_count' => Product::count(),
            'suppliers_count' => User::where('role', 'supplier')->count(),
            'buyers_count' => User::where('role', 'buyer')->count(),
            'countries_count' => 18,
        ];

        return view('home.index', compact(
            'featuredCategories',
            'allParentCategories',
            'featuredProducts',
            'indiaProducts',
            'chinaProducts',
            'europeProducts',
            'verifiedSuppliers',
            'recentRfqs',
            'stats'
        ));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function shipping()
    {
        return view('pages.shipping');
    }

    public function customs()
    {
        return view('pages.customs');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
