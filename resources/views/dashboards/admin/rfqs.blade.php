@extends('layouts.admin')

@section('title', 'Manage RFQs & Tenders — Mart Admin')
@section('header_title', 'Marketplace RFQs & Quotations')
@section('header_subtitle', 'Oversee industrial procurement tenders, supplier bidding, and quotation awards')

@section('content')
<div class="space-y-6" x-data="{ statusModal: false, activeRfqId: '', activeRfqNumber: '', currentStatus: '' }">

    <!-- Filters & Actions Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            
            <!-- Status Filter Pills -->
            <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="{{ route('admin.rfqs') }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ !request('status') ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All RFQs
                </a>
                <a href="{{ route('admin.rfqs', ['status' => 'open', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'open' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Open Bidding
                </a>
                <a href="{{ route('admin.rfqs', ['status' => 'under_review', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'under_review' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Under Review
                </a>
                <a href="{{ route('admin.rfqs', ['status' => 'awarded', 'search' => request('search')]) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'awarded' ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Awarded PO
                </a>
            </div>

            <!-- Search -->
            <form action="{{ route('admin.rfqs') }}" method="GET" class="relative w-full sm:w-64">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search RFQ #, product..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </form>

        </div>
    </div>

    <!-- RFQs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">RFQ #</th>
                        <th class="p-4">Product Requirement</th>
                        <th class="p-4">Buyer Entity</th>
                        <th class="p-4">Requested Volume</th>
                        <th class="p-4">Supplier Quotes</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($rfqs as $rfq)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- RFQ Number -->
                            <td class="p-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('rfqs.show', $rfq->id) }}" class="text-brand-800 hover:underline">
                                    #{{ $rfq->rfq_number }}
                                </a>
                                <span class="text-[10px] text-slate-400 block font-sans">{{ $rfq->created_at->format('d M Y') }}</span>
                            </td>

                            <!-- Product Name & Category -->
                            <td class="p-4">
                                <span class="font-extrabold text-slate-900 text-xs block">{{ $rfq->product_name }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $rfq->category->name ?? 'General Machinery' }}</span>
                            </td>

                            <!-- Buyer -->
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $rfq->buyer->company->company_name ?? $rfq->buyer->name }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $rfq->destination_country ?? 'United Kingdom' }}</span>
                            </td>

                            <!-- Volume -->
                            <td class="p-4 font-bold text-slate-900">
                                {{ number_format($rfq->quantity) }} {{ $rfq->unit }}
                            </td>

                            <!-- Quotes Count -->
                            <td class="p-4 font-bold text-brand-700">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-brand-700 border border-blue-100">
                                    {{ $rfq->quotations_count }} Bids Submitted
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <button type="button" 
                                        @click="statusModal = true; 
                                                activeRfqId = '{{ $rfq->id }}'; 
                                                activeRfqNumber = '{{ $rfq->rfq_number }}'; 
                                                currentStatus = '{{ $rfq->status }}';"
                                        class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold {{ $rfq->status === 'open' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700' }} hover:bg-slate-200 transition">
                                    <span>{{ ucfirst(str_replace('_', ' ', $rfq->status)) }}</span>
                                    <i class="fa-solid fa-pen ml-1.5 text-[9px] text-slate-400"></i>
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('rfqs.compare', $rfq->id) }}" 
                                       title="View Quote Comparison"
                                       class="px-2.5 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs flex items-center space-x-1 transition">
                                        <i class="fa-solid fa-code-compare"></i>
                                        <span>Compare</span>
                                    </a>

                                    <form action="{{ route('admin.rfqs.destroy', $rfq->id) }}" method="POST" onsubmit="return confirm('Permanently delete RFQ #{{ $rfq->rfq_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete RFQ"
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
                                <i class="fa-solid fa-file-invoice-dollar text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No RFQs recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rfqs->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $rfqs->links() }}
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
                <h3 class="text-sm font-black text-slate-900">Update RFQ Status: <span class="font-mono text-brand-700" x-text="'#' + activeRfqNumber"></span></h3>
                <button @click="statusModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/rfqs') }}/' + activeRfqId + '/status'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tender Status</label>
                    <select name="status" x-model="currentStatus" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="open">Open (Accepting Supplier Bids)</option>
                        <option value="under_review">Under Review (Buyer Evaluating Quotes)</option>
                        <option value="awarded">Awarded (Converted to Purchase Order)</option>
                        <option value="closed">Closed / Expired</option>
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
