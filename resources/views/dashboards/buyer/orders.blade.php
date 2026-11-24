@extends('layouts.buyer')

@section('title', 'My Orders — Buyer Portal')
@section('header_title', 'My Purchase Orders & Consignments')
@section('header_subtitle', 'Track 14-stage logistics progress, inspection reports, customs clearance, and delivery')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($orders->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-receipt text-3xl mb-3 text-slate-300 block"></i>
                <p class="text-sm font-semibold">No purchase orders placed yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-3 px-5 py-2 bg-sky-600 text-white font-bold rounded-xl text-xs">Browse Products</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($orders as $order)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center space-x-2.5">
                                <span class="font-mono text-xs font-bold text-sky-800">#{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                <span class="text-xs text-slate-400">Placed on {{ $order->created_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                Total Landed Cost: £{{ number_format($order->total_amount, 2) }}
                            </h3>

                            <div class="text-xs text-slate-600 font-medium">
                                {{ count($order->orderItems) }} Item(s) across suppliers • Delivery: {{ $order->destination_country ?? 'UK' }}
                            </div>

                            <div class="text-xs text-slate-500">
                                Current Status: <strong class="text-slate-800 uppercase">{{ ucwords(str_replace('_', ' ', $order->order_status)) }}</strong>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 flex-shrink-0">
                            <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                                <i class="fa-solid fa-route"></i>
                                <span>Track 14-Stage Consignment &rarr;</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($orders->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
