@extends('layouts.app')

@section('title', 'Compare Quotations — ' . $rfq->product_name)

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('rfqs.index') }}" class="hover:text-brand-700">RFQs</a>
            <span>/</span>
            <a href="{{ route('rfqs.show', $rfq->id) }}" class="hover:text-brand-700">{{ $rfq->rfq_number }}</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Compare Quotations</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Compare Quotations Side-by-Side</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Comparing {{ count($rfq->quotations) }} binding quotes submitted for <strong class="text-slate-900">{{ $rfq->product_name }}</strong> ({{ number_format($rfq->quantity) }} {{ $rfq->unit }}).</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-x-auto">
        <table class="w-full text-xs text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <th class="p-5 font-bold w-48">Quotation Metric</th>
                    @foreach($rfq->quotations as $q)
                        <th class="p-5 font-extrabold text-slate-900 border-l border-slate-200 text-center {{ $q->status === 'accepted' ? 'bg-emerald-50 text-emerald-950' : '' }}">
                            <div class="font-mono text-[11px] text-brand-700">{{ $q->quotation_number }}</div>
                            <div class="text-sm font-black">{{ $q->supplier->company->company_name ?? $q->supplier->name }}</div>
                            <div class="text-[11px] font-normal text-slate-500">{{ $q->supplier->country }} ({{ $q->supplier->city }})</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <!-- Unit Price -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">Unit Price</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center font-black text-slate-900 border-l border-slate-100 text-sm">
                            £{{ number_format($q->unit_price, 2) }}
                        </td>
                    @endforeach
                </tr>

                <!-- Subtotal Amount -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">Subtotal ({{ $rfq->quantity }} {{ $rfq->unit }})</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center font-bold text-slate-800 border-l border-slate-100">
                            £{{ number_format($q->subtotal_amount, 2) }}
                        </td>
                    @endforeach
                </tr>

                <!-- Shipping Freight -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">Freight & Shipping</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center text-slate-800 border-l border-slate-100">
                            <span class="font-bold">£{{ number_format($q->shipping_cost, 2) }}</span>
                            <span class="block text-[10px] text-slate-500 mt-0.5">{{ $q->shipping_method }}</span>
                        </td>
                    @endforeach
                </tr>

                <!-- Estimated Duty & VAT -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">UK Import Duty & VAT (Est.)</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center text-slate-800 border-l border-slate-100">
                            <span>Duty: £{{ number_format($q->estimated_customs_duty, 2) }}</span>
                            <span class="block text-[11px] text-slate-500">VAT: £{{ number_format($q->estimated_vat, 2) }}</span>
                        </td>
                    @endforeach
                </tr>

                <!-- Total Landed Cost -->
                <tr class="bg-blue-50/50">
                    <td class="p-5 font-black text-slate-900 bg-slate-100 text-sm">Total Landed UK Cost</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center font-black text-lg text-brand-700 border-l border-slate-200">
                            £{{ number_format($q->total_landed_cost, 2) }}
                        </td>
                    @endforeach
                </tr>

                <!-- Estimated Delivery Days -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">Estimated Transit Time</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center text-slate-800 border-l border-slate-100 font-semibold">
                            {{ $q->estimated_delivery_days }} Days
                        </td>
                    @endforeach
                </tr>

                <!-- Payment Terms -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">Payment Terms</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center text-slate-600 border-l border-slate-100 text-xs">
                            {{ $q->payment_terms ?? 'Standard Trade Assurance' }}
                        </td>
                    @endforeach
                </tr>

                <!-- Notes / Inclusions -->
                <tr>
                    <td class="p-5 font-bold text-slate-700 bg-slate-50">Supplier Notes & Inspection</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center text-slate-600 border-l border-slate-100 text-xs">
                            {{ $q->notes ?? 'Full compliance with RFQ specifications' }}
                        </td>
                    @endforeach
                </tr>

                <!-- Action Row -->
                <tr class="bg-slate-50">
                    <td class="p-5 font-bold text-slate-700 bg-slate-100">Accept Quotation</td>
                    @foreach($rfq->quotations as $q)
                        <td class="p-5 text-center border-l border-slate-200">
                            @if($q->status === 'accepted')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-800 font-bold rounded-xl text-xs inline-block">
                                    <i class="fa-solid fa-check mr-1"></i> Accepted & Ordered
                                </span>
                            @elseif($rfq->status === 'awarded')
                                <span class="text-xs text-slate-400 font-semibold">Another quote was selected</span>
                            @else
                                <form action="{{ route('quotations.accept', $q->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Accept this quotation from {{ $q->supplier->company->company_name ?? $q->supplier->name }}?')" class="w-full px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                        Accept & Create Purchase Order
                                    </button>
                                </form>
                            @endif
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
