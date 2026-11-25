@extends('layouts.supplier')

@section('title', 'Plant Profile & Capabilities — Supplier Portal')
@section('header_title', 'Manufacturing Plant Profile & KYC')
@section('header_subtitle', 'Showcase factory tonnage, CNC machinery, ISO certifications, and export capabilities')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('supplier.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Company Logo / Image Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Company Logo / Factory Image</label>
                <div class="flex items-center space-x-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-sm">
                        <img id="supplierLogoPreview" 
                             src="{{ $user->company ? $user->company->logo_url : $user->avatar_url }}" 
                             alt="{{ $user->company->company_name ?? $user->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="logo" id="supplierLogoInput" accept="image/*" class="hidden" onchange="previewSupplierLogo(event)">
                        <button type="button" onclick="document.getElementById('supplierLogoInput').click()" class="px-4 py-2 bg-white border border-slate-300 hover:border-slate-400 text-slate-700 font-bold rounded-xl text-xs transition shadow-sm inline-flex items-center space-x-2">
                            <i class="fa-solid fa-cloud-arrow-up text-emerald-600"></i>
                            <span>Choose Logo Image</span>
                        </button>
                        <p class="text-[11px] text-slate-500 mt-1.5">PNG, JPG, WEBP, or SVG up to 4MB. Square ratio recommended.</p>
                    </div>
                </div>
            </div>

            <!-- Section 1: Legal Entity & Classification -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Company & Enterprise Registration</h3>
                <p class="text-xs text-slate-500 mb-4">Official registered company and location</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company Legal Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company->company_name ?? '') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Classification <span class="text-rose-500">*</span></label>
                        <select name="business_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                            <option value="Manufacturer" {{ ($user->company->business_type ?? '') == 'Manufacturer' ? 'selected' : '' }}>Manufacturer / Factory</option>
                            <option value="OEM / ODM Supplier" {{ ($user->company->business_type ?? '') == 'OEM / ODM Supplier' ? 'selected' : '' }}>OEM / ODM Supplier</option>
                            <option value="Wholesaler" {{ ($user->company->business_type ?? '') == 'Wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                            <option value="Distributor" {{ ($user->company->business_type ?? '') == 'Distributor' ? 'selected' : '' }}>Distributor / Exporter</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Origin Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" value="{{ old('country', $user->company->country ?? $user->country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City / Industrial Zone</label>
                        <input type="text" name="city" value="{{ old('city', $user->company->city ?? $user->city) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Year Established</label>
                        <input type="number" name="year_established" value="{{ old('year_established', $user->company->year_established ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tax / GST / VAT ID</label>
                        <input type="text" name="tax_id" value="{{ old('tax_id', $user->company->tax_id ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Manufacturing Capabilities -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Industrial Equipment & Manufacturing Scale</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Export Markets</label>
                        <input type="text" name="export_markets" value="{{ old('export_markets', $user->company->export_markets ?? 'United Kingdom, Europe, North America') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Plant Capabilities & Machinery</label>
                        <textarea name="manufacturing_capabilities" rows="2" 
                                  placeholder="e.g. 5,000 MT forging press, 5-axis CNC machines, heat treatment furnace..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">{{ old('manufacturing_capabilities', $user->company->manufacturing_capabilities ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Plant Address</label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">{{ old('address', $user->company->address ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Overview Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none">{{ old('description', $user->company->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Plant Profile</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function previewSupplierLogo(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('supplierLogoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
