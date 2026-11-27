@extends('layouts.app')

@section('title', $product->name . ' — Mart')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-brand-700">Products</a>
            <span>/</span>
            <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-brand-700">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate">{{ $product->name }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left 7 Cols: Image, Specs & Overview -->
        <div class="lg:col-span-7 space-y-8">
            <!-- Main Product Image & Badges -->
            <div class="bg-white rounded-3xl border border-slate-200 p-4 shadow-sm overflow-hidden">
                <div class="relative h-96 rounded-2xl bg-slate-100 overflow-hidden mb-4">
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-slate-800 shadow flex items-center space-x-1.5">
                        <span>{{ $product->country_flag }}</span>
                        <span>Origin: {{ $product->origin_country }}</span>
                    </div>

                    @if($product->company && $product->company->is_verified)
                        <div class="absolute top-4 right-4 bg-emerald-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase shadow flex items-center space-x-1">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Verified Manufacturer</span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500 px-2">
                    <span>HS Code: <strong class="font-mono text-slate-900">{{ $product->hs_code }}</strong></span>
                    <span>SKU: <strong class="font-mono text-slate-900">{{ $product->sku ?? 'N/A' }}</strong></span>
                    <span>Lead Time: <strong class="text-slate-900">{{ $product->lead_time_days }} Days</strong></span>
                    <span>Unit Weight: <strong class="text-slate-900">{{ $product->weight_kg }} kg</strong></span>
                </div>
            </div>

            <!-- Volume Pricing Table -->
            @if(!empty($product->volume_pricing) && is_array($product->volume_pricing))
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center">
                        <i class="fa-solid fa-layer-group text-brand-700 mr-2"></i> Tiered Wholesale Volume Pricing
                    </h3>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        @foreach($product->volume_pricing as $tier)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-xs text-slate-500 font-semibold block mb-1">
                                    {{ $tier['min'] }} - {{ $tier['max'] ?? '+' }} {{ $product->unit }}
                                </span>
                                <span class="text-base font-black text-slate-900 block">
                                    £{{ number_format($tier['price'], 2) }}
                                </span>
                                <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">Wholesale Rate</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Technical Specifications Table -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                    <i class="fa-solid fa-microchip text-brand-700 mr-2"></i> Technical Specifications
                </h3>

                @if(!empty($product->technical_specifications) && is_array($product->technical_specifications))
                    <div class="divide-y divide-slate-100 text-xs">
                        @foreach($product->technical_specifications as $key => $val)
                            <div class="py-2.5 flex justify-between">
                                <span class="font-semibold text-slate-500 w-1/3">{{ $key }}</span>
                                <span class="font-bold text-slate-900 w-2/3 text-right">{{ $val }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Contact supplier for complete technical drawings and material certificates.</p>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-3">Product Overview</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                    {{ $product->full_description ?? $product->short_description }}
                </p>
                <div class="grid grid-cols-2 gap-4 text-xs pt-4 border-t border-slate-100">
                    <div>
                        <span class="text-slate-500 block">Production Capacity:</span>
                        <strong class="text-slate-800">{{ $product->manufacturing_capacity ?? '5000 units/month' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Dimensions:</span>
                        <strong class="text-slate-800">{{ $product->dimensions ?? 'Export standard packaging' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Landed Cost Quick Tool Link -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-sm text-blue-950">Curious about UK Landed Cost?</h4>
                    <p class="text-xs text-blue-800 mt-0.5">Calculate UK Import Duty ({{ $product->hs_code }}) + 20% VAT + Freight to your warehouse.</p>
                </div>
                <a href="{{ route('calculator.index') }}?product_value={{ $product->base_price }}&hs_code={{ $product->hs_code }}&origin_country={{ $product->origin_country }}&weight_kg={{ $product->weight_kg }}" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow transition flex-shrink-0">
                    Calculate Landed Cost
                </a>
            </div>

        </div>

        <!-- Right 5 Cols: Pricing, Purchasing & Supplier Profile -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Price & Order Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm">
                <h1 class="text-2xl font-black text-slate-900 leading-snug mb-3">
                    {{ $product->name }}
                </h1>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-6">
                    <span class="text-xs font-semibold text-slate-500 block mb-1">Price per {{ $product->unit }}</span>
                    @if($product->price_on_request)
                        <span class="text-2xl font-black text-amber-600">Price On Request</span>
                        <p class="text-xs text-slate-500 mt-1">Requires custom specifications or volume quote.</p>
                    @else
                        <div class="flex items-baseline space-x-2">
                            <span class="text-3xl font-black text-slate-900">£{{ number_format($product->base_price, 2) }}</span>
                            <span class="text-xs font-semibold text-slate-500">Ex-Works / FOB</span>
                        </div>
                    @endif

                    <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between text-xs text-slate-700 font-semibold">
                        <span>Minimum Order (MOQ):</span>
                        <strong class="text-slate-900 font-bold">{{ $product->moq }} {{ $product->unit }}</strong>
                    </div>
                </div>

                <!-- Add to Cart or Request Quotation Action -->
                @if($product->direct_purchase_enabled && !$product->price_on_request)
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Purchase Quantity (Min: {{ $product->moq }})</label>
                            <div class="flex items-center space-x-2">
                                <input type="number" name="quantity" value="{{ $product->moq }}" min="{{ $product->moq }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                <span class="text-xs font-bold text-slate-500 uppercase">{{ $product->unit }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-sm transition shadow-md shadow-brand-700/20 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Add to Procurement Cart</span>
                        </button>
                    </form>
                @endif

                <a href="#enquiry" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition shadow-md shadow-amber-500/20 flex items-center justify-center space-x-2 mb-3">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Send Direct Product Enquiry</span>
                </a>

                <a href="{{ route('rfqs.create') }}?product={{ urlencode($product->name) }}" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-file-pen"></i>
                    <span>Post Custom RFQ for Similar Items</span>
                </a>
            </div>

            <!-- Supplier Profile Card -->
            @if($product->company)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-3">Manufacturer Information</span>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ $product->company->logo ?? 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=100&h=100&fit=crop' }}" class="w-12 h-12 rounded-xl object-cover border border-slate-100" alt="{{ $product->company->company_name }}">
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm leading-tight hover:text-brand-700 transition">
                                <a href="{{ route('suppliers.show', $product->company->slug) }}">{{ $product->company->company_name }}</a>
                            </h4>
                            <span class="text-xs text-slate-500">{{ $product->company->city }}, {{ $product->company->country }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs py-3 border-y border-slate-100 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Business Type:</span>
                            <strong class="text-slate-800 capitalize">{{ $product->company->business_type }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Established:</span>
                            <strong class="text-slate-800">{{ $product->company->year_established ?? '2005' }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Export Markets:</span>
                            <strong class="text-slate-800 truncate max-w-[180px]">{{ $product->company->export_markets ?? 'UK, Europe' }}</strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Verification:</span>
                            @if($product->company->is_verified)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">Audit Passed</span>
                            @else
                                <span class="text-slate-500">Standard</span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('suppliers.show', $product->company->slug) }}" class="text-xs font-bold text-brand-700 hover:text-brand-800 transition block text-center">
                        View Full Company Profile & Catalogue &rarr;
                    </a>
                </div>
            @endif

            <!-- Direct Enquiry Box Form (#enquiry) -->
            <div id="enquiry" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center">
                    <i class="fa-solid fa-envelope-open-text text-amber-500 mr-2"></i> Contact This Supplier
                </h3>
                <p class="text-xs text-slate-500 mb-4">
                    Send your questions directly to {{ $product->company->company_name ?? 'this manufacturer' }}.
                </p>

                <form action="{{ route('enquiries.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Required Quantity</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="required_quantity" value="{{ $product->moq }}" min="1" required class="px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            <input type="text" name="unit" value="{{ $product->unit }}" readonly class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subject</label>
                        <input type="text" name="subject" value="Enquiry regarding {{ $product->name }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Message / Specifications</label>
                        <textarea name="message" rows="3" required placeholder="Please provide pricing for delivery to UK, lead times, and MTR material test certificates..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                    </div>

                    @guest
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Your Name</label>
                                <input type="text" name="buyer_name" required placeholder="Full Name" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Work Email</label>
                                <input type="email" name="buyer_email" required placeholder="you@company.co.uk" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none">
                            </div>
                        </div>
                    @endguest

                    <button type="submit" class="w-full py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Submit Direct Enquiry
                    </button>
                </form>
            </div>

        </div>

    </div>

    <!-- Similar Products Section -->
    @if($similarProducts->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-slate-200">
            <h3 class="text-xl font-black text-slate-900 mb-6">Similar Products You May Like</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarProducts as $sim)
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="h-36 rounded-xl bg-slate-100 overflow-hidden mb-3">
                                <img src="{{ $sim->main_image }}" alt="{{ $sim->name }}" class="w-full h-full object-cover">
                            </div>
                            <h4 class="font-bold text-slate-900 text-xs line-clamp-2 mb-1 hover:text-brand-700 transition">
                                <a href="{{ route('products.show', $sim->slug) }}">{{ $sim->name }}</a>
                            </h4>
                            <span class="text-xs text-slate-500 block mb-2">{{ $sim->company->company_name ?? 'Exporter' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-black text-sm text-slate-900">£{{ number_format($sim->base_price, 2) }}</span>
                            <a href="{{ route('products.show', $sim->slug) }}" class="text-xs text-brand-700 font-bold hover:underline">View &rarr;</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
