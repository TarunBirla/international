@extends(auth()->check() ? auth()->user()->getDashboardLayout() : 'layouts.app')

@section('title', 'Order ' . $order->order_number . ' Tracking & Details — Mart')
@section('header_title', 'Order ' . $order->order_number)
@section('header_subtitle', 'End-to-End International Logistics, Stepper, and Customs Status')

@section('content')
@if(!auth()->check())
<div class="bg-slate-50 border-b border-slate-200 py-8 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('buyer.orders') }}" class="hover:text-brand-700">Orders</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold font-mono">{{ $order->order_number }}</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                    <span class="text-xs text-slate-500">Placed on {{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Order {{ $order->order_number }}</h1>
            </div>

            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center">
                    <i class="fa-solid fa-shield-check mr-1.5"></i> Trade Assurance Escrow Paid
                </span>
                <span class="text-xl font-black text-slate-900">£{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@else
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div class="flex items-center space-x-3">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.orders') : (auth()->user()->isSupplier() ? route('supplier.orders') : (auth()->user()->isCustoms() ? route('customs.orders') : (auth()->user()->isLogistics() ? route('logistics.dashboard') : route('buyer.orders')))) }}"
           class="p-2 text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition text-xs font-bold flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to {{ auth()->user()->isCustoms() ? 'Customs Orders' : (auth()->user()->isLogistics() ? 'Logistics' : 'Orders') }}</span>
        </a>
        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
            {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
        </span>
        <span class="text-xs text-slate-500">Placed {{ $order->created_at->format('d M Y') }}</span>
    </div>

    <div class="flex items-center space-x-3">
        <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center">
            <i class="fa-solid fa-shield-check mr-1.5"></i> Trade Assurance Escrow
        </span>
        <span class="text-xl font-black text-slate-900">£{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>
@endif

<div class="{{ auth()->check() ? 'space-y-8' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10' }}">
    
    <!-- 14-Stage Visual Order Tracking Stepper -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">End-to-End International Traceability</span>
                <h2 class="text-lg font-black text-slate-900">Shipment & Customs Tracking Timeline</h2>
            </div>
            <div class="text-xs font-mono font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                Consignment Tracking: {{ $order->tracking_number ?? 'PRL-UK-IN-TRANSIT' }}
            </div>
        </div>

        <!-- Horizontal Stepper Bar (Responsive Scrolling) -->
        <div class="overflow-x-auto pb-4">
            <div class="flex items-center justify-between min-w-[850px] relative">
                <!-- Background Progress Line -->
                <div class="absolute top-5 left-6 right-6 h-1 bg-slate-100 -z-0"></div>
                <div class="absolute top-5 left-6 h-1 bg-brand-600 -z-0 transition-all duration-500" style="width: {{ min(100, max(5, ($order->stage_index / 12) * 100)) }}%;"></div>

                @php $currentStageIndex = $order->stage_index; $idx = 1; @endphp
                @foreach($allStages as $stageKey => $s)
                    @php 
                        $isCompleted = $idx < $currentStageIndex; 
                        $isCurrent = $idx === $currentStageIndex; 
                    @endphp
                    <div class="flex flex-col items-center text-center relative z-10 w-24">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shadow-sm transition
                            {{ $isCompleted ? 'bg-emerald-600 text-white' : ($isCurrent ? 'bg-brand-600 text-white ring-4 ring-brand-100' : 'bg-white text-slate-400 border-2 border-slate-200') }}">
                            @if($isCompleted)
                                <i class="fa-solid fa-check text-xs"></i>
                            @else
                                <i class="fa-solid {{ $s['icon'] }} text-xs"></i>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold mt-2 leading-tight {{ $isCurrent ? 'text-brand-700' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    @php $idx++; @endphp
                @endforeach
            </div>
        </div>

        <!-- Live Status Updates Log -->
        <div class="mt-8 pt-6 border-t border-slate-100">
            <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-4">Milestone Checkpoints Log</h3>
            <div class="space-y-3">
                @foreach($order->trackingUpdates as $update)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex items-start space-x-3 text-xs">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-brand-700 font-bold flex items-center justify-center flex-shrink-0 mt-0.5 text-[10px]">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-0.5">
                                <strong class="text-slate-900 font-bold">{{ $update->title }}</strong>
                                <span class="text-slate-400 font-mono text-[11px]">{{ $update->occurred_at->format('d M Y, H:i') }}</span>
                            </div>
                            <p class="text-slate-600">{{ $update->description }}</p>
                            @if($update->location)
                                <span class="text-[10px] text-slate-400 block mt-1"><i class="fa-solid fa-map-pin mr-1"></i> {{ $update->location }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Admin / Partner Status Update Form -->
        @if(!Auth::user()->isBuyer())
            <div class="mt-6 pt-6 border-t border-slate-200 bg-slate-50 p-4 rounded-2xl">
                <h4 class="text-xs font-bold text-slate-800 mb-2 flex items-center">
                    <i class="fa-solid fa-pen-to-square text-brand-700 mr-1.5"></i> Update Tracking Milestones (Staff & Partners Only)
                </h4>
                <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    @csrf
                    <div>
                        <select name="stage" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-white">
                            @foreach($allStages as $k => $st)
                                <option value="{{ $k }}" {{ $order->order_status === $k ? 'selected' : '' }}>{{ $st['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="text" name="title" placeholder="Event headline (e.g. Customs Cleared)" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    </div>
                    <div>
                        <input type="text" name="location" placeholder="Location (e.g. Felixstowe Port)" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    </div>
                    <div>
                        <button type="submit" class="w-full py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                            Record Checkpoint
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Multi-Supplier Split Purchase Orders (PO) Breakdown -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider block">Multi-Supplier Architecture</span>
            <h2 class="text-xl font-black text-slate-900">Supplier Split Purchase Orders (PO)</h2>
            <p class="text-xs text-slate-500 mt-0.5">Each supplier manages production and dispatch for their respective split purchase order.</p>
        </div>

        <div class="space-y-4">
            @foreach($order->orderItems as $item)
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-brand-800">
                                {{ $item->purchase_order_number }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                Status: {{ ucfirst(str_replace('_', ' ', $item->supplier_status)) }}
                            </span>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">{{ $item->product_name }}</h3>
                        <div class="text-xs text-slate-600">
                            Supplier: <strong>{{ $item->supplier->company->company_name ?? $item->supplier->name }}</strong> ({{ $item->supplier->country }})
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ $item->quantity }} {{ $item->unit }} &times; £{{ number_format($item->unit_price, 2) }} • HS Code: {{ $item->hs_code ?? '8479.89.97' }}
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end">
                        <span class="text-xs text-slate-500">PO Subtotal</span>
                        <span class="text-lg font-black text-slate-900">£{{ number_format($item->subtotal, 2) }}</span>
                        @if($item->supplier_tracking_number)
                            <span class="text-[11px] font-mono font-bold text-slate-500 block mt-1">
                                Supplier Dispatched: {{ $item->supplier_tracking_number }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Logistics & Customs Partners 2-Col Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Logistics Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-base font-black text-slate-900 flex items-center">
                <i class="fa-solid fa-ship text-brand-700 mr-2"></i> International Freight & Logistics
            </h3>

            @if($order->shipments->isNotEmpty())
                @php $shipment = $order->shipments->first(); @endphp
                <div class="space-y-2 text-xs divide-y divide-slate-100">
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Carrier / Liner:</span>
                        <strong class="text-slate-900">{{ $shipment->carrier_name }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Shipment Type:</span>
                        <strong class="text-slate-900 uppercase">{{ str_replace('_', ' ', $shipment->shipment_type) }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Total Cargo Weight:</span>
                        <strong class="text-slate-900">{{ number_format($shipment->total_weight_kg, 1) }} kg</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Current Position:</span>
                        <strong class="text-slate-900">{{ $shipment->current_location ?? 'In International Transit' }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Est. Arrival Date:</span>
                        <strong class="text-slate-900">{{ $shipment->estimated_delivery_date ? $shipment->estimated_delivery_date->format('d M Y') : 'In 14 days' }}</strong>
                    </div>
                </div>
            @else
                <p class="text-xs text-slate-500">Shipment booking being allocated by Global Intermodal Freight.</p>
            @endif
        </div>

        <!-- Customs Clearance Card (Junaid & Co.) -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h3 class="text-base font-black text-slate-900 flex items-center">
                <i class="fa-solid fa-file-shield text-emerald-600 mr-2"></i> UK Customs Clearance (Junaid & Co.)
            </h3>

            @if($order->customsClearances->isNotEmpty())
                @php $customs = $order->customsClearances->first(); @endphp
                <div class="space-y-2 text-xs divide-y divide-slate-100">
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Customs Broker:</span>
                        <strong class="text-slate-900">Junaid & Co. Customs Clearance Specialists</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">HMRC CDS Entry Reference:</span>
                        <strong class="font-mono text-slate-900">{{ $customs->c88_entry_number ?? 'CDS-25-GB-PENDING' }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Port of Entry:</span>
                        <strong class="text-slate-900">{{ $customs->port_of_entry }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Customs Duty Payable:</span>
                        <strong class="text-slate-900">£{{ number_format($customs->duty_payable_amount, 2) }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Import VAT (20%):</span>
                        <strong class="text-slate-900">£{{ number_format($customs->vat_payable_amount, 2) }}</strong>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Declaration Status:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $customs->status)) }}
                        </span>
                    </div>
                </div>

                @if(auth()->check() && (auth()->user()->isCustoms() || auth()->user()->isAdmin()))
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wider">Customs Broker Actions</p>
                        <form action="{{ route('customs.clearances.update', $customs->id) }}" method="POST" class="space-y-2">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" name="c88_entry_number" value="{{ $customs->c88_entry_number }}" placeholder="HMRC C88 / CDS Entry Ref" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-mono font-bold w-full">
                                <select name="status" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-bold bg-white text-slate-800 w-full">
                                    <option value="declaration_filed" {{ $customs->status == 'declaration_filed' ? 'selected' : '' }}>Declaration Filed</option>
                                    <option value="under_review" {{ $customs->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="documents_required" {{ $customs->status == 'documents_required' ? 'selected' : '' }}>Docs Required</option>
                                    <option value="duty_paid" {{ $customs->status == 'duty_paid' ? 'selected' : '' }}>Duty Assessed</option>
                                    <option value="physical_inspection" {{ $customs->status == 'physical_inspection' ? 'selected' : '' }}>Inspection</option>
                                    <option value="cleared" {{ $customs->status == 'cleared' ? 'selected' : '' }}>Cleared (Green)</option>
                                    <option value="released" {{ $customs->status == 'released' ? 'selected' : '' }}>Released for Delivery</option>
                                </select>
                            </div>
                            <input type="text" name="partner_notes" value="{{ $customs->partner_notes }}" placeholder="Broker notes & customs audit status..." class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium w-full">
                            <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                Update C88 Clearance Status
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-xs text-slate-400 py-3 text-center">
                    Customs declaration pre-lodgement is being prepared.
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
