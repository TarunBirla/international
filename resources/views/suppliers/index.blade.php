@extends('layouts.app')

@section('title', 'Verified Suppliers Directory — Mart')

@section('content')
<div class="bg-white border-b border-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Suppliers Directory</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Verified Manufacturers & Exporters</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Directly connect with audited manufacturing facilities in India, China, Germany, and the UK.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filters Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-8">
        <form action="{{ route('suppliers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search company, plant capabilities..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>
            <div>
                <select name="country" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">All Countries</option>
                    @foreach($countries as $c)
                        <option value="{{ $c }}" {{ request('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="business_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">All Business Types</option>
                    <option value="manufacturer" {{ request('business_type') == 'manufacturer' ? 'selected' : '' }}>Manufacturer / Factory</option>
                    <option value="wholesaler" {{ request('business_type') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                    <option value="distributor" {{ request('business_type') == 'distributor' ? 'selected' : '' }}>Distributor / Exporter</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                    Filter Suppliers
                </button>
                <a href="{{ route('suppliers.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Suppliers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($suppliers as $company)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3.5 mb-4">
                        <img src="{{ $company->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-100 shadow-sm" alt="{{ $company->company_name }}">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base leading-tight hover:text-brand-700 transition">
                                <a href="{{ route('suppliers.show', $company->slug) }}">{{ $company->company_name }}</a>
                            </h3>
                            <div class="flex items-center space-x-1.5 text-xs text-slate-500 mt-1">
                                <span>{{ $company->country === 'India' ? '🇮🇳' : ($company->country === 'China' ? '🇨🇳' : '🇩🇪') }} {{ $company->city }}, {{ $company->country }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 mb-3">
                        @if($company->is_verified)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center">
                                <i class="fa-solid fa-circle-check mr-1"></i> Verified Plant
                            </span>
                        @endif
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                            {{ $company->business_type }}
                        </span>
                        <span class="text-xs text-amber-500 font-bold ml-auto flex items-center">
                            <i class="fa-solid fa-star mr-1"></i> {{ $company->rating }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed">
                        {{ $company->description }}
                    </p>

                    <div class="space-y-1.5 text-xs py-3 border-y border-slate-100 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Established:</span>
                            <strong class="text-slate-800">{{ $company->year_established ?? '2005' }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Turnover:</span>
                            <strong class="text-slate-800">{{ $company->annual_turnover ?? '$10M+' }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Export Markets:</span>
                            <strong class="text-slate-800 truncate max-w-[170px]">{{ $company->export_markets ?? 'UK, Europe' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-semibold">{{ count($company->products) }} Products Listed</span>
                    <a href="{{ route('suppliers.show', $company->slug) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white rounded-xl text-xs font-bold transition">
                        View Profile & Catalogue
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
