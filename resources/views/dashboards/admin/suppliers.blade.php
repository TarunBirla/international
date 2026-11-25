@extends('layouts.admin')

@section('title', 'Manage Suppliers & Verification — Mart Admin')
@section('header_title', 'Suppliers & Manufacturers')
@section('header_subtitle', 'Audit international plants, manage KYC verification badges, and control supplier accounts')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            <!-- Country Filter Pills -->
            <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="{{ route('admin.suppliers') }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ !request('country') ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Countries
                </a>
                <a href="{{ route('admin.suppliers', ['country' => 'India', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('country') === 'India' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    🇮🇳 India
                </a>
                <a href="{{ route('admin.suppliers', ['country' => 'China', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('country') === 'China' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    🇨🇳 China
                </a>
                <a href="{{ route('admin.suppliers', ['country' => 'Germany', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('country') === 'Germany' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    🇩🇪 Europe (Germany)
                </a>
            </div>

            <!-- Search and Add Button -->
            <div class="flex items-center space-x-3 w-full lg:w-auto">
                <form action="{{ route('admin.suppliers') }}" method="GET" class="relative flex-1 lg:w-64">
                    @if(request('country'))
                        <input type="hidden" name="country" value="{{ request('country') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company or email..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>

                <a href="{{ route('admin.suppliers.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5 flex-shrink-0">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Add Supplier</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Plant / Company Details</th>
                        <th class="p-4">Origin & Location</th>
                        <th class="p-4">Business Type</th>
                        <th class="p-4">Products</th>
                        <th class="p-4">KYC / Verification</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- Company Name & Contact -->
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        <img src="{{ $supplier->company ? $supplier->company->logo_url : $supplier->avatar_url }}" alt="{{ $supplier->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-slate-900 text-sm block">
                                            {{ $supplier->company->company_name ?? $supplier->name }}
                                        </span>
                                        <span class="text-slate-500 text-[11px] block">{{ $supplier->email }}</span>
                                        <span class="text-slate-400 text-[10px]">{{ $supplier->name }} • {{ $supplier->phone ?? 'No phone' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Country & City -->
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $supplier->country }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $supplier->city ?? 'N/A' }}</span>
                            </td>

                            <!-- Business Type -->
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $supplier->company->business_type ?? 'Manufacturer' }}
                                </span>
                            </td>

                            <!-- Products Count -->
                            <td class="p-4 font-bold text-slate-900">
                                {{ $supplier->products->count() }} Items
                            </td>

                            <!-- Verification Status & Toggle -->
                            <td class="p-4">
                                @if($supplier->company && $supplier->company->is_verified)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i> Verified Plant
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-clock mr-1 text-amber-500"></i> Pending Audit
                                    </span>
                                @endif

                                @if($supplier->company)
                                    <form action="{{ route('admin.suppliers.toggle-verification', $supplier->company->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold underline hover:text-brand-700 text-slate-500">
                                            {{ $supplier->company->is_verified ? 'Revoke Status' : 'Grant Verified Badge' }}
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <!-- Actions (View, Edit, Delete) -->
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    @if($supplier->company)
                                        <a href="{{ route('suppliers.show', $supplier->company->slug) }}" target="_blank"
                                           title="View Public Profile"
                                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" 
                                       title="Edit Supplier"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-700 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Permanently delete supplier {{ $supplier->company->company_name ?? $supplier->name }} and all their listings?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Supplier"
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-industry text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No suppliers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
