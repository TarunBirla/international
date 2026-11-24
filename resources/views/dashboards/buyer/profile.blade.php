@extends('layouts.app')

@section('title', 'Company Profile & Settings — Buyer Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buyer Profile & Company Information</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Manage your corporate credentials, billing address, and contact details.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('buyer.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My RFQs</a>
            <a href="{{ route('buyer.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Received Quotations</a>
            <a href="{{ route('buyer.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Purchase Orders</a>
            <a href="{{ route('buyer.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">My Enquiries</a>
            <a href="{{ route('buyer.profile') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Company Details</a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <form action="{{ route('buyer.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Corporate Email (Read Only)</label>
                    <input type="email" value="{{ $user->email }}" readonly class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-400">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company Legal Name *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company->company_name ?? '') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">UK VAT / EORI Number</label>
                    <input type="text" name="tax_id" value="{{ old('tax_id', $user->company->tax_id ?? '') }}" placeholder="GB 984 2134 50" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country ?? 'United Kingdom') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Default Consignee Delivery Address</label>
                <textarea name="address" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('address', $user->company->address ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Company Procurement Description</label>
                <textarea name="description" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs">{{ old('description', $user->company->description ?? '') }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
