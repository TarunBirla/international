@extends('layouts.app')

@section('title', 'Inquiries Inbox — Supplier Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buyer Inquiries Inbox</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Respond to direct buyer inquiries for your machinery and catalog items.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Manage Products</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Inquiries</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Quotations</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Subscriptions</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($enquiries->isEmpty())
            <div class="p-12 text-center text-slate-500 text-xs">No buyer inquiries received yet.</div>
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
                                Buyer: <strong>{{ $enq->buyer_name }}</strong> ({{ $enq->buyer_company ?? 'UK' }}, {{ $enq->buyer_country }}) • Required: {{ $enq->required_quantity }} {{ $enq->unit }}
                            </div>
                            <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $enq->message }}</p>
                        </div>

                        <a href="{{ route('messages.index', ['user_id' => $enq->buyer_id ?? 0]) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition flex-shrink-0">
                            Reply to Buyer &rarr;
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
