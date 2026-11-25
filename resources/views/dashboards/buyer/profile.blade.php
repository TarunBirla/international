@extends('layouts.buyer')

@section('title', 'Company Profile — Buyer Portal')
@section('header_title', 'Corporate Profile & Delivery Details')
@section('header_subtitle', 'Manage company registration, tax/VAT details, and standard delivery destinations')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Company Logo / Buyer Photo Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Company Logo / Profile Photo</label>
                <div class="flex items-center space-x-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-sm">
                        <img id="buyerLogoPreview" 
                             src="{{ $user->company && $user->company->logo ? $user->company->logo_url : $user->avatar_url }}" 
                             alt="{{ $user->company->company_name ?? $user->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="logo" id="buyerLogoInput" accept="image/*" class="hidden" onchange="previewBuyerProfileLogo(event)">
                        <button type="button" onclick="document.getElementById('buyerLogoInput').click()" class="px-4 py-2 bg-white border border-slate-300 hover:border-slate-400 text-slate-700 font-bold rounded-xl text-xs transition shadow-sm inline-flex items-center space-x-2">
                            <i class="fa-solid fa-cloud-arrow-up text-sky-600"></i>
                            <span>Choose Logo / Photo</span>
                        </button>
                        <p class="text-[11px] text-slate-500 mt-1.5">PNG, JPG, WEBP, or SVG up to 4MB.</p>
                    </div>
                </div>
            </div>

            <!-- Section 1: Contact Details -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Contact Officer Information</h3>
                <p class="text-xs text-slate-500 mb-4">Official representative for procurement contracts</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" value="{{ $user->email }}" disabled 
                               class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-medium text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Enterprise Details -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Enterprise Registration & Delivery Address</h3>
                <p class="text-xs text-slate-500 mb-4">Official billing and import delivery location</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company->company_name ?? '') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tax / VAT Registration ID</label>
                        <input type="text" name="tax_id" value="{{ old('tax_id', $user->company->tax_id ?? '') }}" 
                               placeholder="e.g. GB 123 4567 89"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Default Delivery Address</label>
                        <textarea name="address" rows="2" 
                                  placeholder="Warehouse or plant delivery destination..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('address', $user->company->address ?? '') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Description</label>
                        <textarea name="description" rows="2" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('description', $user->company->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Company Profile</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function previewBuyerProfileLogo(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('buyerLogoPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
