@extends('layouts.admin')

@section('title', 'Manage Shipments & Consignments — Perlcon Admin')
@section('header_title', 'International Logistics & Freight Tracking')
@section('header_subtitle', 'Monitor multimodal transport: DHL/FedEx small parcels, LCL ocean freight, and FCL containers')

@section('content')
<div class="space-y-6" x-data="{ updateModal: false, activeShipmentId: '', activeTracking: '', currentStatus: '', currentLocation: '', currentNote: '' }">

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="text-sm font-black text-slate-900">Active Freight Consignments</h2>
                <p class="text-xs text-slate-500">Live shipping status updates and milestone logging</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Tracking Number</th>
                        <th class="p-4">Freight Carrier & Mode</th>
                        <th class="p-4">Route & Transit</th>
                        <th class="p-4">Associated Order</th>
                        <th class="p-4">Current Status</th>
                        <th class="p-4">Last Location</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($shipments as $shipment)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- Tracking # -->
                            <td class="p-4 font-mono font-bold text-slate-900">
                                <span class="text-brand-800">#{{ $shipment->tracking_number }}</span>
                                <span class="text-[10px] text-slate-400 block font-sans">Ref: {{ $shipment->shipment_reference }}</span>
                            </td>

                            <!-- Carrier & Mode -->
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $shipment->carrier_name }}</span>
                                <span class="text-[10px] text-slate-500 uppercase">{{ ucwords(str_replace('_', ' ', $shipment->shipping_method)) }}</span>
                            </td>

                            <!-- Route -->
                            <td class="p-4">
                                <div class="flex items-center space-x-1.5 font-bold text-slate-800">
                                    <span>{{ $shipment->origin_country }}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                    <span>{{ $shipment->destination_country }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400">Est. {{ $shipment->estimated_delivery_date ?? 'In Schedule' }}</span>
                            </td>

                            <!-- Order -->
                            <td class="p-4">
                                @if($shipment->order)
                                    <a href="{{ route('orders.show', $shipment->order->id) }}" class="font-bold text-brand-700 hover:underline">
                                        #{{ $shipment->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-slate-400">N/A</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                @php
                                    $statusClasses = [
                                        'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'in_transit' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'booked' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'dispatched' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    ][$shipment->status] ?? 'bg-amber-50 text-amber-700 border-amber-200';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusClasses }}">
                                    {{ ucwords(str_replace('_', ' ', $shipment->status)) }}
                                </span>
                            </td>

                            <!-- Location -->
                            <td class="p-4 text-slate-600 font-semibold">
                                {{ $shipment->current_location ?? 'Port of Origin' }}
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-right">
                                <button type="button" 
                                        @click="updateModal = true; 
                                                activeShipmentId = '{{ $shipment->id }}'; 
                                                activeTracking = '{{ $shipment->tracking_number }}'; 
                                                currentStatus = '{{ $shipment->status }}'; 
                                                currentLocation = '{{ addslashes($shipment->current_location ?? '') }}';"
                                        class="px-3 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs inline-flex items-center space-x-1 transition">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>Update Status</span>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-truck-fast text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No active shipments.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

    <!-- Update Shipment Modal -->
    <div x-show="updateModal" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200" 
             @click.away="updateModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black text-slate-900">Update Shipment: <span class="font-mono text-brand-700" x-text="'#' + activeTracking"></span></h3>
                <button @click="updateModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/shipments') }}/' + activeShipmentId + '/update'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Transit Status</label>
                    <select name="status" x-model="currentStatus" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="booked">Booked with Freight Forwarder</option>
                        <option value="dispatched">Dispatched from Factory</option>
                        <option value="in_transit">In Ocean / Air Transit</option>
                        <option value="port_arrival">Arrived at UK Port of Entry</option>
                        <option value="customs_cleared">Customs Cleared by Junaid & Co.</option>
                        <option value="out_for_delivery">Out for Final Delivery</option>
                        <option value="delivered">Delivered to Buyer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Current Geo Location</label>
                    <input type="text" name="current_location" x-model="currentLocation" placeholder="e.g. Felixstowe Port / English Channel" 
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Milestone Log Note</label>
                    <textarea name="status_note" rows="2" placeholder="e.g. Vessel cleared customs; container loaded onto rail freight..." 
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="updateModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Record Update
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
