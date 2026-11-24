@extends('layouts.app')

@section('title', 'Perlcon International — Global B2B Marketplace & Industrial Procurement')

@section('content')

<!-- Hero Section with Industrial Background & Sourcing Focus -->
<section class="relative bg-gradient-to-b from-slate-50 via-white to-slate-50 border-b border-slate-200 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left 7 Cols: Value Proposition & Search -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-brand-700 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Direct Factory Sourcing & UK Landed Delivery</span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Source Industrial Machinery & Spare Parts from <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-brand-600 to-indigo-700">India, China & Europe</span>
                </h1>

                <p class="text-slate-600 text-base md:text-lg leading-relaxed max-w-2xl">
                    Perlcon connects engineering firms and UK buyers directly with verified manufacturers. Compare quotations, consolidate multi-supplier orders, and clear UK customs with zero hassle.
                </p>

                <!-- Big Search Box on Hero -->
                <div class="bg-white p-2.5 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 flex items-center px-3 bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-magnifying-glass text-slate-400 mr-2.5"></i>
                            <input type="text" name="q" placeholder="Search CNC machines, forged flanges, motors, valves, HS codes..." class="w-full py-3 bg-transparent text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                        </div>
                        <select name="country" class="px-3 py-3 bg-slate-50 rounded-xl text-xs font-semibold text-slate-700 border-none focus:outline-none cursor-pointer">
                            <option value="">All Countries</option>
                            <option value="India">🇮🇳 India</option>
                            <option value="China">🇨🇳 China</option>
                            <option value="Germany">🇩🇪 Germany</option>
                            <option value="United Kingdom">🇬🇧 UK</option>
                        </select>
                        <button type="submit" class="px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-sm transition shadow-md shadow-brand-700/20">
                            Find Suppliers
                        </button>
                    </form>
                </div>

                <!-- Trust Badges -->
                <div class="pt-2 flex flex-wrap items-center gap-6 text-xs text-slate-600 font-semibold">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-certificate text-blue-600 text-base"></i>
                        <span>ISO 9001 & CE Certified</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-emerald-600 text-base"></i>
                        <span>Trade Assurance Escrow</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-ship text-indigo-600 text-base"></i>
                        <span>Integrated UK Customs (HMRC CDS)</span>
                    </div>
                </div>
            </div>

            <!-- Right 5 Cols: Quick RFQ Box -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl border border-slate-200 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 via-brand-600 to-blue-600"></div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block">IndiaMART Style Fast RFQ</span>
                            <h2 class="text-xl font-black text-slate-900">Request For Quotation (RFQ)</h2>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                    </div>
                    
                    <p class="text-xs text-slate-500 mb-5">
                        Tell us what you need. Receive binding quotations from verified suppliers in 24 hours.
                    </p>

                    <form action="{{ route('rfqs.store') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Product Name or Component</label>
                            <input type="text" name="product_name" required placeholder="e.g. ASME B16.5 Forged Flanges DN150" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Quantity</label>
                                <input type="number" name="quantity" min="1" required placeholder="500" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Unit</label>
                                <select name="unit" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                    <option value="Pieces">Pieces</option>
                                    <option value="Units">Units</option>
                                    <option value="Sets">Sets</option>
                                    <option value="Metric Tons">Metric Tons</option>
                                    <option value="Meters">Meters</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Technical Specifications</label>
                            <textarea name="technical_specifications" rows="2" required placeholder="Material grade (SS316, Carbon Steel), pressure rating, tolerances..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
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

                        <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition shadow-md shadow-amber-500/25 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Submit RFQ to Suppliers</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Country Sourcing Hubs Section -->
