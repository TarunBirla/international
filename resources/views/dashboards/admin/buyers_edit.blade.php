@extends('layouts.admin')

@section('title', 'Edit Buyer — Mart Admin')
@section('header_title', 'Edit Buyer: ' . $buyer->name)
@section('header_subtitle', 'Update buyer profile, organization, and delivery contact info')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.buyers') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Buyers List</span>
        </a>

        <form action="{{ route('admin.buyers.destroy', $buyer->id) }}" method="POST" onsubmit="return confirm('Permanently delete this buyer account?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center space-x-1.5 px-3 py-1.5 rounded-lg hover:bg-rose-50 transition">
                <i class="fa-solid fa-trash-can"></i>
                <span>Delete Buyer</span>
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.buyers.update', $buyer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Buyer Credentials</h3>
                <p class="text-xs text-slate-500 mb-4">Contact person & account access</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $buyer->name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $buyer->email) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $buyer->phone) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div x-data="{ showPass: false }">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Change Password (Optional)</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" 
                                   placeholder="Leave blank to keep existing password"
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
                        <input type="text" name="company_name" value="{{ old('company_name', $buyer->company->company_name ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Classification</label>
                        <select name="business_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            @php $bType = old('business_type', $buyer->company->business_type ?? 'Procurement Contractor'); @endphp
                            <option value="Procurement Contractor" {{ $bType === 'Procurement Contractor' ? 'selected' : '' }}>Procurement Contractor</option>
                            <option value="EPC Contractor" {{ $bType === 'EPC Contractor' ? 'selected' : '' }}>EPC Contractor</option>
                            <option value="Industrial Importer" {{ $bType === 'Industrial Importer' ? 'selected' : '' }}>Industrial Importer</option>
                            <option value="Original Equipment Manufacturer" {{ $bType === 'Original Equipment Manufacturer' ? 'selected' : '' }}>Original Equipment Manufacturer (OEM)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" value="{{ old('country', $buyer->country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $buyer->city) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company Logo / Profile Photo</label>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0" id="buyerEditLogoBox">
                                @if($buyer->company && $buyer->company->logo)
                                    <img id="buyerEditLogoImg" src="{{ $buyer->company->logo_url }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-building text-slate-400 text-xl" id="buyerEditDefaultIcon"></i>
                                    <img id="buyerEditLogoImg" src="" class="w-full h-full object-cover hidden">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo" id="buyerEditLogoInput" accept="image/*"
                                       onchange="previewEditBuyerLogo(this)"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-1">Upload new logo or photo to replace existing (JPG, PNG, WEBP)</p>
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
                    <span>Save Changes</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function previewEditBuyerLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('buyerEditLogoImg');
            const defaultIcon = document.getElementById('buyerEditDefaultIcon');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (defaultIcon) defaultIcon.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
