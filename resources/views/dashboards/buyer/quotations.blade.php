@extends('layouts.app')

@section('title', 'Received Quotations — Buyer Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Received Supplier Quotations</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Review binding quotations submitted by international manufacturers.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('buyer.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My RFQs</a>
            <a href="{{ route('buyer.quotations') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Received Quotations</a>
            <a href="{{ route('buyer.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Purchase Orders</a>
            <a href="{{ route('buyer.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My Enquiries</a>
            <a href="{{ route('buyer.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Company Details</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($quotations->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">No quotations received yet.</div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($quotations as $q)
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-slate-700">{{ $q->quotation_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $q->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-brand-800' }}">
                                    {{ ucfirst($q->status) }}
                                </span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $q->supplier->company->company_name ?? $q->supplier->name }} ({{ $q->supplier->country }})
                            </h3>
                            <div class="text-xs text-slate-500">
                                For: <strong>{{ $q->rfq ? $q->rfq->product_name : ($q->product ? $q->product->name : 'Industrial Cargo') }}</strong> • {{ $q->quantity }} Units
                            </div>
                            <div class="text-xs text-slate-500">
                                Freight: £{{ number_format($q->shipping_cost, 2) }} ({{ $q->shipping_method }}) • Delivery: {{ $q->estimated_delivery_days }} days
                            </div>
                        </div>

                        <div class="text-right flex flex-col sm:items-end w-full sm:w-auto">
                            <span class="text-xs text-slate-500">Total Landed (CIF + VAT):</span>
                            <span class="text-lg font-black text-slate-900 mb-2">£{{ number_format($q->total_landed_cost, 2) }}</span>
                            
                            @if($q->status !== 'accepted')
                                <form action="{{ route('quotations.accept', $q->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Accept this quotation?')" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                                        Accept & Convert to Order
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-bold text-emerald-700"><i class="fa-solid fa-check mr-1"></i> Accepted</span>
                            @endif
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
