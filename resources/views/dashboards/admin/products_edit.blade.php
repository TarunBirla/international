@extends('layouts.admin')

@section('title', 'Edit Product — Mart Admin')
@section('header_title', 'Edit Product: ' . $product->name)
@section('header_subtitle', 'Update technical parameters, tariff classification, pricing, and stock status')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Products List</span>
        </a>

        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Permanently delete this product?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center space-x-1.5 px-3 py-1.5 rounded-lg hover:bg-rose-50 transition">
                <i class="fa-solid fa-trash-can"></i>
                <span>Delete Product</span>
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Classification -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Product Details & Classification</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Product Category <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }} class="font-bold text-slate-900">
                                    📁 {{ $category->name }}
                                </option>
                                @if($category->subcategories && $category->subcategories->count() > 0)
                                    @foreach($category->subcategories as $sub)
                                        <option value="{{ $sub->id }}" {{ old('category_id', $product->category_id) == $sub->id ? 'selected' : '' }} class="text-slate-600">
                                            &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Product Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">SKU / Part Number</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catalog Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active / In Stock</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive / Hidden</option>
                            <option value="out_of_stock" {{ old('status', $product->status) === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Pricing & Customs -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Commercial Terms & Tariffs</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">HS Code <span class="text-rose-500">*</span></label>
                        <input type="text" name="hs_code" value="{{ old('hs_code', $product->hs_code) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Base Price <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Currency <span class="text-rose-500">*</span></label>
                        <select name="currency" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="GBP" {{ old('currency', $product->currency) === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="USD" {{ old('currency', $product->currency) === 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $product->currency) === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">MOQ <span class="text-rose-500">*</span></label>
                        <input type="number" name="moq" value="{{ old('moq', $product->moq) }}" required min="1" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Unit <span class="text-rose-500">*</span></label>
                        <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country of Origin <span class="text-rose-500">*</span></label>
                        <input type="text" name="origin_country" value="{{ old('origin_country', $product->origin_country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Approx Weight (KG)</label>
                        <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $product->weight_kg) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lead Time (Days)</label>
                        <input type="number" name="lead_time_days" value="{{ old('lead_time_days', $product->lead_time_days) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Product Main Image (Choose File)</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4 p-4 border-2 border-dashed border-slate-300 hover:border-brand-500 rounded-2xl bg-slate-50 transition">
                            <div class="relative w-28 h-28 rounded-xl bg-white border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-sm">
                                <img id="imagePreview" src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                <span class="absolute bottom-1 right-1 bg-slate-900/70 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">Current</span>
                            </div>

                            <div class="flex-1 text-center sm:text-left space-y-2">
                                <div>
                                    <label for="imageUpload" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-brand-700 hover:bg-brand-800 text-white text-xs font-bold rounded-xl cursor-pointer shadow-sm transition">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Choose New Image</span>
                                    </label>
                                    <input type="file" id="imageUpload" name="image" accept="image/*" class="hidden" onchange="previewProductImage(this)">
                                    <span id="fileNameDisplay" class="block sm:inline sm:ml-3 text-xs text-slate-500 mt-2 sm:mt-0 font-medium">Select a new image to replace current (Max 10MB)</span>
                                </div>
                                <p class="text-[11px] text-slate-400">Supported formats: JPG, PNG, WEBP, GIF, SVG. Leave empty if you don't want to change the current image.</p>
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
                <h3 class="text-sm font-black text-slate-900 mb-1">Descriptions & Visibility</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Short Description <span class="text-rose-500">*</span></label>
                        <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Technical Description</label>
                        <textarea name="full_description" rows="4" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('full_description', $product->full_description) }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-6 pt-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                            <span class="text-xs font-bold text-slate-700">Feature on Homepage Showcase</span>
                        </label>

                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input type="checkbox" name="direct_purchase_enabled" value="1" {{ old('direct_purchase_enabled', $product->direct_purchase_enabled) ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-700">Enable Direct "Add to Cart" Purchase</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
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
            fileNameDisplay.classList.add('text-brand-600', 'font-bold');
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
