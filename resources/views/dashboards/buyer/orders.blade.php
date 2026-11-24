@extends('layouts.app')

@section('title', 'My Orders & Consignments — Buyer Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Purchase Orders & Shipments</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Track multi-supplier purchase orders, shipping statuses, and UK customs clearances.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('buyer.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My RFQs</a>
            <a href="{{ route('buyer.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Received Quotations</a>
            <a href="{{ route('buyer.orders') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Purchase Orders</a>
            <a href="{{ route('buyer.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My Enquiries</a>
            <a href="{{ route('buyer.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Company Details</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($orders->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">No orders placed yet.</div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($orders as $order)
                    <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-brand-700">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-100 text-brand-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ count($order->orderItems) }} Product Item(s) • Tracking: {{ $order->tracking_number ?? 'PRL-UK-TRACK' }}
                            </h3>
                            <div class="text-xs text-slate-500">
                                Destination: {{ $order->shipping_address['address_line_1'] ?? 'UK' }}, {{ $order->shipping_address['city'] ?? 'London' }}
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <span class="text-xs text-slate-500 block">Landed Total</span>
                                <span class="text-lg font-black text-slate-900">£{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                View Order & 14-Stage Tracker &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
