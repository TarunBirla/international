@extends('layouts.supplier')

@section('title', 'Submitted Quotations — Supplier Portal')
@section('header_title', 'Submitted Quotations & Bids')
@section('header_subtitle', 'Review binding price quotes, shipping terms, and buyer acceptance status')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($quotations->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-comments-dollar text-3xl mb-3 text-slate-300 block"></i>
                <p class="text-sm font-semibold">No quotations submitted yet.</p>
                <p class="text-xs text-slate-500 mt-1">Browse open RFQs posted by UK and European buyers to submit your first quotation.</p>
                <a href="{{ route('rfqs.index') }}" target="_blank" class="inline-block mt-3 px-5 py-2 bg-emerald-600 text-white font-bold rounded-xl text-xs">Browse Open RFQs</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($quotations as $q)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-emerald-800">#{{ $q->quotation_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $q->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($q->status) }}
                                </span>
                                <span class="text-xs text-slate-400">Submitted {{ $q->created_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $q->rfq ? $q->rfq->product_name : ($q->product ? $q->product->name : 'Machinery Quote') }}
                            </h3>

                            <div class="text-xs text-slate-600 font-medium">
                                Buyer: <strong class="text-slate-900">{{ $q->buyer->company->company_name ?? $q->buyer->name }}</strong> ({{ $q->buyer->country }}) • {{ number_format($q->quantity) }} Units &times; £{{ number_format($q->unit_price, 2) }}
                            </div>

                            <div class="text-xs text-slate-500 flex flex-wrap gap-x-3 gap-y-1">
                                <span>Freight Share: <strong>£{{ number_format($q->shipping_cost, 2) }}</strong> ({{ ucwords(str_replace('_', ' ', $q->shipping_method)) }})</span>
                                <span>•</span>
                                <span>Est. Delivery: <strong>{{ $q->estimated_delivery_days }} days</strong></span>
                                <span>•</span>
                                <span>FOB Port: <strong>{{ $q->port_of_loading ?? 'Origin Port' }}</strong></span>
                            </div>
                        </div>

                        <div class="text-right flex flex-col sm:items-end w-full lg:w-auto flex-shrink-0">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Total Landed Value:</span>
                            <span class="text-xl font-black text-slate-900 mb-2">£{{ number_format($q->total_landed_cost, 2) }}</span>

                            @if($q->status === 'accepted')
                                <span class="px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold flex items-center space-x-1">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                    <span>Converted to PO</span>
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">
                                    Awaiting Buyer Award
                                </span>
                            @endif
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
