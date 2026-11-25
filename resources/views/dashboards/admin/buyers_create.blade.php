@extends('layouts.admin')

@section('title', 'Add New Buyer — Mart Admin')
@section('header_title', 'Register Buyer Account')
@section('header_subtitle', 'Onboard an engineering procurement company or institutional buyer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.buyers') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Buyers List</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.buyers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Buyer Credentials</h3>
                <p class="text-xs text-slate-500 mb-4">Contact person & account access</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               placeholder="e.g. Richard Thornton"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               placeholder="e.g. r.thornton@apexcontracting.co.uk"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               placeholder="e.g. +44 20 7946 0912"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div x-data="{ showPass: false }">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" required 
                                   placeholder="Minimum 6 characters"
                                   class="w-full px-4 py-2.5 pr-10 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" title="Toggle password visibility">
                                <i class="fa-solid text-xs" :class="showPass ? 'fa-eye-slash text-brand-600' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Enterprise Company Details</h3>
                <p class="text-xs text-slate-500 mb-4">Corporate purchasing entity</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company / Organization Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" 
                               placeholder="e.g. Apex British Industrial Contracting Ltd"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Classification</label>
                        <select name="business_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="Procurement Contractor">Procurement Contractor</option>
                            <option value="EPC Contractor">EPC Contractor</option>
                            <option value="Industrial Importer">Industrial Importer</option>
                            <option value="Original Equipment Manufacturer">Original Equipment Manufacturer (OEM)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" value="{{ old('country', 'United Kingdom') }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', 'London') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company Logo / Profile Photo</label>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0" id="buyerLogoPreviewBox">
                                <i class="fa-solid fa-building text-slate-400 text-xl" id="buyerLogoDefaultIcon"></i>
                                <img id="buyerLogoPreviewImg" src="" class="w-full h-full object-cover hidden">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo" id="buyerLogoInput" accept="image/*"
                                       onchange="previewBuyerLogo(this)"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-1">Upload company logo (JPG, PNG, WEBP)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.buyers') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Register Buyer</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function previewBuyerLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('buyerLogoPreviewImg');
            const defaultIcon = document.getElementById('buyerLogoDefaultIcon');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (defaultIcon) defaultIcon.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
