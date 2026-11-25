@extends('layouts.buyer')

@section('title', 'Edit RFQ Tender — Mart')
@section('header_title', 'Edit RFQ Tender: #' . $rfq->rfq_number)
@section('header_subtitle', 'Update technical specifications, volume, target price or delivery requirements')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('buyer.rfqs') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to My RFQs</span>
        </a>

        <form action="{{ route('rfqs.destroy', $rfq->id) }}" method="POST" onsubmit="return confirm('Permanently delete RFQ #{{ $rfq->rfq_number }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center space-x-1.5 px-3 py-1.5 rounded-lg hover:bg-rose-50 transition">
                <i class="fa-solid fa-trash-can"></i>
                <span>Delete RFQ</span>
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('rfqs.update', $rfq->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Product & Quantity -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Requirement Overview</h3>
                <p class="text-xs text-slate-500 mb-4">Core equipment specifications and volume</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Product / Requirement Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="product_name" value="{{ old('product_name', $rfq->product_name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                        <select name="category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="">General Industrial</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $rfq->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Required Quantity <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity', $rfq->quantity) }}" required min="1" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unit of Measurement <span class="text-rose-500">*</span></label>
                        <input type="text" name="unit" value="{{ old('unit', $rfq->unit) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Unit Price</label>
                        <input type="number" step="0.01" name="target_price" value="{{ old('target_price', $rfq->target_price) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Currency <span class="text-rose-500">*</span></label>
                        <select name="currency" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="GBP" {{ old('currency', $rfq->currency) === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="USD" {{ old('currency', $rfq->currency) === 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $rfq->currency) === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Delivery Date</label>
                        <input type="date" name="required_delivery_date" value="{{ old('required_delivery_date', $rfq->required_delivery_date ? date('Y-m-d', strtotime($rfq->required_delivery_date)) : '') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Destination Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="destination_country" value="{{ old('destination_country', $rfq->destination_country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Technical Specifications -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Technical Parameters & Standards</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Technical Specifications <span class="text-rose-500">*</span></label>
                        <textarea name="technical_specifications" rows="4" required 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('technical_specifications', $rfq->technical_specifications) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Additional Requirements (Certifications, Testing, CAD format)</label>
                        <textarea name="additional_requirements" rows="2" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('additional_requirements', $rfq->additional_requirements) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('buyer.rfqs') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Changes</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
