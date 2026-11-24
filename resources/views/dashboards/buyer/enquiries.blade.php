@extends('layouts.app')

@section('title', 'My Enquiries — Buyer Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Product Enquiries Sent</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Track direct inquiries sent to overseas manufacturers.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('buyer.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My RFQs</a>
            <a href="{{ route('buyer.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Received Quotations</a>
            <a href="{{ route('buyer.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Purchase Orders</a>
            <a href="{{ route('buyer.enquiries') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">My Enquiries</a>
            <a href="{{ route('buyer.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Company Details</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($enquiries->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">No direct enquiries sent.</div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($enquiries as $enq)
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-slate-700">{{ $enq->enquiry_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                    {{ ucfirst(str_replace('_', ' ', $enq->status)) }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $enq->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">{{ $enq->subject }}</h3>
                            <div class="text-xs text-slate-500">
                                Supplier: <strong>{{ $enq->supplier->company->company_name ?? $enq->supplier->name }}</strong> ({{ $enq->supplier->country }}) • Qty: {{ $enq->required_quantity }} {{ $enq->unit }}
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-1 italic">{{ $enq->message }}</p>
                        </div>

                        <a href="{{ route('messages.index', ['user_id' => $enq->supplier_id]) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                            Open Chat & Reply &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $enquiries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
