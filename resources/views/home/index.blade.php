@extends('layouts.app')

@section('title', 'Mart — B2B Marketplace & Online Sourcing')

@section('content')

<!-- Hero Section: IndiaMART Style Sourcing & Quick RFQ -->
<section class="relative bg-white border-b border-slate-100 py-10 md:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left 7 Cols: Value Proposition & Search Bar -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Simple & Verified B2B Sourcing</span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Find & Buy Products from <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-700 via-blue-600 to-indigo-700">Verified Manufacturers</span>
                </h1>

                <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl">
                    Mart connects wholesale buyers directly with manufacturers and verified suppliers. Post your requirement, get instant quotations, and buy quality products at factory prices.
                </p>

                <!-- Search Box -->
                <div class="bg-white p-2.5 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 flex items-center px-3.5 bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-magnifying-glass text-slate-400 mr-2.5"></i>
                            <input type="text" name="q" placeholder="Enter product name, machinery, chemical, or supplier..." class="w-full py-3 bg-transparent text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                        </div>
                        <select name="country" class="px-3.5 py-3 bg-slate-50 rounded-xl text-xs font-semibold text-slate-700 border-none focus:outline-none cursor-pointer">
                            <option value="">All Locations</option>
                            <option value="India">🇮🇳 India</option>
                            <option value="China">🇨🇳 China</option>
                            <option value="Germany">🇩🇪 Germany</option>
                            <option value="United Kingdom">🇬🇧 UK</option>
                        </select>
                        <button type="submit" class="px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-sm transition shadow-md shadow-brand-700/20 flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search</span>
                        </button>
                    </form>
                </div>

                <!-- Trust Badges -->
                <div class="pt-2 flex flex-wrap items-center gap-6 text-xs text-slate-600 font-semibold">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>Verified Manufacturers</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-tags text-brand-600 text-base"></i>
                        <span>Direct Factory Rates</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-blue-600 text-base"></i>
                        <span>Safe Trade Protection</span>
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Quick RFQ Box (Post Requirement) -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl border border-slate-200 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-brand-600 to-blue-600"></div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Fast Free Quote</span>
                            <h2 class="text-xl font-black text-slate-900">Post Your Requirement</h2>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                    </div>
                    
                    <p class="text-xs text-slate-500 mb-5">
                        Tell us what you need. Receive quotations directly from verified suppliers.
                    </p>

                    <form action="{{ route('rfqs.store') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Product Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="product_name" required placeholder="e.g. Hydraulic Press, Solar Panel, TMT Steel..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Quantity <span class="text-rose-500">*</span></label>
                                <input type="number" name="quantity" min="1" required placeholder="10" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Unit</label>
                                <select name="unit" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                    <option value="Pieces">Pieces</option>
                                    <option value="Units">Units</option>
                                    <option value="Sets">Sets</option>
                                    <option value="Metric Tons">Metric Tons</option>
                                    <option value="Bundles">Bundles</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Requirements / Details</label>
                            <textarea name="technical_specifications" rows="2" required placeholder="Describe material grade, size, power or delivery requirements..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Currency</label>
                                <select name="currency" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                    <option value="GBP">GBP (£)</option>
                                    <option value="USD">USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Destination</label>
                                <input type="text" name="destination_country" value="United Kingdom" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-emerald-600/25 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Submit Requirement</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section 2: 5 Core Categories Grid -->
