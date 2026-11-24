@extends('layouts.app')

@section('title', 'Customs Partner Portal — Junaid & Co.')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block">Customs Brokerage Partner Portal</span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Junaid & Co. Customs Clearance Specialists</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Authorised Economic Operator (AEO-F) • HMRC CDS Pre-Lodgement & C88 Entry Processing.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
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
            <span class="text-xs text-slate-500 font-semibold block mb-1">Customs Cleared</span>
            <span class="text-2xl font-black text-emerald-700">{{ $stats['total_cleared'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Duties & VAT Managed</span>
            <span class="text-2xl font-black text-brand-700">£{{ number_format($stats['total_duty_vat_collected'], 2) }}</span>
        </div>
    </div>

    <!-- Customs Declarations -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-black text-slate-900 mb-4">HMRC CDS Customs Declarations</h2>

        <div class="space-y-4">
            @foreach($clearances as $c)
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-slate-700">{{ $c->clearance_reference }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ in_array($c->status, ['cleared', 'released']) ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-brand-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                            </span>
                            <span class="text-xs text-slate-400">Port: {{ $c->port_of_entry }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">
                            C88 Entry: <span class="font-mono text-brand-700">{{ $c->c88_entry_number ?? 'Pending Entry Number' }}</span>
                        </h3>
                        <div class="text-xs text-slate-500">
                            Declared CIF: <strong>£{{ number_format($c->declared_cif_value, 2) }}</strong> • Duty: £{{ number_format($c->duty_payable_amount, 2) }} • VAT: £{{ number_format($c->vat_payable_amount, 2) }}
                        </div>
                        <div class="text-xs text-slate-500">
                            Commodity Codes: <span class="font-mono font-bold text-slate-700">{{ $c->hs_codes }}</span>
                        </div>
                    </div>

                    <!-- Update Customs Status & C88 Entry Form -->
                    <form action="{{ route('customs.clearances.update', $c->id) }}" method="POST" class="flex flex-wrap items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200">
                        @csrf
                        <input type="text" name="c88_entry_number" value="{{ $c->c88_entry_number }}" placeholder="CDS/C88 Entry #" required class="w-36 px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-mono font-bold">
                        <select name="status" class="px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                            <option value="declaration_filed" {{ $c->status === 'declaration_filed' ? 'selected' : '' }}>Declaration Filed</option>
                            <option value="under_review" {{ $c->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="documents_required" {{ $c->status === 'documents_required' ? 'selected' : '' }}>Docs Required</option>
                            <option value="duty_paid" {{ $c->status === 'duty_paid' ? 'selected' : '' }}>Duty Paid</option>
                            <option value="cleared" {{ $c->status === 'cleared' ? 'selected' : '' }}>Cleared (C88 Issued)</option>
                            <option value="released" {{ $c->status === 'released' ? 'selected' : '' }}>Released for Delivery</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition shadow-sm">
                            Update Entry
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
