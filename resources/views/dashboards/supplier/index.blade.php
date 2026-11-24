@extends('layouts.supplier')

@section('title', 'Supplier Command Center — Perlcon International')
@section('header_title', 'Manufacturing Control Center')
@section('header_subtitle', 'Oversee plant machinery catalog, RFQ quotation bids, and export orders')

@section('content')
<div class="space-y-8">

    <!-- KPI Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-emerald-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Products</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['products_count'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">Active Catalog Items</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-emerald-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Inquiries</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-brand-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-inbox"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['enquiries_count'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">Direct Buyer Inquiries</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-emerald-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Quotes Submitted</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-comments-dollar"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['quotations_count'] }}</p>
            <p class="text-[11px] text-emerald-600 font-bold mt-1.5">{{ $stats['accepted_quotations'] }} Accepted POs</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-emerald-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Sales</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sterling-sign"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">£{{ number_format($stats['total_sales'], 2) }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">{{ $stats['order_items_count'] }} Fulfillments</p>
        </div>

    </div>

    <!-- Quick Action Launchpad -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Supplier Launchpad</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('supplier.products.create') }}" class="p-3 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-emerald-700 mb-2">
                    <i class="fa-solid fa-plus text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-800 block">Add Product</span>
                <span class="text-[10px] text-slate-500 block">Publish Machinery</span>
            </a>
            <a href="{{ route('rfqs.index') }}" target="_blank" class="p-3 bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-amber-700 mb-2">
                    <i class="fa-solid fa-gavel text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-amber-800 block">Bid on RFQs</span>
                <span class="text-[10px] text-slate-500 block">UK / EU Tenders</span>
            </a>
            <a href="{{ route('supplier.orders') }}" class="p-3 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-blue-700 mb-2">
                    <i class="fa-solid fa-boxes-packing text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-800 block">Manage Orders</span>
                <span class="text-[10px] text-slate-500 block">Production & Dispatch</span>
            </a>
            <a href="{{ route('supplier.profile') }}" class="p-3 bg-slate-50 hover:bg-purple-50 border border-slate-200 hover:border-purple-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-purple-700 mb-2">
                    <i class="fa-solid fa-certificate text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-purple-800 block">Plant Profile</span>
                <span class="text-[10px] text-slate-500 block">ISO & Tonnage</span>
            </a>
        </div>
    </div>

    <!-- Active Orders & Inquiries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Purchase Orders Allocated -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Purchase Orders to Fulfill</h2>
                <a href="{{ route('supplier.orders') }}" class="text-xs font-bold text-emerald-700 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentOrders as $item)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center hover:border-slate-200 transition">
                        <div>
                            <span class="font-mono text-xs font-bold text-emerald-800 block">#{{ $item->purchase_order_number }}</span>
                            <span class="text-[11px] text-slate-600 font-bold">{{ $item->product_name }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ number_format($item->quantity) }} {{ $item->unit }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-slate-900 text-xs block">£{{ number_format($item->subtotal, 2) }}</span>
                            <span class="inline-flex items-center px-2 py-0.2 rounded-full text-[9px] font-bold bg-white text-slate-700 border border-slate-200 mt-0.5">
                                {{ ucwords(str_replace('_', ' ', $item->supplier_status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">No allocated purchase orders yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Buyer Inquiries Inbox</h2>
                <a href="{{ route('supplier.enquiries') }}" class="text-xs font-bold text-emerald-700 hover:underline">View All &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse($recentEnquiries as $enquiry)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center hover:border-slate-200 transition">
                        <div>
                            <span class="font-bold text-slate-900 text-xs block">{{ $enquiry->name }}</span>
                            <span class="text-[11px] text-slate-500 truncate block max-w-xs">{{ $enquiry->subject ?? 'Inquiry on ' . ($enquiry->product->name ?? 'machinery') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block">{{ $enquiry->created_at->diffForHumans() }}</span>
                            <a href="{{ route('messages.index') }}" class="text-[10px] font-bold text-emerald-700 hover:underline">Reply &rarr;</a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">No inquiries received yet.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