<section class="py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Global Sourcing Corridors</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Source by Manufacturing Origin</h2>
            </div>
            <a href="{{ route('suppliers.index') }}" class="text-sm font-bold text-brand-700 hover:text-brand-800 transition flex items-center">
                Explore All Suppliers <i class="fa-solid fa-arrow-right ml-1.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- India Card -->
            <div class="bg-gradient-to-br from-amber-50/50 to-orange-50/30 rounded-3xl p-6 border border-amber-200/60 shadow-sm hover:shadow-md transition">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-3xl">🇮🇳</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">India Manufacturing Hub</h3>
                        <span class="text-xs font-semibold text-amber-800">Forgings, Valves, Motors & Steel</span>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    Heavy industrial forgings, ASME certified flanges, cast carbon valves, precision CNC lathe parts, and energy-efficient 3-phase electric motors.
                </p>
                <div class="flex items-center justify-between text-xs pt-3 border-t border-amber-200/40">
                    <span class="font-bold text-slate-700">Port: Nhava Sheva (JNPT)</span>
                    <a href="{{ route('products.index', ['country' => 'India']) }}" class="text-amber-700 font-bold hover:underline">View Products &rarr;</a>
                </div>
            </div>

            <!-- China Card -->
            <div class="bg-gradient-to-br from-rose-50/50 to-red-50/30 rounded-3xl p-6 border border-rose-200/60 shadow-sm hover:shadow-md transition">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-3xl">🇨🇳</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">China CNC & Automation</h3>
                        <span class="text-xs font-semibold text-rose-800">5-Axis CNC, Robotics, PLCs & Sensors</span>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    High-speed CNC vertical machining centers, 6-axis industrial articulated robotic arms, PLC modular automation, and industrial servo systems.
                </p>
                <div class="flex items-center justify-between text-xs pt-3 border-t border-rose-200/40">
                    <span class="font-bold text-slate-700">Port: Shenzhen / Shanghai</span>
                    <a href="{{ route('products.index', ['country' => 'China']) }}" class="text-rose-700 font-bold hover:underline">View Products &rarr;</a>
                </div>
            </div>

            <!-- Europe / Germany Card -->
            <div class="bg-gradient-to-br from-blue-50/50 to-indigo-50/30 rounded-3xl p-6 border border-blue-200/60 shadow-sm hover:shadow-md transition">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-3xl">🇩🇪</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">European Precision Engineering</h3>
                        <span class="text-xs font-semibold text-blue-800">Hydraulic Presses, Gearboxes & Drives</span>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                    DIN EN ISO certified German hydraulic stamping presses, heavy planetary gearboxes, high-pressure pumps, and ATEX explosion-proof systems.
                </p>
                <div class="flex items-center justify-between text-xs pt-3 border-t border-blue-200/40">
                    <span class="font-bold text-slate-700">Origin: Stuttgart / Rotterdam</span>
                    <a href="{{ route('products.index', ['country' => 'Germany']) }}" class="text-blue-700 font-bold hover:underline">View Products &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories Grid -->
