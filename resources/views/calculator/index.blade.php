@extends('layouts.app')

@section('title', 'UK Import Duty & VAT Landed Cost Calculator — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">UK Import Duty Calculator</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">UK Import Duty & VAT Landed Cost Calculator</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">
            Accurately calculate your total landed cost into the United Kingdom before placing purchase orders from India, China, and Europe.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left 7 Cols: Calculator Input Form -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-7 shadow-sm">
            <h2 class="text-base font-black text-slate-900 mb-4 flex items-center">
                <i class="fa-solid fa-calculator text-brand-700 mr-2"></i> Shipment & Commodity Details
            </h2>

            <form action="{{ route('calculator.calculate') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Product Value & Currency -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Invoice / Commodity Value *</label>
                        <input type="number" step="0.01" name="product_value" value="{{ old('product_value', $result['input']['product_value'] ?? request('product_value', 10000)) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Invoice Currency *</label>
                        <select name="currency" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-brand-500 focus:outline-none bg-white">
                            @foreach($currencies as $curr => $data)
                                <option value="{{ $curr }}" {{ (old('currency', $result['currency'] ?? 'GBP') == $curr) ? 'selected' : '' }}>
                                    {{ $curr }} ({{ $data['symbol'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Country of Origin & HS Code -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country of Origin / Export *</label>
                        <select name="origin_country" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none bg-white">
                            <option value="India" {{ (old('origin_country', $result['input']['origin_country'] ?? request('origin_country')) == 'India') ? 'selected' : '' }}>🇮🇳 India</option>
                            <option value="China" {{ (old('origin_country', $result['input']['origin_country'] ?? request('origin_country')) == 'China') ? 'selected' : '' }}>🇨🇳 China</option>
                            <option value="Germany" {{ (old('origin_country', $result['input']['origin_country'] ?? request('origin_country')) == 'Germany') ? 'selected' : '' }}>🇩🇪 Germany</option>
                            <option value="United States" {{ (old('origin_country', $result['input']['origin_country'] ?? request('origin_country')) == 'United States') ? 'selected' : '' }}>🇺🇸 United States</option>
                            <option value="Japan" {{ (old('origin_country', $result['input']['origin_country'] ?? request('origin_country')) == 'Japan') ? 'selected' : '' }}>🇯🇵 Japan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Select Industrial HS Code Tariff</label>
                        <select name="hs_code" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none bg-white">
                            <option value="">Custom Duty Rate</option>
                            @foreach($duties as $duty)
                                <option value="{{ $duty->hs_code }}" {{ (old('hs_code', $result['input']['hs_code'] ?? request('hs_code')) == $duty->hs_code) ? 'selected' : '' }}>
                                    {{ $duty->hs_code }} — {{ $duty->category_name }} ({{ $duty->duty_rate_percent }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Custom Duty Override & Weight -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Custom Duty Rate (%) [Optional Override]</label>
                        <input type="number" step="0.1" name="custom_duty_rate" value="{{ old('custom_duty_rate', $result['duty_rate_percent'] ?? '') }}" placeholder="Default 2.5%" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Gross Shipment Weight (KG)</label>
                        <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $result['input']['weight_kg'] ?? request('weight_kg', 150)) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <!-- Shipping Method -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">International Freight Method *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-semibold">
                        <label class="p-3 border border-slate-200 rounded-xl flex flex-col items-center text-center cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="shipping_method" value="sea_lcl" checked class="text-brand-700 mb-1">
                            <span>Sea Freight LCL</span>
                            <span class="text-[10px] text-slate-400">Consolidated</span>
                        </label>
                        <label class="p-3 border border-slate-200 rounded-xl flex flex-col items-center text-center cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="shipping_method" value="sea_fcl" class="text-brand-700 mb-1">
                            <span>Sea Freight FCL</span>
                            <span class="text-[10px] text-slate-400">20ft / 40ft Container</span>
                        </label>
                        <label class="p-3 border border-slate-200 rounded-xl flex flex-col items-center text-center cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="shipping_method" value="air_freight" class="text-brand-700 mb-1">
                            <span>Air Cargo</span>
                            <span class="text-[10px] text-slate-400">Heathrow Airport</span>
                        </label>
                        <label class="p-3 border border-slate-200 rounded-xl flex flex-col items-center text-center cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="shipping_method" value="dhl_express" class="text-brand-700 mb-1">
                            <span>DHL Express</span>
                            <span class="text-[10px] text-slate-400">Courier 3-5 Days</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-black rounded-xl text-sm transition shadow-md shadow-brand-700/20 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-calculator"></i>
                    <span>Calculate Landed Cost</span>
                </button>
            </form>
        </div>

        <!-- Right 5 Cols: Calculation Results -->
        <div class="lg:col-span-5 space-y-6">
            @if(isset($result))
                <div class="bg-white rounded-3xl border-2 border-brand-500 p-6 shadow-md space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-brand-700 uppercase tracking-wider block">Estimated Landed Cost</span>
                            <h3 class="text-lg font-black text-slate-900">Total UK Landed Cost</h3>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-800 border border-brand-200">
                            {{ $result['currency'] }}
                        </span>
                    </div>

                    <!-- Big Landed Cost Number -->
                    <div class="bg-gradient-to-r from-blue-50 to-brand-50/50 p-4 rounded-2xl border border-blue-200 text-center">
                        <span class="text-xs font-semibold text-slate-500 block mb-0.5">Final Landed UK Amount:</span>
                        <span class="text-3xl font-black text-brand-900">£{{ number_format($result['total_landed_cost_gbp'], 2) }}</span>
                        @if($result['currency'] !== 'GBP')
                            <span class="text-xs font-bold text-slate-600 block mt-1">
                                (&approx; {{ $result['currency'] }} {{ number_format($result['landed_cost_original_currency'], 2) }})
                            </span>
                        @endif
                    </div>

                    <!-- Itemized Breakdown -->
                    <div class="space-y-2 text-xs divide-y divide-slate-100">
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">Product / FOB Value:</span>
                            <strong class="text-slate-900">£{{ number_format($result['product_value_gbp'], 2) }}</strong>
                        </div>
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">International Freight:</span>
                            <strong class="text-slate-900">£{{ number_format($result['shipping_gbp'], 2) }}</strong>
                        </div>
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">Marine Insurance:</span>
                            <strong class="text-slate-900">£{{ number_format($result['insurance_gbp'], 2) }}</strong>
                        </div>
                        <div class="flex justify-between pt-1.5 font-bold text-slate-800 bg-slate-50 px-2 py-1 rounded">
                            <span>CIF Value (Basis for Duty):</span>
                            <span>£{{ number_format($result['cif_value_gbp'], 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">UK Customs Duty ({{ $result['duty_rate_percent'] }}%):</span>
                            <strong class="text-amber-700">£{{ number_format($result['customs_duty_gbp'], 2) }}</strong>
                        </div>
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">UK Import VAT (20%):</span>
                            <strong class="text-brand-700">£{{ number_format($result['import_vat_gbp'], 2) }}</strong>
                        </div>
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">Customs Clearance (Junaid & Co.):</span>
                            <strong class="text-slate-900">£{{ number_format($result['clearance_fee_gbp'], 2) }}</strong>
                        </div>
                        <div class="flex justify-between pt-1.5">
                            <span class="text-slate-600">Port Handling & Documentation:</span>
                            <strong class="text-slate-900">£{{ number_format($result['port_handling_gbp'], 2) }}</strong>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200 text-center">
                        <span class="text-xs text-slate-500 block mb-3">Total HMRC Duties & Taxes Payable: <strong class="text-slate-900">£{{ number_format($result['total_customs_payable_gbp'], 2) }}</strong></span>
                        <a href="{{ route('rfqs.create') }}" class="inline-block w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">
                            Post RFQ with This Target Cost
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center space-y-3">
                    <div class="w-12 h-12 bg-blue-50 text-brand-700 rounded-2xl flex items-center justify-center text-xl mx-auto">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Instant Landed Breakdown</h3>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto">
                        Enter your product price, origin country, and shipping method on the left to see exact UK import duties, 20% VAT, and port handling charges.
                    </p>
                </div>
            @endif

            <!-- Informational Notes -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-600 space-y-2">
                <strong class="text-slate-800 block">UK Import Regulation Notes:</strong>
                <p>• UK VAT (20%) is calculated on CIF Value + Applicable Customs Duty.</p>
                <p>• VAT registered UK businesses can account for import VAT on their periodic VAT return via Postponed VAT Accounting (PVA).</p>
                <p>• Customs clearance is facilitated through Junaid & Co. on the HMRC CDS (Customs Declaration Service) platform.</p>
            </div>
        </div>

    </div>
</div>
@endsection
