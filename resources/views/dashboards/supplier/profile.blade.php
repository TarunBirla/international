@extends('layouts.app')

@section('title', 'Plant Capabilities & Profile — Supplier Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Plant Profile & Manufacturing Capabilities</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Showcase your factory equipment, quality inspection standards, and export certifications to buyers.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Manage Products</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Inquiries</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Quotations</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Subscriptions</a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <form action="{{ route('supplier.profile.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company Legal Name *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company->company_name ?? '') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Business Type *</label>
                    <select name="business_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none bg-white">
                        <option value="manufacturer" {{ ($user->company->business_type ?? '') == 'manufacturer' ? 'selected' : '' }}>Manufacturer / Factory</option>
                        <option value="wholesaler" {{ ($user->company->business_type ?? '') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                        <option value="distributor" {{ ($user->company->business_type ?? '') == 'distributor' ? 'selected' : '' }}>Distributor / Exporter</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country *</label>
                    <input type="text" name="country" value="{{ old('country', $user->company->country ?? '') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->company->city ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Year Established</label>
                    <input type="text" name="year_established" value="{{ old('year_established', $user->company->year_established ?? '') }}" placeholder="e.g. 1998" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Workforce Count</label>
                    <input type="text" name="employees_count" value="{{ old('employees_count', $user->company->employees_count ?? '') }}" placeholder="e.g. 450+ Engineers" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Annual Turnover</label>
                    <input type="text" name="annual_turnover" value="{{ old('annual_turnover', $user->company->annual_turnover ?? '') }}" placeholder="e.g. $25M - $50M" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tax / GST / VAT ID</label>
                    <input type="text" name="tax_id" value="{{ old('tax_id', $user->company->tax_id ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Factory Address</label>
                <input type="text" name="address" value="{{ old('address', $user->company->address ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Major Export Markets</label>
                <input type="text" name="export_markets" value="{{ old('export_markets', $user->company->export_markets ?? 'UK, Europe, USA, Middle East') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Manufacturing Capabilities & Plant Machinery</label>
                <textarea name="manufacturing_capabilities" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('manufacturing_capabilities', $user->company->manufacturing_capabilities ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Company Description</label>
                <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('description', $user->company->description ?? '') }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                Save Plant Profile
            </button>
        </form>
    </div>
</div>
@endsection
