@extends('layouts.admin')

@section('title', 'Reports & Financial Analytics — Mart Admin')
@section('header_title', 'Marketplace Reports & Financial Analytics')
@section('header_subtitle', 'Audit transaction volume, destination country distribution, and export data in CSV format')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-sm font-black text-slate-900">Export Raw Transaction Data</h2>
            <p class="text-xs text-slate-500">Download comma-separated values file with full buyer, supplier PO, and landed cost breakdown</p>
        </div>
        <a href="{{ route('admin.reports.export-csv') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-2 flex-shrink-0">
            <i class="fa-solid fa-file-csv"></i>
            <span>Download Orders CSV Report</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Country Trade Distribution -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Destination Country Trade Distribution</h2>
                <span class="text-xs text-slate-400 font-semibold">Total Consignments</span>
            </div>
            
            <div class="space-y-3">
                @forelse($countryWiseOrders as $item)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center text-xs hover:border-slate-200 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 font-bold">
                                <i class="fa-solid fa-earth-americas text-brand-600"></i>
                            </div>
                            <div>
                                <strong class="text-slate-900 text-sm block">{{ $item->destination_country ?? 'United Kingdom' }}</strong>
                                <span class="text-slate-500 text-[11px]">{{ $item->count }} Consignment(s)</span>
                            </div>
                        </div>
                        <span class="font-black text-slate-900 text-sm">£{{ number_format($item->total, 2) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">No order volume data available yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Monthly Sales Volume -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Monthly Procurement Volume</h2>
                <span class="text-xs text-slate-400 font-semibold">Gross Merchandise Value</span>
            </div>

            <div class="space-y-3">
                @forelse($salesByMonth as $item)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center text-xs hover:border-slate-200 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 font-bold">
                                <i class="fa-solid fa-calendar text-brand-600"></i>
                            </div>
                            <div>
                                <strong class="text-slate-900 text-sm block">{{ date('F Y', strtotime($item->month . '-01')) }}</strong>
                                <span class="text-slate-500 text-[11px]">Monthly settlement</span>
                            </div>
                        </div>
                        <span class="font-black text-slate-900 text-sm">£{{ number_format($item->total, 2) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">No monthly transactions recorded yet.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
