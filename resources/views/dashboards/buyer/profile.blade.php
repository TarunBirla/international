@extends('layouts.buyer')

@section('title', 'Company Profile — Buyer Portal')
@section('header_title', 'Corporate Profile & Delivery Details')
@section('header_subtitle', 'Manage company registration, tax/VAT details, and standard delivery destinations')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('buyer.profile.update') }}" method="POST" class="space-y-6">
            @csrf

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
@endsection
