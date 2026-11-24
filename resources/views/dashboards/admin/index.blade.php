@extends('layouts.app')

@section('title', 'Super Admin Command Center — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-purple-700 uppercase tracking-wider block">Super Admin Command Center</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Marketplace Operations & Governance</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Control suppliers, buyers, product audits, customs declarations, and international consignments.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('setup.index') }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                    <i class="fa-solid fa-server"></i>
                    <span>Database Tools</span>
                </a>
                <a href="{{ route('admin.reports.export-csv') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export Orders CSV</span>
                </a>
            </div>
        </div>

        <!-- Super Admin Subnav Tabs -->
        <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-slate-200 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Suppliers ({{ $stats['total_suppliers'] }})</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Buyers ({{ $stats['total_buyers'] }})</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Products ({{ $stats['total_products'] }})</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Orders ({{ $stats['total_orders'] }})</a>
            <a href="{{ route('admin.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">RFQs ({{ $stats['total_rfqs'] }})</a>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200 transition">Reports & Analytics</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Stat Counters (5 Columns) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total Suppliers</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['total_suppliers'] }}</span>
            <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">{{ $stats['verified_suppliers'] }} Verified</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Registered Buyers</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['total_buyers'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Total Orders</span>
            <span class="text-2xl font-black text-slate-900">{{ $stats['total_orders'] }}</span>
            <span class="text-[10px] text-brand-700 font-bold block mt-0.5">£{{ number_format($stats['total_revenue'], 2) }} Volume</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Active Shipments</span>
            <span class="text-2xl font-black text-blue-700">{{ $stats['active_shipments'] }}</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block mb-1">Pending Customs</span>
            <span class="text-2xl font-black text-amber-600">{{ $stats['pending_customs'] }}</span>
            <span class="text-[10px] text-slate-400 block mt-0.5">Junaid & Co.</span>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
            <h2 class="text-base font-black text-slate-900">Recent Marketplace Transactions</h2>
            <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-brand-700 hover:underline">View All Orders &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                    <tr>
                        <th class="p-3">Order #</th>
                        <th class="p-3">Buyer Company</th>
                        <th class="p-3">Total Landed</th>
                        <th class="p-3">Payment</th>
                        <th class="p-3">Tracking Stage</th>
                        <th class="p-3">Date</th>
                        <th class="p-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-mono font-bold text-brand-700">{{ $order->order_number }}</td>
                            <td class="p-3 font-bold text-slate-900">{{ $order->buyer->company->company_name ?? $order->buyer->name }}</td>
                            <td class="p-3 font-black text-slate-900">£{{ number_format($order->total_amount, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-brand-800">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td class="p-3 text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('orders.show', $order->id) }}" class="text-xs text-brand-700 font-bold hover:underline">
                                    Manage &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2 Column: Suppliers & RFQs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Suppliers Management -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">Registered Suppliers</h3>
                <a href="{{ route('admin.suppliers') }}" class="text-xs font-bold text-brand-700 hover:underline">Manage All</a>
            </div>

            <div class="space-y-3">
                @foreach($recentSuppliers as $sup)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center text-xs">
                        <div>
                            <strong class="text-slate-900 block font-bold">{{ $sup->company->company_name ?? $sup->name }}</strong>
                            <span class="text-[11px] text-slate-500">{{ $sup->country }} • {{ $sup->company->business_type ?? 'Exporter' }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($sup->company && $sup->company->is_verified)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Verified</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-600">Pending</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- RFQs Management -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">Latest Buyer RFQs</h3>
                <a href="{{ route('admin.rfqs') }}" class="text-xs font-bold text-brand-700 hover:underline">Manage All</a>
            </div>

            <div class="space-y-3">
                @foreach($recentRfqs as $rfq)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center text-xs">
                        <div>
                            <span class="font-mono text-[10px] font-bold text-slate-400 block">{{ $rfq->rfq_number }}</span>
                            <strong class="text-slate-900 block truncate max-w-[200px]">{{ $rfq->product_name }}</strong>
                            <span class="text-[11px] text-slate-500">{{ $rfq->quantity }} {{ $rfq->unit }} • By {{ $rfq->buyer->name }}</span>
                        </div>
                        <a href="{{ route('rfqs.show', $rfq->id) }}" class="px-3 py-1.5 bg-brand-700 text-white rounded-lg text-xs font-bold hover:bg-brand-800 transition">
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
