@extends('layouts.logistics')

@section('title', 'Logistics Freight Portal — Perlcon International')
@section('header_title', 'Global Freight & Intermodal Logistics')
@section('header_subtitle', 'Update multimodal container freight status, carrier reference numbers, and live milestones')

@section('content')
<div class="space-y-8">

    <!-- KPI Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Total Booked Consignments</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['total_shipments'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">In Ocean / Air Transit</span>
            <span class="text-2xl font-black text-indigo-700">{{ $stats['in_transit'] }}</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold block mb-1">Delivered to UK Sites</span>
            <span class="text-2xl font-black text-emerald-700">{{ $stats['delivered'] }}</span>
        </div>
    </div>

    <!-- Active Shipments Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-sm font-black text-slate-900 mb-4">Active Multimodal Shipments</h2>

        <div class="space-y-4">
            @forelse($shipments as $shp)
                <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/70 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:border-slate-300 transition">
                    
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-indigo-800">#{{ $shp->shipment_number }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-indigo-700 border border-indigo-200">
                                {{ ucfirst(str_replace('_', ' ', $shp->status)) }}
                            </span>
                            <span class="text-xs text-slate-400">Order: #{{ $shp->order->order_number ?? 'ORD' }}</span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900">
                            {{ $shp->carrier_name }} • <span class="text-xs text-slate-500 font-bold uppercase">{{ $shp->shipment_type }}</span>
                        </h3>

                        <div class="text-xs text-slate-600 font-medium">
                            Route: <strong class="text-slate-900">{{ $shp->origin_country }}</strong> &rarr; <strong class="text-slate-900">{{ $shp->destination_country }} ({{ $shp->destination_port_city ?? 'Felixstowe' }})</strong>
                        </div>

                        <div class="text-xs text-slate-500">
                            Weight: {{ number_format($shp->total_weight_kg, 1) }} kg • Current Geo Location: <strong class="text-slate-800">{{ $shp->current_location ?? 'Port Depot' }}</strong>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <div class="w-full lg:w-auto bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex-shrink-0">
                        <form action="{{ route('logistics.shipments.update', $shp->id) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            @csrf
                            <input type="text" name="carrier_name" value="{{ $shp->carrier_name }}" placeholder="Carrier" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium w-28">
                            <input type="text" name="current_location" value="{{ $shp->current_location }}" placeholder="Current Location" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium w-40">
                            <select name="status" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-bold bg-white text-slate-800">
                                <option value="booked" {{ $shp->status == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="picked_up" {{ $shp->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                <option value="dispatched" {{ $shp->status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                <option value="in_transit" {{ $shp->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="customs_hold" {{ $shp->status == 'customs_hold' ? 'selected' : '' }}>Customs Hold</option>
                                <option value="customs_released" {{ $shp->status == 'customs_released' ? 'selected' : '' }}>Customs Released</option>
                                <option value="out_for_delivery" {{ $shp->status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="delivered" {{ $shp->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition shadow-sm whitespace-nowrap">
                                Update
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center">No active shipments in transit.</p>
            @endforelse
        </div>

        @if($shipments->hasPages())
            <div class="mt-4 pt-4 border-t border-slate-100">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
