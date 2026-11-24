@extends('layouts.app')

@section('title', 'Edit ' . $product->name . ' — Supplier Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('supplier.dashboard') }}" class="hover:text-brand-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('supplier.products') }}" class="hover:text-brand-700">Products</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate">Edit {{ $product->name }}</span>
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Product</h1>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <form action="{{ route('supplier.products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Product Title *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @foreach($cat->subcategories as $sub)
                                <option value="{{ $sub->id }}" {{ $product->category_id == $sub->id ? 'selected' : '' }}>&nbsp;&nbsp;-- {{ $sub->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">HS Code *</label>
                    <input type="text" name="hs_code" value="{{ old('hs_code', $product->hs_code) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Base Price (£) *</label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Currency</label>
                    <input type="text" name="currency" value="{{ $product->currency }}" readonly class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">MOQ *</label>
                    <input type="number" name="moq" value="{{ old('moq', $product->moq) }}" min="1" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit *</label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Weight (kg) *</label>
                    <input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $product->weight_kg) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Lead Time (Days) *</label>
                    <input type="number" name="lead_time_days" value="{{ old('lead_time_days', $product->lead_time_days) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country of Origin *</label>
                    <input type="text" name="origin_country" value="{{ old('origin_country', $product->origin_country) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Production Capacity</label>
                    <input type="text" name="manufacturing_capacity" value="{{ old('manufacturing_capacity', $product->manufacturing_capacity) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Product Main Image URL *</label>
                <input type="url" name="main_image" value="{{ old('main_image', $product->main_image) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Short Description *</label>
                <textarea name="short_description" rows="2" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Technical Specifications Description</label>
                <textarea name="full_description" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('full_description', $product->full_description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Listing Status</label>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold bg-white">
                        <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                        <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="out_of_stock" {{ $product->status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2 pt-6">
                    <input type="checkbox" name="direct_purchase_enabled" value="1" {{ $product->direct_purchase_enabled ? 'checked' : '' }} class="w-4 h-4 text-brand-700 rounded border-slate-300">
                    <span class="text-xs font-semibold text-slate-700">Direct Purchase (Cart)</span>
                </div>
                <div class="flex items-center space-x-2 pt-6">
                    <input type="checkbox" name="price_on_request" value="1" {{ $product->price_on_request ? 'checked' : '' }} class="w-4 h-4 text-brand-700 rounded border-slate-300">
                    <span class="text-xs font-semibold text-slate-700">Price on Request</span>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-sm transition shadow-md shadow-brand-700/20">
                Update Product Changes
            </button>
        </form>
    </div>
</div>
@endsection
