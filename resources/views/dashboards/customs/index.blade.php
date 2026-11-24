@extends('layouts.customs')

@section('title', 'Customs Clearance — Junaid & Co.')
@section('header_title', 'Junaid & Co. Customs Declarations (C88)')
@section('header_subtitle', 'Audit Single Administrative Documents (C88), assess UK import duties, and issue green-channel release')

@section('content')
<div class="space-y-8">

    <!-- KPI Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Total Declarations</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['total_declarations'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Pending Clearance</span>
            <span class="text-2xl font-black text-amber-600">{{ $stats['pending_review'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Cleared / Released</span>
            <span class="text-2xl font-black text-emerald-700">{{ $stats['total_cleared'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Duty & VAT Assessed</span>
            <span class="text-2xl font-black text-slate-900">£{{ number_format($stats['total_duty_vat_collected'], 2) }}</span>
        </div>
    </div>

    <!-- Clearances Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-sm font-black text-slate-900 mb-4">C88 Import Customs Filings</h2>

        <div class="space-y-4">
            @forelse($clearances as $clr)
                <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/70 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:border-slate-300 transition">
                    
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Entry: {{ $clr->c88_entry_number ?? 'PENDING' }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-900">
                                {{ ucfirst(str_replace('_', ' ', $clr->status)) }}
                            </span>
                            <span class="text-xs text-slate-400">Order: #{{ $clr->order->order_number ?? 'ORD' }}</span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900">
                            Port: {{ $clr->port_of_entry ?? 'Port of Felixstowe / London Gateway' }}
                        </h3>

                        <div class="text-xs text-slate-600 font-medium">
                            Buyer: <strong class="text-slate-900">{{ $clr->order->buyer->company->company_name ?? $clr->order->buyer->name }}</strong>
                        </div>

                        <div class="text-xs text-slate-500 flex flex-wrap gap-x-3 gap-y-1">
                            <span>Assessed Duty: <strong class="text-slate-900">£{{ number_format($clr->duty_amount ?? 0, 2) }}</strong></span>
                            <span>•</span>
                            <span>Import VAT: <strong class="text-slate-900">£{{ number_format($clr->vat_amount ?? 0, 2) }}</strong></span>
                            <span>•</span>
                            <span>Total Charges: <strong class="text-slate-900">£{{ number_format($clr->total_customs_charges ?? 0, 2) }}</strong></span>
                        </div>

                        @if($clr->partner_notes)
                            <div class="text-xs text-amber-900 bg-amber-50/80 p-2.5 rounded-xl border border-amber-200/60 max-w-xl">
                                <strong>Broker Note:</strong> {{ $clr->partner_notes }}
                            </div>
                        @endif
                    </div>

                    <!-- Update Status Form -->
                    <div class="w-full lg:w-auto bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex-shrink-0">
                        <form action="{{ route('customs.clearances.update', $clr->id) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            @csrf
                            <input type="text" name="c88_entry_number" value="{{ $clr->c88_entry_number }}" placeholder="C88 Entry Ref" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-mono font-bold w-36">
                            <select name="status" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-bold bg-white text-slate-800">
                                <option value="declaration_filed" {{ $clr->status == 'declaration_filed' ? 'selected' : '' }}>Declaration Filed</option>
                                <option value="under_review" {{ $clr->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="documents_required" {{ $clr->status == 'documents_required' ? 'selected' : '' }}>Docs Required</option>
                                <option value="duty_paid" {{ $clr->status == 'duty_paid' ? 'selected' : '' }}>Duty Assessed</option>
                                <option value="physical_inspection" {{ $clr->status == 'physical_inspection' ? 'selected' : '' }}>Inspection</option>
                                <option value="cleared" {{ $clr->status == 'cleared' ? 'selected' : '' }}>Cleared (Green)</option>
                                <option value="released" {{ $clr->status == 'released' ? 'selected' : '' }}>Released for Delivery</option>
                            </select>
                            <input type="text" name="partner_notes" value="{{ $clr->partner_notes }}" placeholder="Broker Notes" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium w-40">
                            <button type="submit" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg text-xs transition shadow-sm whitespace-nowrap">
                                Update
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center">No customs filings submitted.</p>
            @endforelse
        </div>

        @if($clearances->hasPages())
            <div class="mt-4 pt-4 border-t border-slate-100">
                {{ $clearances->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
