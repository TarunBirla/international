@extends('layouts.app')

@section('title', 'Marketplace Products — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Marketplace Product Governance</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Audit catalog listings, toggle homepage featured placement, and verify HS codes.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Buyers</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Products</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('admin.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">RFQs</a>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Reports</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Product Details</th>
                        <th class="p-4">Supplier & Origin</th>
                        <th class="p-4">HS Code Tariff</th>
                        <th class="p-4">Base Price (£)</th>
                        <th class="p-4">Featured</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($products as $product)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 flex items-center space-x-3">
                                <img src="{{ $product->main_image }}" class="w-12 h-12 rounded-xl object-cover bg-slate-100 flex-shrink-0" alt="{{ $product->name }}">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $product->name }}</h4>
                                    <span class="text-slate-400 text-[10px]">{{ $product->category->name ?? 'General' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <strong class="text-slate-800">{{ $product->company->company_name ?? 'Supplier' }}</strong>
                                <span class="text-slate-400 block text-[10px]">{{ $product->origin_country }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-slate-700">
                                {{ $product->hs_code }}
                            </td>
                            <td class="p-4 font-black text-slate-900">
                                £{{ number_format($product->base_price, 2) }}
                            </td>
                            <td class="p-4">
                                @if($product->is_featured)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Featured</span>
                                @else
                                    <span class="text-slate-400 text-[11px]">Normal</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $product->is_featured ? 'bg-slate-100 text-slate-600' : 'bg-amber-500 text-white' }}">
                                        {{ $product->is_featured ? 'Unfeature' : 'Feature on Home' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
