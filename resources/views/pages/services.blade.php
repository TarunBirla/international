@extends('layouts.app')

@section('title', 'Procurement & Engineering Services — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Industrial Procurement & Sourcing Services</h1>
        <p class="text-slate-600 text-sm mt-2 max-w-2xl">
            Complete turnkey international sourcing, quality assurance, multi-supplier order consolidation, and UK site delivery.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-industry"></i>
            </div>
            <h3 class="text-base font-black text-slate-900">Factory Audit & Quality Inspection</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                On-site metallurgical testing, ultrasonic NDT, dimensional tolerance CMM audits, and verification of EN 10204 3.1 mill test certificates.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-code-compare"></i>
            </div>
            <h3 class="text-base font-black text-slate-900">RFQ Tender Management</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Broadcast your bill of materials (BOM) to verified manufacturers in India, China, and Germany to secure competitive wholesale quotes.
            </p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 class="text-base font-black text-slate-900">Customs & Landed Delivery</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Integrated HMRC CDS customs clearances through Junaid & Co. at the Port of Felixstowe and direct delivery to your UK address.
            </p>
        </div>
    </div>
</div>
@endsection
