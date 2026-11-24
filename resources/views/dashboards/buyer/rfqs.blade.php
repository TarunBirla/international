@extends('layouts.buyer')

@section('title', 'My RFQs & Tenders — Buyer Portal')
@section('header_title', 'My Requests for Quotation (RFQs)')
@section('header_subtitle', 'Post custom machinery tenders, compare bids, edit specifications, or award contracts')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-sm font-black text-slate-900">Active Industrial Tenders</h2>
            <p class="text-xs text-slate-500">Track supplier quotations and negotiate volume discounts</p>
        </div>
        <a href="{{ route('rfqs.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-2 flex-shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>+ Post New RFQ</span>
        </a>
    </div>

    <!-- RFQs List -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($rfqs->isEmpty())
            <div class="p-12 text-center">
                <i class="fa-solid fa-file-pen text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">You haven't posted any RFQs yet</h3>
                <p class="text-xs text-slate-500 mt-1">Submit your machinery specifications to receive competitive quotes from India, China, and Europe.</p>
                <a href="{{ route('rfqs.create') }}" class="inline-block mt-4 px-6 py-2.5 bg-sky-600 text-white font-bold rounded-xl text-xs">Post RFQ Now</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($rfqs as $rfq)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <!-- RFQ Info -->
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center space-x-2.5">
                                <span class="font-mono text-xs font-bold text-sky-800">#{{ $rfq->rfq_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $rfq->status === 'open' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                                </span>
                                <span class="text-xs text-slate-400">Posted {{ $rfq->created_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                <a href="{{ route('rfqs.show', $rfq->id) }}" class="hover:text-sky-700 transition">{{ $rfq->product_name }}</a>
                            </h3>

                            <div class="text-xs text-slate-500 flex flex-wrap gap-x-3 gap-y-1 font-medium">
                                <span>Volume: <strong class="text-slate-800">{{ number_format($rfq->quantity) }} {{ $rfq->unit }}</strong></span>
                                <span>•</span>
                                <span>Destination: <strong class="text-slate-800">{{ $rfq->destination_country }}</strong></span>
                                <span>•</span>
                                <span class="text-sky-700 font-bold">{{ count($rfq->quotations) }} Quotation(s) received</span>
                            </div>
                        </div>

                        <!-- Action Buttons (Compare, View, Edit, Delete) -->
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            @if($rfq->quotations->isNotEmpty())
                                <a href="{{ route('rfqs.compare', $rfq->id) }}" class="px-3.5 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                                    <i class="fa-solid fa-code-compare"></i>
                                    <span>Compare Quotes ({{ count($rfq->quotations) }})</span>
                                </a>
                            @endif

                            <a href="{{ route('rfqs.show', $rfq->id) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                                View Bids
                            </a>

                            <a href="{{ route('rfqs.edit', $rfq->id) }}" title="Edit RFQ" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-sky-50 text-slate-600 hover:text-sky-700 flex items-center justify-center transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>

                            <form action="{{ route('rfqs.destroy', $rfq->id) }}" method="POST" onsubmit="return confirm('Permanently delete RFQ #{{ $rfq->rfq_number }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete RFQ" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($rfqs->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $rfqs->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
