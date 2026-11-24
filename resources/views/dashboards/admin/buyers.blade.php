@extends('layouts.app')

@section('title', 'Manage Buyers — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buyer Accounts & EPC Contractors</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Manage registered procurement managers and industrial importers.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Buyers</a>
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
                        <th class="p-4">Buyer Name & Email</th>
                        <th class="p-4">Company</th>
                        <th class="p-4">Location</th>
                        <th class="p-4">RFQs Posted</th>
                        <th class="p-4">Orders Placed</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($buyers as $buyer)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $buyer->name }}</div>
                                <span class="text-slate-500 text-[11px]">{{ $buyer->email }}</span>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                {{ $buyer->company->company_name ?? 'Individual Buyer' }}
                            </td>
                            <td class="p-4 text-slate-600">
                                {{ $buyer->city ?? 'London' }}, {{ $buyer->country ?? 'United Kingdom' }}
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                {{ count($buyer->rfqs) }}
                            </td>
                            <td class="p-4 font-black text-slate-900">
                                {{ count($buyer->orders) }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($buyer->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $buyers->links() }}
        </div>
    </div>
</div>
@endsection
