@extends('layouts.app')

@section('title', 'About Mart — B2B Marketplace')

@section('content')
<div class="bg-white border-b border-slate-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">About Mart</h1>
        <p class="text-slate-600 text-sm mt-2 max-w-2xl">
            Connecting wholesale buyers directly with verified manufacturers and industrial suppliers.
        </p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10 text-slate-700 text-sm leading-relaxed">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-4">
        <h2 class="text-xl font-extrabold text-slate-900">Our Mission</h2>
        <p>
            Mart was created to make B2B wholesale trade simple, fast, and transparent. We help businesses easily discover trusted manufacturers, post their buying requirements, compare quotations, and purchase quality goods at direct factory prices.
        </p>
        <p>
            Like IndiaMART, Mart empowers businesses with an intuitive supplier discovery network, real-time RFQ negotiation, verified seller badges, and safe order management.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-3xl font-black text-brand-700 block mb-1">100%</span>
            <strong class="text-slate-900 block text-xs">Audited Factories</strong>
            <span class="text-[11px] text-slate-500">Every supplier is verified with ISO & CE certificates.</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-3xl font-black text-brand-700 block mb-1">48h</span>
            <strong class="text-slate-900 block text-xs">UK Customs Clearance</strong>
            <span class="text-[11px] text-slate-500">Electronic CDS pre-lodged declarations with Junaid & Co.</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-3xl font-black text-brand-700 block mb-1">0%</span>
            <strong class="text-slate-900 block text-xs">Hidden Landed Costs</strong>
            <span class="text-[11px] text-slate-500">Full transparency on CIF, import duty, and VAT upfront.</span>
        </div>
    </div>
</div>
@endsection
