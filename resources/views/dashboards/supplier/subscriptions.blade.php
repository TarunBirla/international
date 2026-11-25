@extends('layouts.supplier')

@section('title', 'Supplier Subscription Plans — Mart')
@section('header_title', 'Exporter Membership Tiers')
@section('header_subtitle', 'Upgrade your manufacturer tier to unlock higher product limits, priority RFQs, and featured placement')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($plans as $plan)
            @php $isCurrent = ($currentSub && $currentSub->plan_id === $plan->id); @endphp
            <div class="bg-white rounded-3xl border-2 {{ $isCurrent ? 'border-emerald-600 ring-4 ring-emerald-50' : 'border-slate-200' }} p-7 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-black text-slate-900">{{ $plan->name }}</h2>
                        @if($isCurrent)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Current Active
                            </span>
                        @endif
                    </div>
                    <div class="text-3xl font-black text-slate-900 mb-4">
                        £{{ number_format($plan->price, 0) }} <span class="text-xs font-semibold text-slate-500">/ {{ $plan->billing_period }}</span>
                    </div>
                    <p class="text-xs text-slate-600 mb-6 leading-relaxed">{{ $plan->description }}</p>

                    <div class="space-y-2.5 text-xs text-slate-700 font-medium mb-8">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i>
                            <span>Up to <strong>{{ $plan->max_products }}</strong> Product Listings</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i>
                            <span>RFQ Bids: <strong>{{ $plan->rfq_responses_limit }}</strong> / month</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid {{ $plan->verified_badge ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-slate-300' }} text-xs"></i>
                            <span>Verified Manufacturer Badge</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid {{ $plan->featured_placement ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-slate-300' }} text-xs"></i>
                            <span>Featured Homepage Showcase</span>
                        </div>
                    </div>
                </div>

                @if(!$isCurrent)
                    <form action="{{ route('supplier.subscriptions.subscribe', $plan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                            Upgrade to {{ $plan->name }}
                        </button>
                    </form>
                @else
                    <button disabled class="w-full py-2.5 bg-slate-100 text-slate-400 font-bold rounded-xl text-xs cursor-not-allowed">
                        Active Membership
                    </button>
                @endif
            </div>
        @endforeach
    </div>

</div>
@endsection
