@extends('layouts.app')

@section('title', 'International Logistics & Freight Services — Mart')

@section('content')
<div class="bg-white border-b border-slate-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Shipping & Logistics</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">International Logistics & UK Freight</h1>
        <p class="text-slate-600 text-sm mt-2 max-w-2xl">
            Reliable multimodal shipping tailored for heavy industrial machinery, precision CNC components, and batch manufactured spare parts.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    <!-- Freight Tiers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Tier 1 -->
        <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-plane-up"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900">Small Parcels & Air Express</h3>
            <span class="text-xs font-bold text-amber-600 uppercase block -mt-2">1 – 20 KG • 3 to 5 Days</span>
            <p class="text-xs text-slate-600 leading-relaxed">
                Fast courier delivery for urgent replacement parts, metrology tools, and engineering prototypes. Integrated with DHL Express, FedEx, and UPS.
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 list-disc pl-4 pt-2 border-t border-slate-100">
                <li>Direct factory pickup in India / China</li>
                <li>Door-to-door tracking number</li>
                <li>HMRC fast-track customs clearance</li>
            </ul>
        </div>

        <!-- Tier 2 -->
        <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900">Consolidated Ocean Freight (LCL)</h3>
            <span class="text-xs font-bold text-brand-700 uppercase block -mt-2">20 – 5,000 KG • 18 to 28 Days</span>
            <p class="text-xs text-slate-600 leading-relaxed">
                Cost-effective sea freight consolidation for valves, forged flanges, motors, and palletized machine components.
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 list-disc pl-4 pt-2 border-t border-slate-100">
                <li>Departures from Nhava Sheva & Shenzhen</li>
                <li>Arriving at Port of Felixstowe / London Gateway</li>
                <li>Export seaworthy ISPM-15 wooden crating</li>
            </ul>
        </div>

        <!-- Tier 3 -->
        <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-ship"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900">Large Machinery & Full Container (FCL)</h3>
            <span class="text-xs font-bold text-indigo-700 uppercase block -mt-2">20ft & 40ft Containers / Heavy Lift</span>
            <p class="text-xs text-slate-600 leading-relaxed">
                Dedicated FCL containers, flat racks, and open-top sea containers for 5-axis CNC machines, hydraulic presses, and entire factory production lines.
            </p>
            <ul class="text-xs text-slate-700 space-y-1.5 list-disc pl-4 pt-2 border-t border-slate-100">
                <li>Heavy lift port cranes and rigging</li>
                <li>Marine cargo insurance included</li>
                <li>UK wide low-loader lorry transport</li>
            </ul>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-slate-50 border border-slate-200 rounded-3xl p-8 text-center">
        <h3 class="text-xl font-bold text-slate-900 mb-2">Need a custom shipping quotation for your machinery?</h3>
        <p class="text-xs text-slate-600 mb-5 max-w-lg mx-auto">Use our UK Landed Cost Calculator or submit an RFQ with your target port of entry.</p>
        <a href="{{ route('calculator.index') }}" class="px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs shadow transition">
            Launch Duty & Freight Calculator
        </a>
    </div>
</div>
@endsection
