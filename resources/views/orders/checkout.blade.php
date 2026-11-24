@extends('layouts.app')

@section('title', 'Secure Checkout & PO Generation — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-brand-700">Cart</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Checkout</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Industrial Procurement Checkout</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Multi-supplier order routing, international freight booking and HMRC customs pre-lodgement.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left 7 Cols: Address & Payment Info -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Multi-Supplier Split Order Alert -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-xs text-blue-900 flex items-start space-x-3">
                    <i class="fa-solid fa-code-fork text-blue-600 text-lg mt-0.5"></i>
                    <div>
                        <strong class="text-sm font-bold block mb-1">Automated Multi-Supplier Order Splitting</strong>
                        <span>Your order contains items from {{ count($suppliers) }} different suppliers. Perlcon will issue 1 unified invoice to your company, while automatically creating separate Purchase Orders (POs) for each overseas factory.</span>
                    </div>
                </div>

                <!-- UK Delivery & Receiving Address -->
                <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm space-y-4">
                    <h2 class="text-base font-black text-slate-900 flex items-center">
                        <i class="fa-solid fa-truck-ramp-box text-brand-700 mr-2.5"></i> UK Delivery & Consignee Address
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Recipient Name *</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Company Name *</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $user->company->company_name ?? 'UK Engineering Ltd') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Address Line 1 (Street / Industrial Estate) *</label>
                        <input type="text" name="address_line_1" value="{{ old('address_line_1', $user->company->address ?? 'Unit 14, West Midlands Industrial Park') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">City *</label>
                            <input type="text" name="city" value="{{ old('city', $user->city ?? 'Birmingham') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Postal Code *</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', 'B7 4TR') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Country *</label>
                            <input type="text" name="country" value="United Kingdom" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Site Contact Phone *</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $user->phone ?? '+44 121 496 0884') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm space-y-4">
                    <h2 class="text-base font-black text-slate-900 flex items-center">
                        <i class="fa-solid fa-credit-card text-brand-700 mr-2.5"></i> Trade Assurance Payment
                    </h2>

                    <div class="space-y-3 text-xs font-semibold">
                        <label class="flex items-center space-x-3 p-4 rounded-2xl border-2 border-brand-500 bg-brand-50/40 cursor-pointer">
                            <input type="radio" name="payment_method" value="stripe" checked class="w-4 h-4 text-brand-700">
                            <div>
                                <strong class="text-slate-900 text-sm block">Online Corporate Card / Stripe Escrow</strong>
                                <span class="text-slate-500 text-[11px]">Instant authorization. Held securely in escrow until Bill of Lading is uploaded.</span>
                            </div>
                        </label>

                        <label class="flex items-center space-x-3 p-4 rounded-2xl border border-slate-200 hover:border-slate-300 cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" class="w-4 h-4 text-brand-700">
                            <div>
                                <strong class="text-slate-900 text-sm block">BACS / CHAPS UK Bank Wire Transfer</strong>
                                <span class="text-slate-500 text-[11px]">Pro-forma invoice generated for bank transfer directly to Perlcon UK client account.</span>
                            </div>
                        </label>

                        <label class="flex items-center space-x-3 p-4 rounded-2xl border border-slate-200 hover:border-slate-300 cursor-pointer">
                            <input type="radio" name="payment_method" value="letter_of_credit" class="w-4 h-4 text-brand-700">
                            <div>
                                <strong class="text-slate-900 text-sm block">Irrevocable Letter of Credit (L/C) at Sight</strong>
                                <span class="text-slate-500 text-[11px]">Recommended for orders exceeding £50,000. Confirmed by Tier-1 British Bank.</span>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Special Delivery / Unloading Instructions</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Forklift on site for offloading, deliver strictly between 8 AM - 4 PM..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
                    </div>
                </div>

            </div>

            <!-- Right 5 Cols: Items & Total Landed Cost -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-black text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Order Summary</span>
                        <span class="text-xs font-bold text-brand-700">{{ count($cartItems) }} Items</span>
                    </h2>

                    <!-- Items list -->
                    <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 text-xs">
                        @foreach($cartItems as $item)
                            @php
                                $price = $item->product->getEffectivePriceForQuantity($item->quantity);
                            @endphp
                            <div class="py-2.5 flex justify-between items-center">
                                <div class="max-w-[200px]">
                                    <strong class="text-slate-900 block truncate">{{ $item->product->name }}</strong>
                                    <span class="text-slate-400 text-[11px]">Qty: {{ $item->quantity }} {{ $item->product->unit }}</span>
                                </div>
                                <span class="font-black text-slate-900">£{{ number_format($price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 pt-3 border-t border-slate-100 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal (FOB Goods)</span>
                            <span class="font-bold text-slate-900">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Consolidated Freight</span>
                            <span class="font-bold text-slate-900">£{{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Estimated Customs Duty</span>
                            <span class="font-bold text-slate-900">£{{ number_format($duty, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>UK Import VAT (20%)</span>
                            <span class="font-bold text-slate-900">£{{ number_format($vat, 2) }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-sm font-extrabold text-slate-900">Final Landed Total</span>
                        <span class="text-2xl font-black text-brand-700">£{{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-700 hover:bg-brand-800 text-white font-black rounded-xl text-base transition shadow-lg shadow-brand-700/25 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-lock"></i>
                        <span>Confirm Order & Authorize Escrow</span>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
