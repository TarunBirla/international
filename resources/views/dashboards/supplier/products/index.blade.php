@extends('layouts.app')

@section('title', 'Product Management — Supplier Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Product Catalogue Management</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Add, edit, and update your manufactured products on Perlcon International.</p>
            </div>
            <a href="{{ route('supplier.products.create') }}" class="px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Product</span>
            </a>
        </div>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Manage Products</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Inquiries</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Quotations</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Subscriptions</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($products->isEmpty())
            <div class="p-12 text-center">
                <i class="fa-solid fa-boxes-stacked text-4xl text-slate-300 mb-3 block"></i>
                <h3 class="text-base font-bold text-slate-800">You haven't listed any products yet</h3>
                <a href="{{ route('supplier.products.create') }}" class="inline-block mt-4 px-6 py-2.5 bg-brand-700 text-white font-bold rounded-xl text-xs">Add Your First Product</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($products as $product)
                    <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $product->main_image }}" class="w-16 h-16 rounded-xl object-cover bg-slate-100 flex-shrink-0" alt="{{ $product->name }}">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-[10px] font-mono font-bold text-slate-400">HS: {{ $product->hs_code }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $product->status }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-400">Category: {{ $product->category->name ?? 'General' }}</span>
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="hover:text-brand-700 transition">{{ $product->name }}</a>
                                </h3>
                                <div class="text-xs text-slate-500">
                                    Price: <strong class="text-slate-900">£{{ number_format($product->base_price, 2) }}</strong> / {{ $product->unit }} • MOQ: {{ $product->moq }} • Lead Time: {{ $product->lead_time_days }} days
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                            <a href="{{ route('supplier.products.edit', $product->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> View
                            </a>
                            <form action="{{ route('supplier.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
