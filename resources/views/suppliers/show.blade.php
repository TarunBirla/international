@extends('layouts.app')

@section('title', $company->company_name . ' — Verified Supplier Profile')

@section('content')
<!-- Company Banner Header -->
<div class="relative bg-slate-900 text-white py-12 md:py-16 overflow-hidden">
    @if($company->banner)
        <img src="{{ $company->banner }}" alt="{{ $company->company_name }}" class="absolute inset-0 w-full h-full object-cover opacity-20">
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center space-x-5">
                <img src="{{ $company->logo_url }}" class="w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover bg-white p-1 border-2 border-white/20 shadow-xl" alt="{{ $company->company_name }}">
                <div>
                    <div class="flex items-center space-x-2 mb-1.5">
                        <span class="text-xl">{{ $company->country === 'India' ? '🇮🇳' : ($company->country === 'China' ? '🇨🇳' : '🇩🇪') }}</span>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $company->company_name }}</h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300">
                        <span><i class="fa-solid fa-location-dot mr-1 text-blue-400"></i> {{ $company->city }}, {{ $company->country }}</span>
                        <span>•</span>
                        <span class="capitalize"><i class="fa-solid fa-industry mr-1 text-blue-400"></i> {{ $company->business_type }}</span>
                        <span>•</span>
                        <span>Est. {{ $company->year_established ?? '2005' }}</span>
                        @if($company->is_verified)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white uppercase">
                                <i class="fa-solid fa-circle-check mr-1"></i> Verified Plant
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="#contact" class="px-5 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-lg shadow-amber-500/20 flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Contact Supplier</span>
                </a>
                <a href="{{ route('rfqs.create') }}?supplier_id={{ $company->user_id }}" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl text-xs transition border border-white/20">
                    Request Quotation
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Plant Capabilities & Product Catalogue -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Company Overview -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 mb-3 flex items-center">
                    <i class="fa-solid fa-circle-info text-brand-700 mr-2"></i> About The Manufacturer
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                    {{ $company->description }}
                </p>

                <h3 class="text-sm font-bold text-slate-900 mb-2">Manufacturing Facilities & Equipment</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    {{ $company->manufacturing_capabilities ?? 'Full CNC production shop, CMM quality inspection, spectro laboratory.' }}
                </p>

                <!-- Certifications -->
                @if(!empty($company->certifications))
                    <h3 class="text-sm font-bold text-slate-900 mb-3">Audited Quality Certifications</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($company->certifications as $cert)
                            <div class="px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-xl text-xs font-bold text-brand-800 flex items-center space-x-1.5">
                                <i class="fa-solid fa-award text-blue-600"></i>
                                <span>{{ $cert }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Catalogue -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-black text-slate-900">Product Catalogue ({{ $products->total() }})</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="h-40 rounded-xl bg-slate-100 overflow-hidden mb-3">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                                <h3 class="font-bold text-slate-900 text-xs sm:text-sm line-clamp-2 mb-1.5 hover:text-brand-700 transition">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 flex items-baseline justify-between mb-3">
                                    <span class="font-black text-slate-900 text-sm">£{{ number_format($product->base_price, 2) }}</span>
                                    <span class="text-[11px] text-slate-500">MOQ: {{ $product->moq }}</span>
                                </div>
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="block text-center py-2 bg-brand-700 hover:bg-brand-800 text-white rounded-xl text-xs font-bold transition">
                                View Details & Pricing
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <!-- Right Col: Company Stats & Contact Form -->
        <div class="space-y-6">
            <!-- Fast Facts -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-extrabold text-slate-900 mb-4">Company Verification Profile</h3>
                <div class="space-y-3 text-xs divide-y divide-slate-100">
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-500">Country of Origin:</span>
                        <strong class="text-slate-800">{{ $company->country }}</strong>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-500">Plant Location:</span>
                        <strong class="text-slate-800 text-right">{{ $company->address ?? $company->city }}</strong>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-500">Tax / VAT ID:</span>
                        <strong class="font-mono text-slate-800">{{ $company->tax_id ?? 'Verified on file' }}</strong>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-500">Workforce:</span>
                        <strong class="text-slate-800">{{ $company->employees_count ?? '100+ Staff' }}</strong>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-500">Annual Turnover:</span>
                        <strong class="text-slate-800">{{ $company->annual_turnover ?? '$10M+' }}</strong>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-slate-500">Primary Export Hubs:</span>
                        <strong class="text-slate-800">{{ $company->export_markets ?? 'UK & Europe' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Contact Box Form (#contact) -->
            <div id="contact" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-2">Send Message to Plant</h3>
                <p class="text-xs text-slate-500 mb-4">Inquire directly about custom OEM manufacturing, lead times, and container shipping.</p>

                <form action="{{ route('messages.send') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $company->user_id }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Message</label>
                        <textarea name="message" rows="4" required placeholder="Describe your manufacturing requirement or ask for plant audit documents..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Send Message to Factory
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
