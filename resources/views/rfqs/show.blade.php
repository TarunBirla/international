@extends(auth()->check() ? auth()->user()->getDashboardLayout() : 'layouts.app')

@section('title', 'RFQ: ' . $rfq->product_name . ' — Mart')
@section('header_title', 'RFQ Tender: #' . $rfq->rfq_number)
@section('header_subtitle', $rfq->product_name . ' • Requested Volume: ' . number_format($rfq->quantity) . ' ' . $rfq->unit)

@section('content')
@if(!auth()->check())
<div class="bg-slate-50 border-b border-slate-200 py-6 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('rfqs.index') }}" class="hover:text-brand-700">RFQs</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold font-mono">{{ $rfq->rfq_number }}</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase {{ $rfq->status === 'open' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                        Status: {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                    </span>
                    <span class="text-xs text-slate-500">Posted {{ $rfq->created_at->format('d M Y') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $rfq->product_name }}</h1>
            </div>
        </div>
    </div>
</div>
@else
<div class="flex items-center justify-between mb-6">
    <a href="{{ auth()->user()->isBuyer() ? route('buyer.rfqs') : (auth()->user()->isAdmin() ? route('admin.rfqs') : route('rfqs.index')) }}" 
       class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to RFQs List</span>
    </a>

    @if(Auth::id() === $rfq->buyer_id || Auth::user()->isAdmin())
        <div class="flex items-center space-x-2">
            <a href="{{ route('rfqs.edit', $rfq->id) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-pen"></i>
                <span>Edit RFQ</span>
            </a>
            @if($rfq->quotations->isNotEmpty())
                <a href="{{ route('rfqs.compare', $rfq->id) }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-extrabold rounded-xl text-xs shadow-sm transition flex items-center space-x-2">
                    <i class="fa-solid fa-code-compare"></i>
                    <span>Compare {{ count($rfq->quotations) }} Quotes</span>
                </a>
            @endif
        </div>
    @endif
</div>
@endif

