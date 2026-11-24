@extends('layouts.admin')

@section('title', 'Add New Supplier — Perlcon Admin')
@section('header_title', 'Register Industrial Supplier')
@section('header_subtitle', 'Onboard a manufacturer or exporter from India, China, Europe or other global origins')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.suppliers') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Suppliers List</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: User Account Credentials -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Supplier Login Credentials</h3>
                <p class="text-xs text-slate-500 mb-4">Account access for supplier representative</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Contact Person Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               placeholder="e.g. Ramesh Patel / Li Wei"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address (Login) <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               placeholder="e.g. sales@bharatforge.com"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">WhatsApp / Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               placeholder="e.g. +91 98200 12345"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required 
                               placeholder="Minimum 6 characters"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Industrial Manufacturing Entity -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Manufacturing Plant & Enterprise Details</h3>
                <p class="text-xs text-slate-500 mb-4">Official registered company name, origin country, and export capabilities</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company / Factory Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" required 
                               placeholder="e.g. Bharat Heavy Forge & Engineering Ltd"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Type <span class="text-rose-500">*</span></label>
                        <select name="business_type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="Manufacturer">Manufacturer / Production Plant</option>
                            <option value="OEM / ODM Supplier">OEM / ODM Supplier</option>
                            <option value="Authorized Distributor">Authorized Industrial Distributor</option>
                            <option value="Trading Company">International Trading Company</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Origin Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" value="{{ old('country', 'India') }}" required 
                               placeholder="e.g. India, China, Germany, Italy, Japan"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City / Industrial Area</label>
                        <input type="text" name="city" value="{{ old('city') }}" 
                               placeholder="e.g. Ahmedabad, Shenzhen, Stuttgart"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Export Markets</label>
                        <input type="text" name="export_markets" value="{{ old('export_markets', 'United Kingdom, Europe, North America') }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Plant Capabilities (Tonnage, Machines)</label>
                        <input type="text" name="manufacturing_capabilities" value="{{ old('manufacturing_capabilities') }}" 
                               placeholder="e.g. 5,000 MT/month forging press, 5-axis CNC"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company Overview / Description</label>
                        <textarea name="description" rows="3" 
                                  placeholder="Brief introduction to the company's manufacturing history and certifications..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer mt-2">
                            <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-700">Audit & Verify Immediately (Grant Gold Verified Manufacturer Badge)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.suppliers') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Register Supplier</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
