@extends('layouts.buyer')

@section('title', 'Received Quotations — Buyer Portal')
@section('header_title', 'Received Supplier Quotations')
@section('header_subtitle', 'Review binding commercial bids, inspection lead times, landed costs, and accept to Purchase Order')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($quotations->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-comments-dollar text-3xl mb-3 text-slate-300 block"></i>
                <p class="text-sm font-semibold">No quotations received yet.</p>
                <p class="text-xs text-slate-500 mt-1">Post an RFQ to request competitive manufacturing quotes from India, China, and Europe.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($quotations as $q)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-sky-800">#{{ $q->quotation_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $q->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-brand-800 border border-blue-200' }}">
                                    {{ ucfirst($q->status) }}
                                </span>
                                <span class="text-[11px] text-slate-400">Valid until: {{ $q->valid_until ? date('d M Y', strtotime($q->valid_until)) : '30 Days' }}</span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $q->supplier->company->company_name ?? $q->supplier->name }} 
                                <span class="text-xs font-bold text-slate-500">({{ $q->supplier->country }})</span>
                            </h3>

                            <div class="text-xs text-slate-600 font-medium">
                                For: <strong class="text-slate-900">{{ $q->rfq ? $q->rfq->product_name : ($q->product ? $q->product->name : 'Industrial Cargo') }}</strong> • Volume: {{ number_format($q->quantity) }} Units
                            </div>

                            <div class="text-xs text-slate-500 flex flex-wrap gap-x-3 gap-y-1">
                                <span>Freight Share: <strong>£{{ number_format($q->shipping_cost, 2) }}</strong> ({{ ucwords(str_replace('_', ' ', $q->shipping_method)) }})</span>
                                <span>•</span>
                                <span>Est. Delivery: <strong>{{ $q->estimated_delivery_days }} days</strong></span>
                                <span>•</span>
                                <span>Est. UK Duty: <strong>£{{ number_format($q->estimated_customs_duty, 2) }}</strong></span>
                            </div>
                        </div>

                        <div class="text-right flex flex-col sm:items-end w-full lg:w-auto flex-shrink-0">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Total Landed CIF + VAT:</span>
                            <span class="text-xl font-black text-slate-900 mb-2">£{{ number_format($q->total_landed_cost, 2) }}</span>

                            <div class="flex items-center space-x-2">
                                @if($q->rfq)
                                    <a href="{{ route('rfqs.compare', $q->rfq->id) }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                        Compare Quotes
                                    </a>
                                @endif

                                @if($q->status !== 'accepted')
                                    <form action="{{ route('quotations.accept', $q->id) }}" method="POST" onsubmit="return confirm('Accept this quotation and generate an official purchase order?');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Accept & Order</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                                        PO Generated
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($quotations->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $quotations->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
