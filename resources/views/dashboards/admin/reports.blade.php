@extends('layouts.app')

@section('title', 'Reports & Analytics — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Marketplace Reports & Financial Analytics</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Audit transaction volume, country-wise distribution, and export data.</p>
            </div>
            <a href="{{ route('admin.reports.export-csv') }}" class="px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-2">
                <i class="fa-solid fa-file-csv"></i>
                <span>Download Orders CSV Report</span>
            </a>
        </div>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Buyers</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Products</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('admin.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">RFQs</a>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Reports</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Country Distribution -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-black text-slate-900 mb-4">Destination Country Trade Distribution</h2>
            <div class="space-y-3">
                @foreach($countryWiseOrders as $item)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center text-xs">
                        <div>
                            <strong class="text-slate-900 text-sm block">{{ $item->destination_country }}</strong>
                            <span class="text-slate-500">{{ $item->count }} Consignment(s)</span>
                        </div>
                        <span class="font-black text-slate-900 text-sm">£{{ number_format($item->total, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Sales Volume -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-black text-slate-900 mb-4">Monthly Trade Volume (GBP)</h2>
            <div class="space-y-3">
                @foreach($salesByMonth as $m)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center text-xs">
                        <div>
                            <strong class="text-slate-900 text-sm block">{{ $m->month }}</strong>
                            <span class="text-slate-500">Gross Landed Volume</span>
                        </div>
                        <span class="font-black text-brand-700 text-base">£{{ number_format($m->total, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
