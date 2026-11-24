@extends('layouts.app')

@section('title', 'All RFQs — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Marketplace RFQs & Tenders</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Oversee buyer procurement requests, quotations, and contract awards.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Buyers</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Products</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('admin.rfqs') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">RFQs</a>
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
                        <th class="p-4">RFQ #</th>
                        <th class="p-4">Product Requirement</th>
                        <th class="p-4">Buyer Company</th>
                        <th class="p-4">Volume</th>
                        <th class="p-4">Quotes</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($rfqs as $rfq)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono font-bold text-brand-700">{{ $rfq->rfq_number }}</td>
                            <td class="p-4 font-extrabold text-slate-900">{{ $rfq->product_name }}</td>
                            <td class="p-4">{{ $rfq->buyer->company->company_name ?? $rfq->buyer->name }}</td>
                            <td class="p-4 font-bold">{{ number_format($rfq->quantity) }} {{ $rfq->unit }}</td>
                            <td class="p-4 font-bold text-brand-700">{{ $rfq->quotations_count }} Quotes</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $rfq->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('rfqs.show', $rfq->id) }}" class="text-xs text-brand-700 font-bold hover:underline">
                                    Review &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $rfqs->links() }}
        </div>
    </div>
</div>
@endsection
