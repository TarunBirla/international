@extends('layouts.app')

@section('title', 'Manage Suppliers — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Supplier Verification & Management</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Audit overseas manufacturing plants, toggle verification badges, and inspect plant capabilities.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Buyers</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Products</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('admin.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">RFQs</a>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Reports</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                    <tr>
                        <th class="p-4">Company & Contact</th>
                        <th class="p-4">Country & Origin</th>
                        <th class="p-4">Business Type</th>
                        <th class="p-4">Products</th>
                        <th class="p-4">Audit Status</th>
                        <th class="p-4 text-right">Verification Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($suppliers as $supplier)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <div class="font-extrabold text-slate-900 text-sm">
                                    {{ $supplier->company->company_name ?? $supplier->name }}
                                </div>
                                <span class="text-slate-500 text-[11px]">{{ $supplier->email }} • {{ $supplier->phone ?? 'No phone' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800">{{ $supplier->country }}</span>
                                <span class="text-slate-400 block text-[10px]">{{ $supplier->city }}</span>
                            </td>
                            <td class="p-4 capitalize text-slate-700">
                                {{ $supplier->company->business_type ?? 'Exporter' }}
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                {{ count($supplier->products) }} Listed
                            </td>
                            <td class="p-4">
                                @if($supplier->company && $supplier->company->is_verified)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center w-max space-x-1">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Verified Plant</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 flex items-center w-max space-x-1">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>Pending Audit</span>
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                @if($supplier->company)
                                    <form action="{{ route('admin.suppliers.toggle-verification', $supplier->company->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $supplier->company->is_verified ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm' }}">
                                            {{ $supplier->company->is_verified ? 'Revoke Verification' : 'Verify Supplier' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
