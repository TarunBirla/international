<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['subcategories' => function($q) {
                $q->withCount('products')->orderBy('sort_order', 'asc');
            }])
            ->withCount('products')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['subcategories' => function($q) {
                $q->withCount('products')->orderBy('sort_order', 'asc');
            }, 'parent'])
            ->firstOrFail();

        $catIds = $category->subcategories->pluck('id')->push($category->id);

        $products = Product::where('status', 'active')
            ->whereIn('category_id', $catIds)
            ->with(['company', 'supplier'])
            ->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
