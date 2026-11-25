@extends('layouts.admin')

@section('title', 'Manage Products — Mart Admin')
@section('header_title', 'Marketplace Products Catalog')
@section('header_subtitle', 'Audit industrial equipment listings, toggle featured status, and verify international HS codes')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            <!-- Category & Origin Filter -->
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.products') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <select name="category_id" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="country" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="">All Origins</option>
                        <option value="India" {{ request('country') === 'India' ? 'selected' : '' }}>🇮🇳 India</option>
                        <option value="China" {{ request('country') === 'China' ? 'selected' : '' }}>🇨🇳 China</option>
                        <option value="Germany" {{ request('country') === 'Germany' ? 'selected' : '' }}>🇩🇪 Germany / Europe</option>
                    </select>

                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>
            </div>

            <!-- Search and Add Product Button -->
            <div class="flex items-center space-x-3 w-full lg:w-auto">
                <form action="{{ route('admin.products') }}" method="GET" class="relative flex-1 lg:w-64">
                    @if(request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    @endif
                    @if(request('country'))
                        <input type="hidden" name="country" value="{{ request('country') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, SKU, HS code..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>

                <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5 flex-shrink-0">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Add Product</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Product Details</th>
                        <th class="p-4">Manufacturer / Plant</th>
                        <th class="p-4">HS Code Tariff</th>
                        <th class="p-4">Base Price & MOQ</th>
                        <th class="p-4">Stock & Status</th>
                        <th class="p-4">Featured</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- Thumbnail & Product Name -->
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $product->main_image }}" class="w-12 h-12 rounded-xl object-cover bg-slate-100 border border-slate-200 flex-shrink-0" alt="{{ $product->name }}">
                                    <div>
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="font-extrabold text-slate-900 text-xs hover:text-brand-700 block">
                                            {{ $product->name }}
                                        </a>
                                        <span class="text-slate-400 text-[10px]">{{ $product->category->name ?? 'General' }} • SKU: {{ $product->sku ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Supplier / Country -->
                            <td class="p-4">
                                <strong class="text-slate-900 block font-bold">{{ $product->company->company_name ?? 'Supplier' }}</strong>
                                <span class="text-slate-400 text-[10px]">{{ $product->origin_country }}</span>
                            </td>

                            <!-- HS Code -->
                            <td class="p-4">
                                <span class="font-mono bg-slate-100 text-slate-800 px-2 py-0.5 rounded text-[11px] font-bold">
                                    {{ $product->hs_code }}
                                </span>
                            </td>

                            <!-- Price & MOQ -->
                            <td class="p-4">
                                <span class="font-black text-slate-900 block">
                                    {{ $product->currency }} {{ number_format($product->base_price, 2) }}
                                </span>
                                <span class="text-slate-400 text-[10px]">MOQ: {{ $product->moq }} {{ $product->unit }}</span>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($product->status ?? 'active') }}
                                </span>
                            </td>

                            <!-- Featured Toggle -->
                            <td class="p-4">
                                <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-black transition {{ $product->is_featured ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $product->is_featured ? '★ Featured' : '☆ Standard' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                                       title="View Public Storefront"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       title="Edit Product"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-700 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Permanently delete product {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Product"
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-boxes-stacked text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No products found matching your filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