<div class="{{ auth()->check() ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10' }}">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: RFQ Specs & Submitted Quotations -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Details Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm space-y-6">
                <h2 class="text-lg font-black text-slate-900">Procurement Requirements & Scope</h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-slate-500 block mb-0.5">Quantity</span>
                        <strong class="text-slate-900 text-sm font-black">{{ number_format($rfq->quantity) }} {{ $rfq->unit }}</strong>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-slate-500 block mb-0.5">Target Budget</span>
                        <strong class="text-slate-900 text-sm font-black">
                            {{ $rfq->target_price ? '£' . number_format($rfq->target_price, 2) : 'Open to Quote' }}
                        </strong>
                    </div>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-slate-500 block mb-0.5">Destination Port</span>
                        <strong class="text-slate-900 text-sm font-black">{{ $rfq->destination_country }} ({{ $rfq->destination_port_city ?? 'UK' }})</strong>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Technical Specifications</h3>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-800 whitespace-pre-line font-mono">
                        {{ $rfq->technical_specifications }}
                    </div>
                </div>

                @if($rfq->additional_requirements)
                    <div>
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Commercial & Inspection Terms</h3>
                        <p class="text-xs text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            {{ $rfq->additional_requirements }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Existing Quotations (Visible to Buyer/Admin) -->
            @if(Auth::check() && (Auth::id() === $rfq->buyer_id || Auth::user()->isAdmin()))
                <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-slate-900">Quotations Received ({{ count($rfq->quotations) }})</h2>
                        @if($rfq->quotations->isNotEmpty())
                            <a href="{{ route('rfqs.compare', $rfq->id) }}" class="text-xs font-bold text-brand-700 hover:underline">
                                Open Side-by-Side Comparator &rarr;
                            </a>
                        @endif
                    </div>

                    @if($rfq->quotations->isEmpty())
                        <p class="text-xs text-slate-500 py-4 text-center">No quotations submitted yet. Registered suppliers from India, China and Europe have been notified.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($rfq->quotations as $q)
                                <div class="p-5 rounded-2xl border {{ $q->status === 'accepted' ? 'border-emerald-300 bg-emerald-50/40' : 'border-slate-200 bg-white' }} flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="font-mono text-xs font-bold text-slate-800">{{ $q->quotation_number }}</span>
                                            <span class="text-xs font-bold {{ $q->status === 'accepted' ? 'text-emerald-700' : 'text-slate-600' }}">
                                                ({{ ucfirst($q->status) }})
                                            </span>
                                        </div>
                                        <div class="text-sm font-bold text-slate-900">
                                            {{ $q->supplier->company->company_name ?? $q->supplier->name }}
                                            <span class="text-xs font-normal text-slate-500">({{ $q->supplier->country }})</span>
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Unit: £{{ number_format($q->unit_price, 2) }} • Shipping: £{{ number_format($q->shipping_cost, 2) }} ({{ $q->shipping_method }}) • Delivery: {{ $q->estimated_delivery_days }} days
                                        </div>
                                    </div>

                                    <div class="text-right flex flex-col sm:items-end w-full sm:w-auto">
                                        <span class="text-xs text-slate-500">Total Landed Cost:</span>
                                        <span class="text-lg font-black text-slate-900">£{{ number_format($q->total_landed_cost, 2) }}</span>
                                        
                                        @if($q->status !== 'accepted' && $rfq->status !== 'awarded')
                                            <form action="{{ route('quotations.accept', $q->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Accept this quotation and generate purchase order?')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                                    Accept & Convert to Order
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right Col: Supplier Quote Form OR Buyer Overview -->
        <div class="space-y-6">
            @if(Auth::check() && Auth::user()->isSupplier())
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-black text-slate-900 mb-2">Submit Binding Quotation</h3>
                    <p class="text-xs text-slate-500 mb-4">Provide your CIF or Landed quotation for {{ $rfq->quantity }} {{ $rfq->unit }}.</p>

                    <form action="{{ route('rfqs.submit-quotation', $rfq->id) }}" method="POST" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Unit Price (£ / {{ $rfq->unit }}) *</label>
                            <input type="number" step="0.01" name="unit_price" required placeholder="e.g. 32.50" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Estimated Shipping Cost (£) *</label>
                            <input type="number" step="0.01" name="shipping_cost" required placeholder="e.g. 850.00" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Shipping Method *</label>
                            <select name="shipping_method" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                <option value="Consolidated Sea Freight (LCL)">Consolidated Sea Freight (LCL)</option>
                                <option value="Full Container Load (FCL)">Full Container Load (FCL)</option>
                                <option value="Air Freight Express">Air Freight Express</option>
                                <option value="DHL / Courier Express">DHL / Courier Express</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Delivery Days *</label>
                                <input type="number" name="estimated_delivery_days" value="20" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Quote Validity (Days)</label>
                                <input type="number" name="validity_days" value="30" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Payment Terms</label>
                            <input type="text" name="payment_terms" value="30% Advance, 70% against Bill of Lading" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Notes / Packaging / Certification Details</label>
                            <textarea name="notes" rows="2" placeholder="e.g. Seaworthy pallet packaging, includes 100% NDT inspection..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md shadow-brand-700/20">
                            Submit Quotation to Buyer
                        </button>
                    </form>
                </div>
            @endif

            <!-- Buyer Summary Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Buyer Profile</span>
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-sm">
                        {{ strtoupper(substr($rfq->buyer->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong class="text-sm text-slate-900 block">{{ $rfq->buyer->company->company_name ?? $rfq->buyer->name }}</strong>
                        <span class="text-xs text-slate-500">{{ $rfq->buyer->city ?? 'London' }}, {{ $rfq->buyer->country ?? 'UK' }}</span>
                    </div>
                </div>
                <div class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <i class="fa-solid fa-shield-halved text-emerald-600 mr-1.5"></i> Protected by Mart Trade Assurance Escrow.
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
