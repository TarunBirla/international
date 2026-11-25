@extends(auth()->check() ? auth()->user()->getDashboardLayout() : 'layouts.app')

@section('title', 'Open Requests For Quotation (RFQs) — Mart')
@section('header_title', 'Requests For Quotation (RFQs)')
@section('header_subtitle', 'Browse open international procurement requirements and tenders')

@section('content')
@if(!auth()->check())
<div class="bg-slate-50 border-b border-slate-200 py-8 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
                    <span>/</span>
                    <span class="text-slate-900 font-semibold">RFQs</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Open Requests for Quotation (RFQs)</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Verified procurement requirements posted by industrial buyers and engineering contractors.</p>
            </div>
            <a href="{{ route('rfqs.create') }}" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs sm:text-sm transition shadow-md shadow-amber-500/20 flex items-center space-x-2">
                <i class="fa-solid fa-file-pen"></i>
                <span>Post A New RFQ</span>
            </a>
        </div>
    </div>
</div>
@else
<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-xs text-slate-500">All active procurement requests published on the marketplace</p>
    </div>
    @if(auth()->user()->isBuyer() || auth()->user()->isAdmin())
    <a href="{{ route('rfqs.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
        <i class="fa-solid fa-plus"></i>
        <span>+ Post A New RFQ</span>
    </a>
    @endif
</div>
@endif

<div class="{{ auth()->check() ? 'space-y-4' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-4' }}">
    <div class="space-y-4">
        @foreach($rfqs as $rfq)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono text-xs font-bold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-lg border border-brand-200">
                            {{ $rfq->rfq_number }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase {{ $rfq->status === 'open' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                        </span>
                        <span class="text-xs text-slate-500">
                            Posted {{ $rfq->created_at->diffForHumans() }} by <strong>{{ $rfq->buyer->company->company_name ?? $rfq->buyer->name }}</strong>
                        </span>
                    </div>

                    <h2 class="text-lg font-black text-slate-900 hover:text-brand-700 transition">
                        <a href="{{ route('rfqs.show', $rfq->id) }}">{{ $rfq->product_name }}</a>
                    </h2>

                    <p class="text-xs text-slate-600 line-clamp-2 max-w-3xl">
                        {{ $rfq->technical_specifications }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1">
                        <span>Quantity: <strong class="text-slate-800">{{ number_format($rfq->quantity) }} {{ $rfq->unit }}</strong></span>
                        <span>•</span>
                        <span>Destination: <strong class="text-slate-800">{{ $rfq->destination_country }} ({{ $rfq->destination_port_city ?? 'Felixstowe' }})</strong></span>
                        <span>•</span>
                        <span>Required By: <strong class="text-slate-800">{{ $rfq->required_delivery_date ? $rfq->required_delivery_date->format('d M Y') : 'Flexible' }}</strong></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row md:flex-col items-end gap-2 flex-shrink-0 w-full md:w-auto">
                    <div class="text-right">
                        <span class="text-xs text-slate-500 block">Target Budget</span>
                        <span class="text-lg font-black text-slate-900">
                            {{ $rfq->target_price ? '£' . number_format($rfq->target_price, 2) . ' / ' . $rfq->unit : 'Open to Quote' }}
                        </span>
                        <span class="text-[11px] font-semibold text-brand-700 block mt-0.5">
                            {{ $rfq->quotations_count }} Quotation(s) Received
                        </span>
                    </div>

                    <a href="{{ route('rfqs.show', $rfq->id) }}" class="w-full sm:w-auto px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition text-center shadow-sm">
                        View RFQ Details &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $rfqs->links() }}
    </div>
</div>
@endsection
