@extends('layouts.buyer')

@section('title', 'Buyer Dashboard — Perlcon International')
@section('header_title', 'Procurement Control Center')
@section('header_subtitle', 'Manage custom machinery tenders, compare bids, and track international orders')

@section('content')
<div class="space-y-8">

    <!-- KPI Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-sky-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">My RFQs</span>
                <span class="w-7 h-7 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-invoice"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['rfqs_count'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">Active Tenders</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-sky-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Quotations</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-comments-dollar"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['quotations_count'] }}</p>
            <p class="text-[11px] text-emerald-600 font-bold mt-1.5">Supplier Bids Received</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-sky-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Orders</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['orders_count'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">Split PO Consignments</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-sky-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Procurement</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sterling-sign"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">£{{ number_format($stats['total_spent'], 2) }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">Landed Cost Volume</p>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Procurement Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('rfqs.create') }}" class="p-3 bg-slate-50 hover:bg-sky-50 border border-slate-200 hover:border-sky-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-sky-700 mb-2">
                    <i class="fa-solid fa-file-pen text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-sky-800 block">Post New RFQ</span>
                <span class="text-[10px] text-slate-500 block">Custom Machinery</span>
            </a>
            <a href="{{ route('buyer.quotations') }}" class="p-3 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-emerald-700 mb-2">
                    <i class="fa-solid fa-code-compare text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-800 block">Compare Quotes</span>
                <span class="text-[10px] text-slate-500 block">Volume Discounts</span>
            </a>
            <a href="{{ route('products.index') }}" class="p-3 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-blue-700 mb-2">
                    <i class="fa-solid fa-boxes-stacked text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-800 block">Browse Products</span>
                <span class="text-[10px] text-slate-500 block">India, China, Europe</span>
            </a>
            <a href="{{ route('calculator.index') }}" class="p-3 bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-amber-700 mb-2">
                    <i class="fa-solid fa-calculator text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-amber-800 block">Duty & Landed Calc</span>
                <span class="text-[10px] text-slate-500 block">UK Tariff Check</span>
            </a>
        </div>
    </div>

    <!-- Active Orders & RFQs Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Recent Purchase Orders</h2>
                <a href="{{ route('buyer.orders') }}" class="text-xs font-bold text-sky-700 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center hover:border-slate-200 transition">
                        <div>
                            <a href="{{ route('orders.show', $order->id) }}" class="font-mono text-xs font-bold text-sky-800 hover:underline block">
                                #{{ $order->order_number }}
                            </a>
                            <span class="text-[11px] text-slate-500">{{ $order->created_at->format('d M Y') }} • {{ count($order->orderItems) }} Item(s)</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-slate-900 text-xs block">£{{ number_format($order->total_amount, 2) }}</span>
                            <span class="inline-flex items-center px-2 py-0.2 rounded-full text-[9px] font-bold bg-white text-slate-700 border border-slate-200 mt-0.5">
                                {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">No orders placed yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Quotations Received -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Latest Supplier Quotes</h2>
                <a href="{{ route('buyer.quotations') }}" class="text-xs font-bold text-sky-700 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentQuotations as $quote)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center hover:border-slate-200 transition">
                        <div>
                            <span class="font-bold text-slate-900 text-xs block">{{ $quote->supplier->company->company_name ?? $quote->supplier->name }}</span>
                            <span class="text-[11px] text-slate-500">Ref: {{ $quote->rfq ? $quote->rfq->product_name : 'Custom Machining' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-emerald-700 text-xs block">£{{ number_format($quote->total_landed_cost, 2) }}</span>
                            @if($quote->rfq)
                                <a href="{{ route('rfqs.compare', $quote->rfq->id) }}" class="text-[10px] font-bold text-sky-700 hover:underline">Compare &rarr;</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">No quotes received yet.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
