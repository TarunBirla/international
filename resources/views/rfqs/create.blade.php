@extends('layouts.app')

@section('title', 'Post Request for Quotation (RFQ) — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('rfqs.index') }}" class="hover:text-brand-700">RFQs</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Post RFQ</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Create Request for Quotation</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Submit your engineering and procurement requirements to receive verified quotations from manufacturers in India, China and Europe.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <form action="{{ route('rfqs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Product Name & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Product Name / Component Required *</label>
                    <input type="text" name="product_name" value="{{ old('product_name', request('product')) }}" required placeholder="e.g. 5-Axis CNC Milling Center or ASME B16.5 Forged Flanges" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', request('category')) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Quantity & Target Price -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Quantity Required *</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 100) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Unit *</label>
                    <select name="unit" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="Pieces">Pieces</option>
                        <option value="Units">Units</option>
                        <option value="Sets">Sets</option>
                        <option value="Metric Tons">Metric Tons</option>
                        <option value="Kilograms">Kilograms</option>
                        <option value="Meters">Meters</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Target Budget Price (Per Unit)</label>
                    <div class="flex items-center space-x-2">
                        <select name="currency" class="px-3 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none bg-slate-50 font-bold">
                            <option value="GBP">GBP (£)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                        <input type="number" step="0.01" name="target_price" value="{{ old('target_price') }}" placeholder="Optional" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Preferred Countries Checkboxes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Preferred Supplier Countries</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-semibold text-slate-700">
                    <label class="flex items-center space-x-2 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer">
                        <input type="checkbox" name="preferred_countries[]" value="India" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇮🇳 India</span>
                    </label>
                    <label class="flex items-center space-x-2 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer">
                        <input type="checkbox" name="preferred_countries[]" value="China" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇨🇳 China</span>
                    </label>
                    <label class="flex items-center space-x-2 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer">
                        <input type="checkbox" name="preferred_countries[]" value="Germany" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇩🇪 Germany</span>
                    </label>
                    <label class="flex items-center space-x-2 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer">
                        <input type="checkbox" name="preferred_countries[]" value="United Kingdom" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇬🇧 United Kingdom</span>
                    </label>
                </div>
            </div>

            <!-- Technical Specifications -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Technical Specifications & Requirements *</label>
                <textarea name="technical_specifications" rows="4" required placeholder="Detail specific standards (e.g., ASME, DIN, ISO), material grades (SS316, ASTM A105), dimensional tolerances, voltage, pressure ratings, test certifications required (e.g., 3.1 MTR)..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
            </div>

            <!-- Destination Port & Delivery Date -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Destination Country *</label>
                    <input type="text" name="destination_country" value="United Kingdom" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Destination Port / City</label>
                    <input type="text" name="destination_port_city" value="Port of Felixstowe / London Gateway" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Required Delivery Date</label>
                    <input type="date" name="required_delivery_date" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Additional Commercial Terms / Inspection Requirements</label>
                <textarea name="additional_requirements" rows="2" placeholder="e.g. Third-party SGS inspection, letter of credit payment terms, seaworthy packaging..." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-brand-700 hover:bg-brand-800 text-white font-extrabold rounded-xl text-base transition shadow-lg shadow-brand-700/25 flex items-center justify-center space-x-2">
                <i class="fa-solid fa-bolt"></i>
                <span>Publish RFQ & Notify Eligible Manufacturers</span>
            </button>
        </form>
    </div>
</div>
@endsection
