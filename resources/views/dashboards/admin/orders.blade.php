@extends('layouts.app')

@section('title', 'Manage All Orders — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Marketplace Orders & Consignments</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Track all orders, multi-supplier split purchase orders, shipments, and customs clearances.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Buyers</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Products</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Orders</a>
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
                        <th class="p-4">Order #</th>
                        <th class="p-4">Buyer Company</th>
                        <th class="p-4">Split POs</th>
                        <th class="p-4">Landed Total</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">14-Stage Status</th>
                        <th class="p-4 text-right">Tracker</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($orders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono font-bold text-brand-700">{{ $order->order_number }}</td>
                            <td class="p-4">
                                <strong class="text-slate-900 block">{{ $order->buyer->company->company_name ?? $order->buyer->name }}</strong>
                                <span class="text-slate-400 text-[10px]">{{ $order->destination_country }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-brand-700">
                                    {{ count($order->orderItems) }} POs (Multi-Supplier)
                                </span>
                            </td>
                            <td class="p-4 font-black text-slate-900 text-sm">
                                £{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-blue-100 text-brand-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('orders.show', $order->id) }}" class="px-3 py-1.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                    View Tracker &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
