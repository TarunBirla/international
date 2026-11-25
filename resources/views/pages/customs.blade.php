@extends('layouts.app')

@section('title', 'UK Customs Clearance Integration & Junaid & Co. — Mart')

@section('content')
<div class="bg-white border-b border-slate-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Customs Clearance</span>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                Partner: Junaid & Co.
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-2">UK Customs Clearance Integration</h1>
        <p class="text-slate-600 text-sm mt-2 max-w-2xl">
            Streamlined HMRC CDS customs brokerage, C88 import declarations, and Postponed VAT Accounting (PVA) powered in partnership with Junaid & Co.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- Intro Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="space-y-4">
            <h2 class="text-2xl font-black text-slate-900">Dedicated UK Customs Brokerage for Industrial Cargo</h2>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Importing heavy machinery and fabricated steel into the UK requires strict adherence to HMRC trade classification, Rules of Origin, and environmental declarations. Mart has integrated direct electronic documentation sharing with Junaid & Co. Customs Clearance Specialists at the Port of Felixstowe and London Gateway.
            </p>
            <div class="space-y-2 text-xs text-slate-700">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span><strong>Pre-Lodged CDS Entries:</strong> Clearances processed while cargo is still on water.</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span><strong>Postponed VAT Accounting (PVA):</strong> UK buyers defer paying 20% import VAT upfront.</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span><strong>Duty Deferment Handling:</strong> Instant customs release without port demurrage penalties.</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-base font-black text-slate-900 flex items-center">
                <i class="fa-solid fa-shield-halved text-emerald-600 mr-2"></i> Customs Documentation Handled
            </h3>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <strong class="text-slate-900 block mb-0.5">Commercial Invoice</strong>
                    <span class="text-slate-500">Incoterms CIF/FOB validated</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <strong class="text-slate-900 block mb-0.5">Detailed Packing List</strong>
                    <span class="text-slate-500">Gross/Net Weights & Dimensions</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <strong class="text-slate-900 block mb-0.5">Certificate of Origin</strong>
                    <span class="text-slate-500">GSP / FTA preferential tariffs</span>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <strong class="text-slate-900 block mb-0.5">C88 / CDS Entry</strong>
                    <span class="text-slate-500">Official HMRC clearance note</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
