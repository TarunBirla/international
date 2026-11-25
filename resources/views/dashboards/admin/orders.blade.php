@extends('layouts.admin')

@section('title', 'Manage Orders & Split POs — Mart Admin')
@section('header_title', 'Marketplace Orders & Purchase Orders')
@section('header_subtitle', 'Control procurement orders, multi-supplier automated split POs, landed payments, and 14-stage tracking')

@section('content')
<div class="space-y-6" x-data="{ statusModal: false, activeOrderId: '', activeOrderNumber: '', currentStatus: '', currentPayment: '' }">

    <!-- Filters & Actions Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            <!-- Status Filter Pills -->
            <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="{{ route('admin.orders') }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ !request('status') ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Orders
                </a>
                <a href="{{ route('admin.orders', ['status' => 'processing', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'processing' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Factory Production
                </a>
                <a href="{{ route('admin.orders', ['status' => 'shipped', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'shipped' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    In Transit
                </a>
                <a href="{{ route('admin.orders', ['status' => 'delivered', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'delivered' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Delivered
                </a>
            </div>

            <!-- Search and Export Button -->
            <div class="flex items-center space-x-3 w-full lg:w-auto">
                <form action="{{ route('admin.orders') }}" method="GET" class="relative flex-1 lg:w-64">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, buyer, email..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>

                <a href="{{ route('admin.reports.export-csv') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5 flex-shrink-0">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Order #</th>
                        <th class="p-4">Buyer Entity</th>
                        <th class="p-4">Supplier Split POs</th>
                        <th class="p-4">Landed Total (£)</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">Consignment Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- Order Number -->
                            <td class="p-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('orders.show', $order->id) }}" class="hover:text-brand-700 text-brand-800">
                                    #{{ $order->order_number }}
                                </a>
                                <span class="text-[10px] text-slate-400 block font-sans">{{ $order->created_at->format('d M Y') }}</span>
                            </td>

                            <!-- Buyer Company -->
                            <td class="p-4">
                                <span class="font-extrabold text-slate-900 block">{{ $order->buyer->company->company_name ?? $order->buyer->name }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $order->buyer->email }} • {{ $order->destination_country ?? 'UK' }}</span>
                            </td>

                            <!-- Split POs Breakdown -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-brand-700 border border-blue-100">
                                        <i class="fa-solid fa-code-branch mr-1"></i> {{ count($order->orderItems) }} Items / Suppliers
                                    </span>
                                    <div class="text-[10px] text-slate-500">
                                        @foreach($order->orderItems as $item)
                                            <span class="block truncate max-w-xs">• {{ $item->supplier->company->company_name ?? 'Supplier' }} ({{ $item->product_name }})</span>
                                        @endforeach
                                    </div>
                                </div>
                            </td>

                            <!-- Total Amount -->
                            <td class="p-4 font-black text-slate-900 text-sm">
                                £{{ number_format($order->total_amount, 2) }}
                            </td>

                            <!-- Payment Status -->
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>

                            <!-- 14-Stage Status -->
                            <td class="p-4">
                                <button type="button" 
                                        @click="statusModal = true; 
                                                activeOrderId = '{{ $order->id }}'; 
                                                activeOrderNumber = '{{ $order->order_number }}'; 
                                                currentStatus = '{{ $order->order_status }}'; 
                                                currentPayment = '{{ $order->payment_status }}';"
                                        class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-100 text-slate-800 hover:bg-slate-200 transition">
                                    <span>{{ ucwords(str_replace('_', ' ', $order->order_status)) }}</span>
                                    <i class="fa-solid fa-pen ml-1.5 text-[9px] text-slate-400"></i>
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                       title="Inspect 14-Stage Stepper"
                                       class="px-2.5 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs flex items-center space-x-1 transition">
                                        <i class="fa-solid fa-route"></i>
                                        <span>Tracker</span>
                                    </a>

                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Permanently delete order #{{ $order->order_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Order"
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No orders found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- Status Change Modal -->
    <div x-show="statusModal" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200" 
             @click.away="statusModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black text-slate-900">Update Order Status: <span class="font-mono text-brand-700" x-text="'#' + activeOrderNumber"></span></h3>
                <button @click="statusModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/orders') }}/' + activeOrderId + '/status'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">14-Stage Logistics Pipeline Status</label>
                    <select name="order_status" x-model="currentStatus" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="pending">1. Pending Order Confirmation</option>
                        <option value="confirmed">2. Confirmed & PO Issued</option>
                        <option value="processing">3. Factory Production & Quality Inspection</option>
                        <option value="shipped">4. Dispatched / Ocean & Air Freight Transit</option>
                        <option value="customs_clearance">5. Port Arrival & UK Customs Clearance (Junaid & Co.)</option>
                        <option value="out_for_delivery">6. Out for Final UK Inland Delivery</option>
                        <option value="delivered">7. Successfully Delivered & Consigned</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Payment Status</label>
                    <select name="payment_status" x-model="currentPayment" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid in Full</option>
                        <option value="partial">Partial Payment / Letter of Credit</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="statusModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Update Status
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
