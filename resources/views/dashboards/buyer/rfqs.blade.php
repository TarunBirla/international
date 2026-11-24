@extends('layouts.app')

@section('title', 'My RFQs — Buyer Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Requests for Quotation (RFQs)</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Manage custom component RFQs, compare quotes, and award contracts.</p>
            </div>
            <a href="{{ route('rfqs.create') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                <i class="fa-solid fa-file-pen"></i>
                <span>Post New RFQ</span>
            </a>
        </div>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('buyer.rfqs') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">My RFQs</a>
            <a href="{{ route('buyer.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Received Quotations</a>
            <a href="{{ route('buyer.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Purchase Orders</a>
            <a href="{{ route('buyer.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My Enquiries</a>
            <a href="{{ route('buyer.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Company Details</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($rfqs->isEmpty())
            <div class="p-12 text-center">
                <i class="fa-solid fa-file-pen text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">You haven't posted any RFQs yet</h3>
                <a href="{{ route('rfqs.create') }}" class="inline-block mt-4 px-6 py-2.5 bg-amber-500 text-white font-bold rounded-xl text-xs">Post RFQ Now</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($rfqs as $rfq)
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-brand-700">{{ $rfq->rfq_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $rfq->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                                </span>
                                <span class="text-xs text-slate-400">Posted {{ $rfq->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">
                                <a href="{{ route('rfqs.show', $rfq->id) }}" class="hover:text-brand-700 transition">{{ $rfq->product_name }}</a>
                            </h3>
                            <div class="text-xs text-slate-500">
                                {{ number_format($rfq->quantity) }} {{ $rfq->unit }} • Destination: {{ $rfq->destination_country }} • {{ count($rfq->quotations) }} Quotation(s) received
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            @if($rfq->quotations->isNotEmpty())
                                <a href="{{ route('rfqs.compare', $rfq->id) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                                    Compare Quotes ({{ count($rfq->quotations) }})
                                </a>
                            @endif
                            <a href="{{ route('rfqs.show', $rfq->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                                Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $rfqs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
