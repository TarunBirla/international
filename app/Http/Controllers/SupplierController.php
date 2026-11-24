<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Product;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::whereHas('user', function ($q) {
            $q->where('role', 'supplier')->where('status', 'active');
        })->with(['user', 'products']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('company_name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('manufacturing_capabilities', 'like', "%{$q}%");
            });
        }

        if ($request->filled('country')) {
            $query->where('country', $request->input('country'));
        }

        if ($request->filled('business_type')) {
            $query->where('business_type', $request->input('business_type'));
        }

        if ($request->boolean('verified')) {
            $query->where('is_verified', true);
        }

        $suppliers = $query->paginate(9)->withQueryString();
        $countries = ['India', 'China', 'Germany', 'United Kingdom', 'Italy', 'France'];

        return view('suppliers.index', compact('suppliers', 'countries'));
    }

    public function show($slug)
    {
        $company = Company::where('slug', $slug)
            ->whereHas('user', function ($q) {
                $q->where('role', 'supplier');
            })
            ->with(['user', 'products.category', 'reviews.buyer'])
            ->firstOrFail();

        $products = Product::where('company_id', $company->id)
            ->where('status', 'active')
            ->paginate(12);

        return view('suppliers.show', compact('company', 'products'));
    }
}
