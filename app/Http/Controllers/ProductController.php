<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')->with(['company', 'category', 'supplier']);

        // Search Keyword
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('hs_code', 'like', "%{$q}%")
                    ->orWhereHas('company', function ($c) use ($q) {
                        $c->where('company_name', 'like', "%{$q}%");
                    });
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $catParam = $request->input('category');
            $category = is_numeric($catParam)
                ? Category::find($catParam)
                : Category::where('slug', $catParam)->first();
            if ($category) {
                $catIds = $category->subcategories->pluck('id')->push($category->id);
                $query->whereIn('category_id', $catIds);
            }
        }

        // Country of Origin Filter
        if ($request->filled('country')) {
            $query->where('origin_country', $request->input('country'));
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', (float) $request->input('max_price'));
        }

        // MOQ Filter
        if ($request->filled('max_moq')) {
            $query->where('moq', '<=', (int) $request->input('max_moq'));
        }

        // Verified Suppliers Only
        if ($request->boolean('verified')) {
            $query->whereHas('company', function ($c) {
                $c->where('is_verified', true);
            });
        }

        // Direct Purchase Only
        if ($request->boolean('direct_purchase')) {
            $query->where('direct_purchase_enabled', true)->where('price_on_request', false);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')->with('subcategories')->get();
        $countries = ['India', 'China', 'Germany', 'United Kingdom', 'Italy', 'France'];

        return view('products.index', compact('products', 'categories', 'countries'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['company', 'category', 'supplier', 'reviews.buyer'])
            ->firstOrFail();

        // Increment view count
        $product->increment('views_count');

        // Similar products in same category or country
        $similarProducts = Product::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('origin_country', $product->origin_country);
            })
            ->with(['company'])
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'similarProducts'));
    }
}
