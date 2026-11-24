@extends('layouts.app')

@section('title', 'Buyer Dashboard — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Buyer Procurement Portal</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Welcome, {{ $user->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">{{ $user->company->company_name ?? 'UK Procurement Division' }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('rfqs.create') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                    <i class="fa-solid fa-file-pen"></i>
                    <span>Post New RFQ</span>
                </a>
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                    Browse Products
                </a>
            </div>
        </div>

        <!-- Dashboard Navigation Subtabs -->
        <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-200 text-xs font-bold">
            <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Overview</a>
            <a href="{{ route('buyer.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">My RFQs ({{ $stats['rfqs_count'] }})</a>
            <a href="{{ route('buyer.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Received Quotations ({{ $stats['quotations_count'] }})</a>
            <a href="{{ route('buyer.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Purchase Orders ({{ $stats['orders_count'] }})</a>
            <a href="{{ route('buyer.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">My Enquiries</a>
            <a href="{{ route('buyer.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Company Details</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total RFQs Posted</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['rfqs_count'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Received Quotations</span>
            <span class="text-2xl font-black text-brand-700">{{ $stats['quotations_count'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Active Orders</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['orders_count'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total Landed Spend</span>
            <span class="text-2xl font-black text-emerald-700">£{{ number_format($stats['total_spent'], 2) }}</span>
        </div>
    </div>

    <!-- Active Orders Section with Tracking Stepper Preview -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-900">Recent Purchase Orders & Consignments</h2>
                <span class="text-xs text-slate-500">Live 14-stage tracking from origin factories to UK destination.</span>
            </div>
            <a href="{{ route('buyer.orders') }}" class="text-xs font-bold text-brand-700 hover:underline">View All Orders &rarr;</a>
        </div>

        @if($recentOrders->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">No orders placed yet. Accept a quotation or buy items directly from the catalog.</p>
        @else
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-brand-700">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-100 text-brand-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">
                                {{ count($order->orderItems) }} Product Item(s) • Consignment: {{ $order->tracking_number ?? 'PRL-UK-TRACK' }}
                            </h3>
                            <span class="text-xs text-slate-500 block">Deliver to: {{ $order->shipping_address['address_line_1'] ?? 'UK' }}, {{ $order->shipping_address['city'] ?? 'London' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3 w-full md:w-auto justify-between">
                            <div class="text-right">
                                <span class="text-xs text-slate-500 block">Landed CIF + VAT:</span>
                                <span class="text-base font-black text-slate-900">£{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                View Order & 14-Stage Tracker
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2 Column: RFQs & Quotations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- RFQs -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">My Requests for Quotation (RFQs)</h3>
                <a href="{{ route('buyer.rfqs') }}" class="text-xs font-bold text-brand-700 hover:underline">View All</a>
            </div>

            @if($recentRfqs->isEmpty())
                <p class="text-xs text-slate-400 py-4 text-center">No active RFQs.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentRfqs as $rfq)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-slate-400 block">{{ $rfq->rfq_number }}</span>
                                <strong class="text-xs text-slate-900 block truncate max-w-[200px]">{{ $rfq->product_name }}</strong>
                                <span class="text-[11px] text-brand-700 font-semibold">{{ $rfq->quotations_count }} Quote(s) received</span>
                            </div>
                            <a href="{{ route('rfqs.show', $rfq->id) }}" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold hover:bg-slate-50 transition">
                                View
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Received Quotations -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">Received Supplier Quotations</h3>
                <a href="{{ route('buyer.quotations') }}" class="text-xs font-bold text-brand-700 hover:underline">View All</a>
            </div>

            @if($recentQuotations->isEmpty())
                <p class="text-xs text-slate-400 py-4 text-center">No quotations received yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentQuotations as $quote)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-slate-400 block">{{ $quote->quotation_number }}</span>
                                <strong class="text-xs text-slate-900 block truncate max-w-[200px]">{{ $quote->supplier->company->company_name ?? $quote->supplier->name }}</strong>
                                <span class="text-[11px] text-slate-500 font-semibold">Total Landed: £{{ number_format($quote->total_landed_cost, 2) }}</span>
                            </div>
                            @if($quote->rfq)
                                <a href="{{ route('rfqs.compare', $quote->rfq_id) }}" class="px-3 py-1.5 bg-brand-700 text-white rounded-lg text-xs font-bold hover:bg-brand-800 transition">
                                    Compare
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