<section class="py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Browse by Industry</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Core Product Categories</h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-1.5">Explore 5 verified industry sectors for all your wholesale requirements.</p>
        </div>

        <!-- 5 Category Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($featuredCategories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="group bg-white rounded-2xl p-5 border border-slate-200 hover:border-brand-500 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-700 group-hover:bg-brand-700 group-hover:text-white transition flex items-center justify-center text-xl mb-3.5 shadow-sm">
                            <i class="fa-solid {{ $category->icon }}"></i>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-brand-700 transition leading-snug">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">{{ $category->description }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-slate-400 group-hover:text-brand-600 transition">
                        <span>{{ count($category->subcategories) }} Items</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-6 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm shadow-sm transition">
                <span>View All Categories</span>
                <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
    </div>
</section>

<!-- Section 3: 5 Featured Products Grid -->
<section class="py-12 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-3">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Verified Listings</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Featured Products</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-bold text-brand-700 hover:text-brand-800 transition flex items-center">
                <span>View All Products</span>
                <i class="fa-solid fa-arrow-right ml-1.5"></i>
            </a>
        </div>

        <!-- 5 Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Product Image -->
                        <div class="relative h-44 bg-slate-100 overflow-hidden">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            
                            <!-- Origin Country Badge -->
                            <div class="absolute top-2 left-2 bg-white/95 backdrop-blur-sm px-2 py-0.5 rounded-md text-[10px] font-bold text-slate-800 shadow-sm flex items-center space-x-1">
                                <span>{{ $product->origin_country === 'India' ? '🇮🇳' : ($product->origin_country === 'China' ? '🇨🇳' : '🇩🇪') }}</span>
                                <span>{{ $product->origin_country }}</span>
                            </div>

                            @if($product->company && $product->company->is_verified)
                                <div class="absolute top-2 right-2 bg-emerald-600 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase shadow flex items-center space-x-1">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Verified</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-3.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug hover:text-brand-700 transition line-clamp-2 mb-1.5 min-h-[38px]">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <div class="text-[11px] text-slate-500 mb-2.5 truncate">
                                Seller: <strong class="text-slate-700">{{ $product->company->company_name ?? 'Verified Seller' }}</strong>
                            </div>

                            <!-- Price / MOQ -->
                            <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 mb-2">
                                <div class="flex items-baseline justify-between">
                                    <div>
                                        <span class="text-base font-black text-slate-900">£{{ number_format($product->base_price, 2) }}</span>
                                        <span class="text-[10px] text-slate-500">/ {{ $product->unit }}</span>
                                    </div>
                                    <div class="text-[10px] font-semibold text-slate-500">
                                        MOQ: <strong class="text-slate-800">{{ $product->moq }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-3.5 pt-0 grid grid-cols-2 gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-center py-2 px-1 rounded-xl text-xs font-bold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                            Details
                        </a>

                        @if($product->direct_purchase_enabled && !$product->price_on_request)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="{{ $product->moq }}">
                                <button type="submit" class="w-full text-center py-2 px-1 rounded-xl text-xs font-bold bg-brand-700 hover:bg-brand-800 text-white transition flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-cart-plus text-[10px]"></i>
                                    <span>Buy Now</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('products.show', $product->slug) }}#enquiry" class="text-center py-2 px-1 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition">
                                Enquire
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 4: How Mart Works (3 Simple Steps) -->
<section class="py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Simple Process</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">How Mart Works</h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-1.5">Buy directly from wholesale manufacturers in 3 easy steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-700 text-xl font-black flex items-center justify-center mx-auto">
                    1
                </div>
                <h3 class="font-bold text-slate-900 text-base">Search or Post Requirement</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Search from catalog products or fill the quick RFQ form with what you need and quantity.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 text-xl font-black flex items-center justify-center mx-auto">
                    2
                </div>
                <h3 class="font-bold text-slate-900 text-base">Get Verified Quotations</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Genuine manufacturers contact you directly with price quotes, technical details, and delivery terms.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 text-xl font-black flex items-center justify-center mx-auto">
                    3
                </div>
                <h3 class="font-bold text-slate-900 text-base">Connect & Deal Directly</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Compare offers, chat with sellers, finalize prices, and buy securely with full peace of mind.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Call to Action (Buyer / Seller) -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Buyer CTA -->
            <div class="bg-gradient-to-r from-blue-700 to-brand-800 rounded-3xl p-7 text-white flex flex-col justify-between shadow-lg">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-200">For Buyers</span>
                    <h3 class="text-xl sm:text-2xl font-black text-white mt-1 mb-2">Need products in bulk?</h3>
                    <p class="text-xs text-blue-100 mb-6 leading-relaxed">
                        Submit your requirement and let top manufacturers send you their best price quotes.
                    </p>
                </div>
                <div>
                    <a href="{{ route('rfqs.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-blue-900 font-bold text-xs shadow transition">
                        <i class="fa-solid fa-file-pen mr-2"></i> Post Free Requirement
                    </a>
                </div>
            </div>

            <!-- Supplier CTA -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-7 text-white flex flex-col justify-between shadow-lg">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">For Manufacturers & Suppliers</span>
                    <h3 class="text-xl sm:text-2xl font-black text-white mt-1 mb-2">Sell your products on Mart</h3>
                    <p class="text-xs text-slate-300 mb-6 leading-relaxed">
                        Register your business, list products, and connect with thousands of active wholesale buyers.
                    </p>
                </div>
                <div>
                    <a href="{{ route('register', ['role' => 'supplier']) }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition">
                        <i class="fa-solid fa-store mr-2"></i> Register as Supplier
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
