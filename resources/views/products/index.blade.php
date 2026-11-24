@extends('layouts.app')

@section('title', 'Industrial Products Catalogue — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Products Catalogue</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Industrial Products & Machinery</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Browse {{ $products->total() }} verified products from certified manufacturers in India, China and Europe.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Column: Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm sticky top-24 space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-extrabold text-slate-900 flex items-center">
                        <i class="fa-solid fa-filter text-brand-700 mr-2"></i> Filters
                    </h2>
                    <a href="{{ route('products.index') }}" class="text-xs text-brand-700 hover:underline font-semibold">Reset</a>
                </div>

                <form action="{{ route('products.index') }}" method="GET" class="space-y-5">
                    <!-- Search input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Keyword</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Part name, HS code..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Category</label>
                        <select name="category" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Origin Country Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Manufacturing Country</label>
                        <select name="country" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            <option value="">All Countries</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Price Range (£)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min £" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max £" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                        </div>
                    </div>

                    <!-- Max MOQ -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Max Minimum Order (MOQ)</label>
                        <input type="number" name="max_moq" value="{{ request('max_moq') }}" placeholder="e.g. 50" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }} class="w-4 h-4 text-brand-700 rounded border-slate-300">
                            <span>Verified Manufacturers Only</span>
                        </label>
                        <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="direct_purchase" value="1" {{ request('direct_purchase') ? 'checked' : '' }} class="w-4 h-4 text-brand-700 rounded border-slate-300">
                            <span>Direct Buy (Cart Enabled)</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Products List & Sorting -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Top Bar: Results Count & Sort -->
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-3">
                <span class="text-xs font-bold text-slate-700">
                    Showing <span class="text-brand-700">{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</span> of {{ $products->total() }} Products
                </span>

                <div class="flex items-center space-x-2 text-xs font-semibold text-slate-600">
                    <span>Sort by:</span>
                    <form action="{{ route('products.index') }}" method="GET" id="sortForm">
                        @foreach(request()->except('sort') as $key => $val)
                            @if(is_array($val))
                                @foreach($val as $subVal)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endforeach
                        <select name="sort" onchange="document.getElementById('sortForm').submit()" class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Listings</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
                    <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3 block"></i>
                    <h3 class="text-lg font-bold text-slate-800">No products match your filters</h3>
                    <p class="text-slate-500 text-xs mt-1">Try resetting or broadening your filter criteria.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 px-4 py-2 bg-brand-700 text-white font-bold rounded-xl text-xs">Reset All Filters</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                            <div>
                                <!-- Image & Tags -->
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    
                                    <div class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur-sm px-2.5 py-1 rounded-full text-[11px] font-bold text-slate-800 shadow-sm flex items-center space-x-1">
                                        <span>{{ $product->origin_country === 'India' ? '🇮🇳' : ($product->origin_country === 'China' ? '🇨🇳' : '🇩🇪') }}</span>
                                        <span>{{ $product->origin_country }}</span>
                                    </div>

                                    @if($product->company && $product->company->is_verified)
                                        <div class="absolute top-2.5 right-2.5 bg-emerald-600 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase shadow">
                                            Verified
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <span class="text-[10px] font-mono font-bold text-slate-400 block mb-1">HS: {{ $product->hs_code }}</span>
                                    <h3 class="font-bold text-slate-900 text-sm leading-snug hover:text-brand-700 transition line-clamp-2 mb-1.5">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    
                                    <div class="text-xs text-slate-500 mb-3 truncate">
                                        By <strong class="text-slate-700">{{ $product->company->company_name ?? 'Industrial Supplier' }}</strong>
                                    </div>

                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 mb-3">
                                        <div class="flex items-baseline justify-between">
                                            <div>
                                                @if($product->price_on_request)
                                                    <span class="text-xs font-extrabold text-amber-600">Price on Request</span>
                                                @else
                                                    <span class="text-base font-black text-slate-900">£{{ number_format($product->base_price, 2) }}</span>
                                                    <span class="text-[10px] text-slate-500">/ {{ $product->unit }}</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] font-semibold text-slate-500">
                                                MOQ: <span class="text-slate-900 font-bold">{{ $product->moq }} {{ $product->unit }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 pt-0 grid grid-cols-2 gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2 px-2 rounded-xl text-xs font-bold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                                    Details
                                </a>

                                @if($product->direct_purchase_enabled && !$product->price_on_request)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="{{ $product->moq }}">
                                        <button type="submit" class="w-full text-center py-2 px-2 rounded-xl text-xs font-bold bg-brand-700 hover:bg-brand-800 text-white transition flex items-center justify-center space-x-1">
                                            <i class="fa-solid fa-cart-plus text-[10px]"></i>
                                            <span>Buy Now</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('products.show', $product->slug) }}#enquiry" class="text-center py-2 px-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white transition">
                                        Enquiry
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
