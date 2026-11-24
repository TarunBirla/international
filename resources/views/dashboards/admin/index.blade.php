@extends('layouts.admin')

@section('title', 'Super Admin Command Center — Perlcon International')
@section('header_title', 'Marketplace Command Center')
@section('header_subtitle', 'Global procurement oversight, supplier verification, and consignment control')

@section('content')
<div class="space-y-8">

    <!-- KPI Metrics Grid (6 Columns) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-brand-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Suppliers</span>
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-brand-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-industry"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_suppliers'] }}</p>
            <div class="flex items-center space-x-1.5 mt-1.5">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fa-solid fa-check-circle mr-1"></i> {{ $stats['verified_suppliers'] }} Verified
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-brand-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Buyers</span>
                <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-briefcase"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_buyers'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">UK & International</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-brand-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Products</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_products'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">{{ $stats['total_categories'] }} Categories</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-brand-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Orders Vol</span>
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 tracking-tight">£{{ number_format($stats['total_revenue'], 0) }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">{{ $stats['total_orders'] }} Total Orders</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-brand-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Shipments</span>
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-truck-fast"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-indigo-700 tracking-tight">{{ $stats['active_shipments'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">In Transit / Freight</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-brand-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Customs</span>
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-stamp"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-rose-600 tracking-tight">{{ $stats['pending_customs'] }}</p>
            <p class="text-[11px] text-slate-500 font-medium mt-1.5">Junaid & Co. Queue</p>
        </div>

    </div>

    <!-- Quick Action Launchpad -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Quick Management Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            <a href="{{ route('admin.users.create') }}" class="p-3 bg-slate-50 hover:bg-brand-50 border border-slate-200 hover:border-brand-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-brand-700 mb-2">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-brand-800 block">Add User</span>
                <span class="text-[10px] text-slate-500 block">Any Role</span>
            </a>
            <a href="{{ route('admin.suppliers.create') }}" class="p-3 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-emerald-700 mb-2">
                    <i class="fa-solid fa-building-circle-check text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-800 block">Add Supplier</span>
                <span class="text-[10px] text-slate-500 block">Plant / Factory</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="p-3 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-blue-700 mb-2">
                    <i class="fa-solid fa-box-archive text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-800 block">Add Product</span>
                <span class="text-[10px] text-slate-500 block">With HS Code</span>
            </a>
            <a href="{{ route('admin.categories') }}" class="p-3 bg-slate-50 hover:bg-purple-50 border border-slate-200 hover:border-purple-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-purple-700 mb-2">
                    <i class="fa-solid fa-network-wired text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-purple-800 block">Categories</span>
                <span class="text-[10px] text-slate-500 block">49 Hierarchies</span>
            </a>
            <a href="{{ route('admin.reports.export-csv') }}" class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-300 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-slate-600 group-hover:text-slate-900 mb-2">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 group-hover:text-slate-900 block">Export Orders</span>
                <span class="text-[10px] text-slate-500 block">CSV File</span>
            </a>
            <a href="{{ route('setup.index') }}" class="p-3 bg-amber-50/60 hover:bg-amber-100/70 border border-amber-200 rounded-2xl text-center group transition">
                <div class="w-9 h-9 mx-auto rounded-xl bg-white shadow-xs flex items-center justify-center text-amber-700 group-hover:text-amber-800 mb-2">
                    <i class="fa-solid fa-server text-sm"></i>
                </div>
                <span class="text-xs font-bold text-amber-900 block">Server Setup</span>
                <span class="text-[10px] text-amber-700 block">Auto DB Route</span>
            </a>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Recent Orders (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-black text-slate-900">Recent Marketplace Orders</h2>
                    <p class="text-xs text-slate-500">Live order split POs and payment confirmations</p>
                </div>
                <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-brand-700 hover:underline">
                    View All Orders &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="p-3 rounded-l-xl">Order #</th>
                            <th class="p-3">Buyer Company</th>
                            <th class="p-3">Total Amount</th>
                            <th class="p-3">Payment</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 rounded-r-xl text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-3 font-bold text-slate-900">
                                    <a href="{{ route('orders.show', $order->id) }}" class="hover:text-brand-700">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <span class="font-bold text-slate-900 block">{{ $order->buyer->company->company_name ?? $order->buyer->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->destination_country ?? 'UK' }}</span>
                                </td>
                                <td class="p-3 font-extrabold text-slate-900">
                                    £{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="p-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ ucwords(str_replace('_', ' ', $order->order_status)) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('orders.show', $order->id) }}" class="text-xs font-bold text-brand-700 hover:text-brand-900">
                                        Inspect &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">No orders recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Recent RFQs & Pending Approvals -->
        <div class="space-y-6">
            
            <!-- Recent RFQs -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm font-black text-slate-900">Recent RFQ Tenders</h2>
                    <a href="{{ route('admin.rfqs') }}" class="text-[11px] font-bold text-brand-700 hover:underline">View All</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentRfqs as $rfq)
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 hover:border-slate-200 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-brand-700">#{{ $rfq->rfq_number }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-white text-slate-600 border border-slate-200">
                                    {{ ucfirst($rfq->status) }}
                                </span>
                            </div>
                            <p class="text-xs font-bold text-slate-900 mt-1 truncate">{{ $rfq->product_name }}</p>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                                <span>Qty: {{ number_format($rfq->quantity) }} {{ $rfq->unit }}</span>
                                <span class="text-slate-400">{{ $rfq->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No active RFQs.</p>
                    @endforelse
                </div>
            </div>

            <!-- New Registered Users -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm font-black text-slate-900">New User Registrations</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-[11px] font-bold text-brand-700 hover:underline">Manage All</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                            <div class="flex items-center space-x-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 uppercase flex-shrink-0">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No users found.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
