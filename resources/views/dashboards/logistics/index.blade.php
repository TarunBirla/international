@extends('layouts.app')

@section('title', 'Logistics Partner Portal — Global Freight')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider block">Logistics & Multimodal Freight Portal</span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Global Freight & Intermodal Logistics Ltd</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Manage international ocean container LCL/FCL, air cargo shipments, and UK consignments.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Total Booked Shipments</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['total_shipments'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">In Transit</span>
            <span class="text-2xl font-black text-blue-700">{{ $stats['in_transit'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Delivered to UK Sites</span>
            <span class="text-2xl font-black text-emerald-700">{{ $stats['delivered'] }}</span>
        </div>
    </div>

    <!-- Active Shipments Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-black text-slate-900 mb-4">Active Freight Shipments</h2>

        <div class="space-y-4">
            @foreach($shipments as $shp)
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-brand-700">{{ $shp->shipment_number }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-brand-800">
                                {{ ucfirst(str_replace('_', ' ', $shp->status)) }}
                            </span>
                            <span class="text-xs text-slate-400">Order: #{{ $shp->order->order_number ?? 'ORD' }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">{{ $shp->carrier_name }} • {{ strtoupper($shp->shipment_type) }}</h3>
                        <div class="text-xs text-slate-500">
                            Route: <strong>{{ $shp->origin_country }}</strong> &rarr; <strong>{{ $shp->destination_country }} ({{ $shp->destination_port_city ?? 'Felixstowe' }})</strong>
                        </div>
                        <div class="text-xs text-slate-500">
                            Weight: {{ number_format($shp->total_weight_kg, 1) }} kg • Current Location: <strong class="text-slate-800">{{ $shp->current_location ?? 'Port Depot' }}</strong>
                        </div>
                    </div>

                    <!-- Update Location & Carrier Status Form -->
                    <form action="{{ route('logistics.shipments.update', $shp->id) }}" method="POST" class="flex flex-wrap items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200">
                        @csrf
                        <input type="text" name="carrier_name" value="{{ $shp->carrier_name }}" placeholder="Carrier" required class="w-32 px-2 py-1.5 rounded-lg border border-slate-200 text-xs">
                        <input type="text" name="current_location" value="{{ $shp->current_location }}" placeholder="Current Location" required class="w-48 px-2 py-1.5 rounded-lg border border-slate-200 text-xs">
                        <select name="status" class="px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                            <option value="booked" {{ $shp->status === 'booked' ? 'selected' : '' }}>Booked</option>
                            <option value="dispatched" {{ $shp->status === 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                            <option value="in_transit" {{ $shp->status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="customs_hold" {{ $shp->status === 'customs_hold' ? 'selected' : '' }}>Customs Hold</option>
                            <option value="customs_released" {{ $shp->status === 'customs_released' ? 'selected' : '' }}>Customs Released</option>
                            <option value="out_for_delivery" {{ $shp->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ $shp->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-lg text-xs transition">
                            Save Status
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
