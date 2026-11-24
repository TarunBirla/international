@extends('layouts.app')

@section('title', 'Supplier Subscription Plans — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Supplier Subscription Plans & Exporter Tiers</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Upgrade your plant tier to unlock higher product limits, priority RFQs, and featured placement.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('supplier.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('supplier.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Manage Products</a>
            <a href="{{ route('supplier.enquiries') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Inquiries</a>
            <a href="{{ route('supplier.quotations') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Quotations</a>
            <a href="{{ route('supplier.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('supplier.profile') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Plant Capabilities</a>
            <a href="{{ route('supplier.subscriptions') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Subscriptions</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($plans as $plan)
            @php $isCurrent = ($currentSub && $currentSub->plan_id === $plan->id); @endphp
            <div class="bg-white rounded-3xl border-2 {{ $isCurrent ? 'border-brand-600 ring-4 ring-brand-50' : 'border-slate-200' }} p-7 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-black text-slate-900">{{ $plan->name }}</h2>
                        @if($isCurrent)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                Current Active
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <span class="text-3xl font-black text-slate-900">£{{ number_format($plan->price, 0) }}</span>
                        <span class="text-xs text-slate-500">/ month</span>
                    </div>

                    <p class="text-xs text-slate-600 mb-6 leading-relaxed">{{ $plan->description }}</p>

                    <ul class="space-y-2.5 text-xs text-slate-700 divide-y divide-slate-100 pt-2 border-t border-slate-100 mb-6">
                        <li class="pt-2 flex items-center justify-between">
                            <span>Product Listings Limit:</span>
                            <strong class="font-bold">{{ $plan->product_limit }} Products</strong>
                        </li>
                        <li class="pt-2 flex items-center justify-between">
                            <span>Monthly Enquiries:</span>
                            <strong class="font-bold">{{ $plan->monthly_enquiry_limit }} Enquiries</strong>
                        </li>
                        <li class="pt-2 flex items-center justify-between">
                            <span>Verified Badge:</span>
                            <strong class="{{ $plan->featured_supplier_badge ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $plan->featured_supplier_badge ? 'Included' : 'Standard' }}
                            </strong>
                        </li>
                        <li class="pt-2 flex items-center justify-between">
                            <span>Priority RFQ Alerts:</span>
                            <strong class="{{ $plan->priority_rfq_access ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $plan->priority_rfq_access ? 'Yes (Instant SMS/Email)' : 'Standard' }}
                            </strong>
                        </li>
                        <li class="pt-2 flex items-center justify-between">
                            <span>Customs & Shipping Concierge:</span>
                            <strong class="{{ $plan->customs_logistics_discount ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $plan->customs_logistics_discount ? 'Included' : 'No' }}
                            </strong>
                        </li>
                    </ul>
                </div>

                <div>
                    @if($isCurrent)
                        <button disabled class="w-full py-3 bg-slate-100 text-slate-500 font-bold rounded-xl text-xs cursor-default">
                            Current Active Tier
                        </button>
                    @else
                        <form action="{{ route('supplier.subscriptions.subscribe', $plan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                Switch to {{ $plan->name }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