<section class="py-14 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Browse Industrial Sectors</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Popular Industrial Categories</h2>
            <p class="text-slate-500 text-sm mt-2">Discover heavy equipment, precision engineering parts, automation controllers and safety supplies.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
            @foreach($featuredCategories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="group bg-white rounded-2xl p-5 border border-slate-200 hover:border-brand-500 hover:shadow-md transition flex flex-col justify-between">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-700 group-hover:bg-brand-700 group-hover:text-white transition flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid {{ $category->icon }}"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-brand-700 transition">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-slate-400 group-hover:text-brand-600 transition">
                        <span>{{ count($category->subcategories) }} Subcategories</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm shadow-sm transition">
                <span>View All 49 Categories & Subcategories</span>
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Featured Industrial Products Section -->
<section class="py-14 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Ready for Direct Purchase or Quotation</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Featured Industrial Machinery & Parts</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm font-bold text-brand-700 hover:text-brand-800 transition flex items-center">
                View Entire Catalogue <i class="fa-solid fa-arrow-right ml-1.5"></i>
            </a>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Product Image -->
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            
                            <!-- Origin Country Badge -->
                            <div class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur-sm px-2.5 py-1 rounded-full text-[11px] font-bold text-slate-800 shadow-sm flex items-center space-x-1">
                                <span>{{ $product->origin_country === 'India' ? '🇮🇳' : ($product->origin_country === 'China' ? '🇨🇳' : '🇩🇪') }}</span>
                                <span>{{ $product->origin_country }}</span>
                            </div>

                            @if($product->company && $product->company->is_verified)
                                <div class="absolute top-2.5 right-2.5 bg-emerald-600 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase shadow flex items-center space-x-1">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Verified</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <span class="text-[10px] font-mono font-bold text-slate-400 block mb-1">HS Code: {{ $product->hs_code }}</span>
                            <h3 class="font-bold text-slate-900 text-sm leading-snug hover:text-brand-700 transition line-clamp-2 mb-2">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <div class="text-xs text-slate-500 mb-3 truncate">
                                By <strong class="text-slate-700">{{ $product->company->company_name ?? 'Verified Exporter' }}</strong>
                            </div>

                            <!-- Price / MOQ -->
                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 mb-3">
                                <div class="flex items-baseline justify-between">
                                    <div>
                                        @if($product->price_on_request)
                                            <span class="text-sm font-extrabold text-amber-600">Price on Request</span>
                                        @else
                                            <span class="text-lg font-black text-slate-900">£{{ number_format($product->base_price, 2) }}</span>
                                            <span class="text-[11px] text-slate-500">/ {{ $product->unit }}</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] font-semibold text-slate-500">
                                        MOQ: <span class="text-slate-900 font-bold">{{ $product->moq }} {{ $product->unit }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
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
                                    <span>Add to Cart</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('products.show', $product->slug) }}#enquiry" class="text-center py-2 px-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white transition">
                                Send Enquiry
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Verified Manufacturers & Suppliers Carousel/Grid -->
<section class="py-14 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Audited & Verified Plants</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Featured Industrial Manufacturers</h2>
            </div>
            <a href="{{ route('suppliers.index') }}" class="text-sm font-bold text-brand-700 hover:text-brand-800 transition flex items-center">
                View All Manufacturers <i class="fa-solid fa-arrow-right ml-1.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($verifiedSuppliers as $sup)
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <img src="{{ $sup->logo ?? 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=100&h=100&fit=crop' }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-100 shadow-sm" alt="{{ $sup->company_name }}">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base leading-tight hover:text-brand-700 transition">
                                    <a href="{{ route('suppliers.show', $sup->slug) }}">{{ $sup->company_name }}</a>
                                </h3>
                                <div class="flex items-center space-x-2 text-xs text-slate-500 mt-1">
                                    <span>{{ $sup->country === 'India' ? '🇮🇳' : ($sup->country === 'China' ? '🇨🇳' : '🇩🇪') }} {{ $sup->city }}, {{ $sup->country }}</span>
                                    <span>•</span>
                                    <span class="capitalize text-slate-700 font-semibold">{{ $sup->business_type }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center">
                                <i class="fa-solid fa-circle-check mr-1"></i> Verified Plant
                            </span>
                            <span class="text-xs text-amber-500 font-bold flex items-center">
                                <i class="fa-solid fa-star mr-1"></i> {{ $sup->rating }} ({{ $sup->reviews_count }} reviews)
                            </span>
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed">
                            {{ $sup->description }}
                        </p>

                        <!-- Certifications Pills -->
                        @if(!empty($sup->certifications))
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($sup->certifications as $cert)
                                    <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-semibold text-slate-700">{{ $cert }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">Est. <strong>{{ $sup->year_established ?? '2005' }}</strong></span>
                        <a href="{{ route('suppliers.show', $sup->slug) }}" class="text-xs font-bold text-brand-700 hover:text-brand-800 transition flex items-center">
                            View Plant Profile &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- International Procurement, Freight & UK Customs Section -->
<section class="py-14 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-tr from-slate-900 via-brand-950 to-slate-900 rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden shadow-xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
                <div class="space-y-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                        Integrated Logistics & UK Customs
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                        From Overseas Factory Floor to Your UK Warehouse Door
                    </h2>
                    <p class="text-slate-300 text-sm leading-relaxed max-w-lg">
                        Never worry about complex import tariffs, Bills of Lading, or customs holds. Perlcon integrates international sea/air freight with automated UK Import Duty calculation and accredited customs broker processing by Junaid & Co.
                    </p>

                    <div class="grid grid-cols-2 gap-4 pt-2 text-xs">
                        <div class="bg-slate-800/60 p-3.5 rounded-xl border border-slate-700">
                            <i class="fa-solid fa-ship text-blue-400 text-lg mb-2 block"></i>
                            <strong class="text-white block">LCL / FCL Ocean Freight</strong>
                            <span class="text-slate-400">Felixstowe & London Gateway</span>
                        </div>
                        <div class="bg-slate-800/60 p-3.5 rounded-xl border border-slate-700">
                            <i class="fa-solid fa-file-shield text-emerald-400 text-lg mb-2 block"></i>
                            <strong class="text-white block">UK HMRC CDS Clearance</strong>
                            <span class="text-slate-400">Pre-lodged C88 Declarations</span>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-wrap gap-3">
                        <a href="{{ route('calculator.index') }}" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold rounded-xl text-sm transition shadow-md shadow-amber-500/20 flex items-center space-x-2">
                            <i class="fa-solid fa-calculator"></i>
                            <span>Calculate UK Landed Cost</span>
                        </a>
                        <a href="{{ route('shipping') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl text-sm transition border border-white/20">
                            Logistics Services
                        </a>
                    </div>
                </div>

                <!-- Step-by-Step 4-Step Diagram -->
                <div class="bg-slate-800/80 p-6 rounded-2xl border border-slate-700 space-y-4">
                    <h3 class="text-base font-bold text-white mb-2 flex items-center">
                        <i class="fa-solid fa-route text-blue-400 mr-2"></i> The Perlcon 4-Step Procurement Pipeline
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-start space-x-3 p-3 bg-slate-900/60 rounded-xl border border-slate-700/50">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center flex-shrink-0 text-[11px]">1</span>
                            <div>
                                <strong class="text-white block">RFQ or Direct Order</strong>
                                <span class="text-slate-400">Submit requirement or add multiple supplier items to one cart.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-900/60 rounded-xl border border-slate-700/50">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center flex-shrink-0 text-[11px]">2</span>
                            <div>
                                <strong class="text-white block">Trade Assurance Escrow Payment</strong>
                                <span class="text-slate-400">Funds secured until factory QC and Bill of Lading verification.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-900/60 rounded-xl border border-slate-700/50">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center flex-shrink-0 text-[11px]">3</span>
                            <div>
                                <strong class="text-white block">Ocean / Air Freight & Port Dispatch</strong>
                                <span class="text-slate-400">Consolidated from India (JNPT) or China (Shenzhen) via DHL / MSC.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-900/60 rounded-xl border border-slate-700/50">
                            <span class="w-6 h-6 rounded-full bg-emerald-500 text-white font-bold flex items-center justify-center flex-shrink-0 text-[11px]">4</span>
                            <div>
                                <strong class="text-white block">Customs Clearance & UK Delivery</strong>
                                <span class="text-slate-400">Junaid & Co. clears HMRC CDS; delivered straight to your UK site.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Testimonials Section -->
<section class="py-14 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Customer Trust</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">What UK Engineering Buyers Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-xs mb-3 space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        "We sourced 500 SS316L forged flanges from Bharat Heavy Forge in India through Perlcon. The dimensional accuracy and 3.1 MTR certificates were completely compliant with our offshore client specifications."
                    </p>
                </div>
                <div class="flex items-center space-x-3 pt-3 border-t border-slate-100">
                    <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-700">DS</div>
                    <div>
                        <strong class="text-xs text-slate-900 block">David Sterling</strong>
                        <span class="text-[11px] text-slate-500">Procurement Director, Apex British Contracting</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-xs mb-3 space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        "The multi-supplier order splitting is brilliant. We bought CNC tooling from Shenzhen and high-torque gearboxes from Stuttgart on one invoice. Junaid & Co. cleared UK customs in 48 hours at Felixstowe."
                    </p>
                </div>
                <div class="flex items-center space-x-3 pt-3 border-t border-slate-100">
                    <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-700">MR</div>
                    <div>
                        <strong class="text-xs text-slate-900 block">Michael Roberts</strong>
                        <span class="text-[11px] text-slate-500">Plant Operations, Midlands Precision Engineering</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-xs mb-3 space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        "The Landed Cost Calculator showed the exact UK import duty and VAT before checkout. No surprise charges at the port. Best B2B trade experience we've had in 15 years."
                    </p>
                </div>
                <div class="flex items-center space-x-3 pt-3 border-t border-slate-100">
                    <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-700">TL</div>
                    <div>
                        <strong class="text-xs text-slate-900 block">Thomas Lewis</strong>
                        <span class="text-[11px] text-slate-500">Supply Chain Lead, Yorkshire Valve & Piping</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner: Become a Supplier or Post RFQ -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Buyer CTA -->
            <div class="bg-gradient-to-r from-blue-700 to-brand-800 rounded-3xl p-8 text-white flex flex-col justify-between shadow-lg shadow-blue-700/15">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-200">For Buyers & EPC Contractors</span>
                    <h3 class="text-2xl font-black text-white mt-1 mb-2">Have a custom engineering project?</h3>
                    <p class="text-xs text-blue-100 mb-6 leading-relaxed">
                        Submit your technical drawing, BOM (Bill of Materials) or technical specifications to receive quotes from top manufacturers.
                    </p>
                </div>
                <div>
                    <a href="{{ route('rfqs.create') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-white hover:bg-slate-100 text-blue-900 font-bold text-xs shadow transition">
                        <i class="fa-solid fa-file-pen mr-2"></i> Submit RFQ Free
                    </a>
                </div>
            </div>

            <!-- Supplier CTA -->
            <div class="bg-gradient-to-r from-slate-900 to-perlcon-navy rounded-3xl p-8 text-white flex flex-col justify-between shadow-lg shadow-slate-900/15">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400">For Manufacturers & Exporters</span>
                    <h3 class="text-2xl font-black text-white mt-1 mb-2">Export your machinery to the UK & Europe</h3>
                    <p class="text-xs text-slate-300 mb-6 leading-relaxed">
                        Join Perlcon as a verified manufacturer. Connect with verified British & European industrial buyers seeking long-term procurement contracts.
                    </p>
                </div>
                <div>
                    <a href="{{ route('register', ['role' => 'supplier']) }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow transition">
                        <i class="fa-solid fa-handshake mr-2"></i> Register as Supplier
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
