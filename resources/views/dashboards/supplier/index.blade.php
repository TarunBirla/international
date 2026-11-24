@extends('layouts.app')

@section('title', 'Supplier Portal — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider block">Exporter & Manufacturer Portal</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $user->company->company_name ?? $user->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">{{ $user->country }} • Contact: {{ $user->name }} ({{ $user->email }})</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('supplier.products.create') }}" class="px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus"></i>
                    <span>List New Product</span>
                </a>
                <a href="{{ route('rfqs.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs transition shadow-sm">
                    Browse Open RFQs
                </a>
            </div>
        </div>

        <!-- Supplier Navigation Subtabs -->
        <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-200 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Manage Products ({{ $stats['products_count'] }})</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Inquiries Inbox ({{ $stats['enquiries_count'] }})</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Quotation Builder ({{ $stats['quotations_count'] }})</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Production Orders ({{ $stats['order_items_count'] }})</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Subscription Plan</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Listed Products</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['products_count'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Buyer Inquiries</span>
            <span class="text-2xl font-black text-brand-700">{{ $stats['enquiries_count'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Quotations Submitted</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['quotations_count'] }}</span>
            <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">{{ $stats['accepted_quotations'] }} Accepted</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total Export Sales</span>
            <span class="text-2xl font-black text-emerald-700">£{{ number_format($stats['total_sales'], 2) }}</span>
        </div>
    </div>

    <!-- Production Orders Received -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-900">Assigned Purchase Orders (PO)</h2>
                <span class="text-xs text-slate-500">Update factory production milestones, packaging, and dispatch tracking numbers.</span>
            </div>
            <a href="{{ route('supplier.orders') }}" class="text-xs font-bold text-brand-700 hover:underline">View All Orders &rarr;</a>
        </div>

        @if($recentOrders->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">No assigned purchase orders yet.</p>
        @else
            <div class="space-y-4">
                @foreach($recentOrders as $item)
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-brand-700">{{ $item->purchase_order_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                    {{ ucfirst(str_replace('_', ' ', $item->supplier_status)) }}
                                </span>
                                <span class="text-xs text-slate-400">Linked to Parent Order #{{ $item->order->order_number ?? 'ORD' }}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">
                                {{ $item->product_name }} &times; {{ $item->quantity }} {{ $item->unit }}
                            </h3>
                            <span class="text-xs text-slate-500">Buyer: <strong>{{ $item->order->buyer->company->company_name ?? $item->order->buyer->name }}</strong> (United Kingdom)</span>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <span class="text-xs text-slate-500 block">Subtotal</span>
                                <span class="text-base font-black text-slate-900">£{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                            
                            <!-- Update Status Button Form -->
                            <form action="{{ route('supplier.orders.update-status', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                <select name="supplier_status" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold bg-white">
                                    <option value="pending" {{ $item->supplier_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accepted" {{ $item->supplier_status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="in_production" {{ $item->supplier_status === 'in_production' ? 'selected' : '' }}>In Production</option>
                                    <option value="ready_for_dispatch" {{ $item->supplier_status === 'ready_for_dispatch' ? 'selected' : '' }}>Ready for Dispatch</option>
                                    <option value="dispatched" {{ $item->supplier_status === 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                </select>
                                <button type="submit" class="px-3 py-1.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-lg text-xs transition">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2 Column: Inquiries & Quotations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Inquiries -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">Recent Buyer Inquiries</h3>
                <a href="{{ route('supplier.enquiries') }}" class="text-xs font-bold text-brand-700 hover:underline">View All</a>
            </div>

            @if($recentEnquiries->isEmpty())
                <p class="text-xs text-slate-400 py-4 text-center">No inquiries yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentEnquiries as $enq)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-slate-400 block">{{ $enq->enquiry_number }}</span>
                                <strong class="text-xs text-slate-900 block truncate max-w-[200px]">{{ $enq->subject }}</strong>
                                <span class="text-[11px] text-slate-500">From: {{ $enq->buyer_name }} ({{ $enq->buyer_country }})</span>
                            </div>
                            <a href="{{ route('messages.index', ['user_id' => $enq->buyer_id ?? 0]) }}" class="px-3 py-1.5 bg-brand-700 text-white rounded-lg text-xs font-bold hover:bg-brand-800 transition">
                                Reply
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quotations -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">Submitted Quotations</h3>
                <a href="{{ route('supplier.quotations') }}" class="text-xs font-bold text-brand-700 hover:underline">View All</a>
            </div>

            @if($recentQuotations->isEmpty())
                <p class="text-xs text-slate-400 py-4 text-center">No quotations submitted yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentQuotations as $quote)
                        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-slate-400 block">{{ $quote->quotation_number }}</span>
                                <strong class="text-xs text-slate-900 block truncate max-w-[200px]">{{ $quote->rfq ? $quote->rfq->product_name : 'Cargo' }}</strong>
                                <span class="text-[11px] text-slate-500">£{{ number_format($quote->total_landed_cost, 2) }} • {{ ucfirst($quote->status) }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $quote->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $quote->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
