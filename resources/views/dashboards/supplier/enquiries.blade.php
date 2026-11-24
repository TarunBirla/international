@extends('layouts.supplier')

@section('title', 'Buyer Inquiries — Supplier Portal')
@section('header_title', 'Buyer Inquiries & Leads')
@section('header_subtitle', 'Review technical questions from international procurement buyers and reply via chat')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($enquiries->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-inbox text-3xl mb-3 text-slate-300 block"></i>
                <p class="text-sm font-semibold">No buyer inquiries received yet.</p>
                <p class="text-xs text-slate-500 mt-1">Make sure your product specifications and plant capabilities are up-to-date to attract more buyers.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($enquiries as $enq)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-emerald-800">#{{ $enq->enquiry_number ?? 'ENQ-' . $enq->id }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ ucfirst($enq->status ?? 'pending') }}
                                </span>
                                <span class="text-xs text-slate-400">Received {{ $enq->created_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $enq->subject ?? 'Product Inquiry' }}
                            </h3>

                            <p class="text-xs text-slate-600 font-medium">
                                From: <strong class="text-slate-900">{{ $enq->name }}</strong> ({{ $enq->email }})
                                @if($enq->product)
                                    • Product: <strong class="text-slate-900">{{ $enq->product->name }}</strong>
                                @endif
                            </p>

                            <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 max-w-2xl">
                                "{{ $enq->message }}"
                            </p>
                        </div>

                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('messages.index') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                                <i class="fa-solid fa-reply"></i>
                                <span>Reply via Messaging</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($enquiries->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $enquiries->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
