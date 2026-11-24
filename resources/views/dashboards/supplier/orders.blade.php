@extends('layouts.app')

@section('title', 'Production Orders — Supplier Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Assigned Production Purchase Orders (PO)</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Manage production schedules, quality inspections, and export dispatch.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Manage Products</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Inquiries</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Quotations</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Production Orders</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Subscriptions</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($orderItems->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">No production purchase orders assigned yet.</div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($orderItems as $item)
                    <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">{{ $item->purchase_order_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                    {{ ucfirst(str_replace('_', ' ', $item->supplier_status)) }}
                                </span>
                                <span class="text-xs text-slate-400">Order: #{{ $item->order->order_number ?? 'ORD' }}</span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">{{ $item->product_name }}</h3>
                            <div class="text-xs text-slate-500">
                                Quantity: <strong>{{ $item->quantity }} {{ $item->unit }}</strong> &times; £{{ number_format($item->unit_price, 2) }} • Buyer: {{ $item->order->buyer->company->company_name ?? $item->order->buyer->name }} (UK)
                            </div>
                            @if($item->supplier_tracking_number)
                                <div class="text-[11px] font-mono text-slate-500">
                                    Dispatch Ref: <strong>{{ $item->supplier_tracking_number }}</strong>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
                            <div class="text-right">
                                <span class="text-xs text-slate-500 block">Subtotal</span>
                                <span class="text-base font-black text-slate-900">£{{ number_format($item->subtotal, 2) }}</span>
                            </div>

                            <form action="{{ route('supplier.orders.update-status', $item->id) }}" method="POST" class="flex items-center space-x-2 bg-slate-50 p-2 rounded-xl border border-slate-200">
                                @csrf
                                <select name="supplier_status" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold bg-white">
                                    <option value="pending" {{ $item->supplier_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accepted" {{ $item->supplier_status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="in_production" {{ $item->supplier_status === 'in_production' ? 'selected' : '' }}>In Production</option>
                                    <option value="ready_for_dispatch" {{ $item->supplier_status === 'ready_for_dispatch' ? 'selected' : '' }}>Ready for Dispatch</option>
                                    <option value="dispatched" {{ $item->supplier_status === 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                </select>
                                <input type="text" name="supplier_tracking_number" value="{{ $item->supplier_tracking_number }}" placeholder="Tracking/AWB" class="w-24 px-2 py-1.5 rounded-lg border border-slate-200 text-xs bg-white">
                                <button type="submit" class="px-3 py-1.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-lg text-xs transition">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $orderItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
