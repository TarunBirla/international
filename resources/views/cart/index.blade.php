@extends(auth()->check() ? auth()->user()->getDashboardLayout() : 'layouts.app')

@section('title', 'Procurement Cart — Perlcon International')
@section('header_title', 'Procurement Cart')
@section('header_subtitle', 'Review order items and proceed to multi-supplier purchase order split')

@section('content')
@if(!auth()->check())
<div class="bg-slate-50 border-b border-slate-200 py-8 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Procurement Cart</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Your Procurement Cart</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Multi-supplier orders are automatically consolidated and split into supplier-specific purchase orders.</p>
    </div>
</div>
@endif

<div class="{{ auth()->check() ? 'space-y-6' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10' }}">
    @if($cartItems->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 bg-blue-50 text-brand-700 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-900 mb-2">Your procurement cart is empty</h2>
            <p class="text-xs text-slate-500 mb-6">Discover products from manufacturers in India, China and Europe.</p>
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md shadow-brand-700/20">
                Browse Products Catalogue
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Cart Items (8 Cols) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm divide-y divide-slate-100">
                    @foreach($cartItems as $item)
                        @php
                            $effectivePrice = $item->product->getEffectivePriceForQuantity($item->quantity);
                            $lineTotal = $effectivePrice * $item->quantity;
                        @endphp
                        <div class="py-5 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->product->main_image }}" class="w-20 h-20 rounded-2xl object-cover bg-slate-100 flex-shrink-0" alt="{{ $item->product->name }}">
                                <div>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 block mb-0.5">HS: {{ $item->product->hs_code }}</span>
                                    <h3 class="text-sm font-bold text-slate-900 leading-snug hover:text-brand-700 transition">
                                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <div class="text-xs text-slate-500 mt-1">
                                        Supplier: <strong class="text-slate-700">{{ $item->product->company->company_name ?? 'Exporter' }}</strong> 
                                        ({{ $item->product->origin_country }})
                                    </div>
                                    <div class="text-xs text-slate-700 font-bold mt-1">
                                        £{{ number_format($effectivePrice, 2) }} / {{ $item->product->unit }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6">
                                <!-- Quantity Update Form -->
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-1.5">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="{{ $item->product->moq }}" class="w-16 px-2 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-center">
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-brand-700 transition" title="Update Quantity">
                                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                    </button>
                                </form>

                                <div class="text-right">
                                    <span class="text-base font-black text-slate-900 block">
                                        £{{ number_format($lineTotal, 2) }}
                                    </span>
                                </div>

                                <!-- Remove button -->
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Remove Item">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary & Landed Cost Breakdown (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Landed Cost Estimate</span>
                        <span class="text-xs font-bold text-slate-400">{{ count($cartItems) }} Items</span>
                    </h2>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Goods Subtotal (FOB)</span>
                            <span class="font-bold text-slate-900">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>International Freight ({{ count($suppliersCount) }} Origins, {{ $totalWeight }} kg)</span>
                            <span class="font-bold text-slate-900">£{{ number_format($estimatedShipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Estimated UK Customs Duty (Avg 2.5%)</span>
                            <span class="font-bold text-slate-900">£{{ number_format($estimatedDuty, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>UK Import VAT (20%)</span>
                            <span class="font-bold text-slate-900">£{{ number_format($estimatedVat, 2) }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-sm font-extrabold text-slate-900">Total Landed (CIF + VAT)</span>
                        <span class="text-2xl font-black text-brand-700">£{{ number_format($estimatedTotal, 2) }}</span>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-normal">
                        Includes international sea/air freight consolidation, customs pre-declaration, and direct UK site delivery.
                    </p>

                    <a href="{{ route('checkout') }}" class="w-full py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-black rounded-xl text-sm transition shadow-md shadow-brand-700/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-lock"></i>
                        <span>Proceed to Secure Checkout</span>
                    </a>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-xs text-blue-900 flex items-start space-x-2.5">
                    <i class="fa-solid fa-shield-halved text-blue-600 text-base mt-0.5"></i>
                    <div>
                        <strong class="block mb-0.5">Trade Assurance Protected</strong>
                        <span>Your payment is held in escrow until items are verified and Bill of Lading is confirmed.</span>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
