@extends('layouts.supplier')

@section('title', 'My Products Catalog — Supplier Portal')
@section('header_title', 'Product Catalog & Listings (CRUD)')
@section('header_subtitle', 'Manage manufacturing machinery, tariff HS codes, technical specifications, and inventory')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-sm font-black text-slate-900">Your Active Factory Listings</h2>
            <p class="text-xs text-slate-500">Products currently visible to international procurement buyers</p>
        </div>
        <a href="{{ route('supplier.products.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-2 flex-shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>+ Add New Product</span>
        </a>
    </div>

    <!-- Products List -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($products->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-boxes-stacked text-3xl mb-3 text-slate-300 block"></i>
                <h3 class="text-base font-bold text-slate-800">You haven't listed any products yet</h3>
                <p class="text-xs text-slate-500 mt-1">Publish your first machinery listing to receive direct buyer orders and inquiries.</p>
                <a href="{{ route('supplier.products.create') }}" class="inline-block mt-4 px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-xs">Add Product Now</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($products as $product)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <div class="flex items-center space-x-4 min-w-0">
                            <img src="{{ $product->main_image }}" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 border border-slate-200 flex-shrink-0" alt="{{ $product->name }}">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded">HS: {{ $product->hs_code }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $product->status }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-400">Category: {{ $product->category->name ?? 'General' }}</span>
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 truncate">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="hover:text-emerald-700 transition">{{ $product->name }}</a>
                                </h3>
                                <div class="text-xs text-slate-500">
                                    Base Price: <strong class="text-slate-900">£{{ number_format($product->base_price, 2) }}</strong> / {{ $product->unit }} • MOQ: {{ $product->moq }} • Lead Time: {{ $product->lead_time_days }} days
                                </div>
                            </div>
                        </div>

                        <!-- Actions (Edit, View, Delete) -->
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('supplier.products.edit', $product->id) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit</span>
                            </a>

                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>

                            <form action="{{ route('supplier.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete product {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition" title="Delete Product">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
