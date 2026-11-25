@extends('layouts.supplier')

@section('title', 'Add New Product — Supplier Portal')
@section('header_title', 'Create Product Listing')
@section('header_subtitle', 'Publish new machinery or equipment with tariff HS code, lead times, and MOQ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('supplier.products') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Products</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('supplier.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Product Title & Category -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Product Identification</h3>
                <p class="text-xs text-slate-500 mb-4">Official machinery nomenclature and classification</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Product Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               placeholder="e.g. ASME B16.5 Forged SS316L Flanges"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>📁 {{ $cat->name }}</option>
                                @foreach($cat->subcategories as $sub)
                                    <option value="{{ $sub->id }}" {{ old('category_id') == $sub->id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">SKU / Model Number</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" 
                               placeholder="e.g. FLG-SS316-PN40"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country of Origin <span class="text-rose-500">*</span></label>
                        <input type="text" name="origin_country" value="{{ old('origin_country', auth()->user()->country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Pricing, Tariffs, & Logistics -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Commercial Terms & Specifications</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">HS Code (UK Customs Tariff) <span class="text-rose-500">*</span></label>
                        <input type="text" name="hs_code" value="{{ old('hs_code', '731815') }}" required 
                               placeholder="e.g. 731815, 848180"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Base FOB Unit Price <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="base_price" value="{{ old('base_price', '50.00') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Currency <span class="text-rose-500">*</span></label>
                        <select name="currency" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="GBP">GBP (£)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Minimum Order Qty (MOQ) <span class="text-rose-500">*</span></label>
                        <input type="number" name="moq" value="{{ old('moq', 10) }}" required min="1" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unit of Measurement <span class="text-rose-500">*</span></label>
                        <input type="text" name="unit" value="{{ old('unit', 'Pieces') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lead Time (Days) <span class="text-rose-500">*</span></label>
                        <input type="number" name="lead_time_days" value="{{ old('lead_time_days', 14) }}" required min="1" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Approx Weight per Unit (KG) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', 5.0) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Manufacturing Capacity</label>
                        <input type="text" name="manufacturing_capacity" value="{{ old('manufacturing_capacity', '10,000 Pcs/Month') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catalog Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="active">Active / Available</option>
                            <option value="draft">Draft / Hidden</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Product Image (Choose File)</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4 p-4 border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl bg-slate-50 transition">
                            <div class="relative w-28 h-28 rounded-xl bg-white border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-sm">
                                <img id="imagePreview" src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&h=600&fit=crop" alt="Preview" class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1 text-center sm:text-left space-y-2">
                                <div>
                                    <label for="imageUpload" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer shadow-sm transition">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Choose Image</span>
                                    </label>
                                    <input type="file" id="imageUpload" name="image" accept="image/*" class="hidden" onchange="previewProductImage(this)">
                                    <span id="fileNameDisplay" class="block sm:inline sm:ml-3 text-xs text-slate-500 mt-2 sm:mt-0 font-medium">No file chosen (JPG, PNG, WEBP, GIF up to 10MB)</span>
                                </div>
                                <p class="text-[11px] text-slate-400">Select an image file from your device. Image will be uploaded directly to the server.</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 3: Descriptions -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Descriptions & Purchasing Controls</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Short Description <span class="text-rose-500">*</span></label>
                        <input type="text" name="short_description" value="{{ old('short_description') }}" required 
                               placeholder="Brief one-line summary for product card..."
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Technical Parameters / Standards</label>
                        <textarea name="full_description" rows="3" 
                                  placeholder="Testing standards (EN 10204 3.1 certs, hydro-testing, surface finish)..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('full_description') }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-6 pt-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="direct_purchase_enabled" value="1" {{ old('direct_purchase_enabled', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-700">Allow Direct "Add to Cart" Purchase</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('supplier.products') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Publish Listing</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function previewProductImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const imagePreview = document.getElementById('imagePreview');
        if (fileNameDisplay) {
            fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            fileNameDisplay.classList.remove('text-slate-500');
            fileNameDisplay.classList.add('text-emerald-600', 'font-bold');
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            if (imagePreview) {
                imagePreview.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
