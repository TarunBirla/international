@extends('layouts.customs')

@section('title', 'Consignment Orders — Junaid & Co. Customs Clearance')
@section('header_title', 'All Import Consignment Orders')
@section('header_subtitle', 'Monitor all international shipments entering the United Kingdom for customs duty and VAT compliance')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-base font-black text-slate-900">United Kingdom Inbound Consignments</h2>
            <p class="text-xs text-slate-500">Every B2B order requiring HMRC Single Administrative Document (C88) pre-lodgement</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('customs.dashboard') }}" class="px-4 py-2 bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-stamp"></i>
                <span>C88 Declarations</span>
            </a>
            <a href="{{ route('calculator.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-calculator"></i>
                <span>Tariff Calculator</span>
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800">No consignment orders found</h3>
                <p class="text-xs text-slate-400 mt-1">Orders placed by buyers will automatically appear here for customs clearance.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $customs = $order->customsClearances->first();
                        $shipment = $order->shipments->first();
                    @endphp
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-slate-300 transition flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        
                        <!-- Left Details -->
                        <div class="space-y-2 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-900 bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                    {{ $order->order_number }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase 
                                    @if($order->order_status === 'delivered') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif(in_array($order->order_status, ['customs_cleared', 'arrived_in_uk'])) bg-sky-50 text-sky-700 border border-sky-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    Placed {{ $order->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600">
                                <div>Buyer: <strong class="text-slate-900">{{ $order->buyer->company->company_name ?? $order->buyer->name }}</strong> (UK)</div>
                                <div>•</div>
                                <div>Consignment Total: <strong class="text-slate-900">£{{ number_format($order->total_amount, 2) }}</strong></div>
                                <div>•</div>
                                <div>Items: <strong class="text-slate-900">{{ $order->orderItems->count() }} line items</strong></div>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 bg-white p-2.5 rounded-xl border border-slate-200/80">
                                <div class="flex items-center space-x-1.5">
                                    <i class="fa-solid fa-stamp text-amber-600"></i>
                                    <span>C88 Status:</span>
                                    <strong class="text-amber-800 uppercase">{{ $customs ? ucfirst(str_replace('_', ' ', $customs->status)) : 'Pre-lodging' }}</strong>
                                </div>
                                <div>•</div>
                                <div>Entry Ref: <strong class="font-mono text-slate-800">{{ $customs->c88_entry_number ?? 'PENDING' }}</strong></div>
                                <div>•</div>
                                <div>Assessed Duty: <strong class="text-slate-800">£{{ number_format($customs->duty_payable_amount ?? $order->customs_duty_amount, 2) }}</strong></div>
                                <div>•</div>
                                <div>Import VAT (20%): <strong class="text-slate-800">£{{ number_format($customs->vat_payable_amount ?? $order->vat_amount, 2) }}</strong></div>
                            </div>
                        </div>

                        <!-- Right Action Buttons -->
                        <div class="flex items-center space-x-2 flex-shrink-0 w-full sm:w-auto justify-end">
                            <a href="{{ route('orders.show', $order->id) }}" 
                               class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                                <i class="fa-solid fa-file-shield"></i>
                                <span>Audit & Track Order</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($orders->hasPages())
                <div class="mt-6 pt-4 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
