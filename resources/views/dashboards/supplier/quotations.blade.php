@extends('layouts.app')

@section('title', 'Quotation Management — Supplier Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Quotations Submitted</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Track your binding price quotes, shipping terms, and conversion rates.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Manage Products</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Inquiries</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Quotations</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Subscriptions</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($quotations->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">No quotations submitted yet.</div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($quotations as $q)
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-slate-700">{{ $q->quotation_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $q->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($q->status) }}
                                </span>
                                <span class="text-xs text-slate-400">Date: {{ $q->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $q->rfq ? $q->rfq->product_name : ($q->product ? $q->product->name : 'Machinery Quote') }}
                            </h3>
                            <div class="text-xs text-slate-500">
                                Buyer: <strong>{{ $q->buyer->company->company_name ?? $q->buyer->name }}</strong> ({{ $q->buyer->country }}) • {{ $q->quantity }} Units &times; £{{ number_format($q->unit_price, 2) }}
                            </div>
                            <div class="text-xs text-slate-500">
                                Freight: £{{ number_format($q->shipping_cost, 2) }} ({{ $q->shipping_method }}) • Delivery: {{ $q->estimated_delivery_days }} days
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs text-slate-500 block">Total Landed Amount</span>
                            <span class="text-lg font-black text-slate-900">£{{ number_format($q->total_landed_cost, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
