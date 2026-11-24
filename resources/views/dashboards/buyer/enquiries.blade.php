@extends('layouts.buyer')

@section('title', 'My Enquiries — Buyer Portal')
@section('header_title', 'Supplier Inquiries & Technical Questions')
@section('header_subtitle', 'Direct communications and commercial pre-procurement questions sent to factories')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        @if($enquiries->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-envelope-open-text text-3xl mb-3 text-slate-300 block"></i>
                <p class="text-sm font-semibold">No direct supplier inquiries sent yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block mt-3 px-5 py-2 bg-sky-600 text-white font-bold rounded-xl text-xs">Browse Products to Inquire</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($enquiries as $enquiry)
                    <div class="p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 hover:bg-slate-50/70 transition">
                        
                        <div class="space-y-1.5 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono text-xs font-bold text-sky-800">#{{ $enquiry->enquiry_number ?? 'ENQ-' . $enquiry->id }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ ucfirst($enquiry->status ?? 'pending') }}
                                </span>
                                <span class="text-xs text-slate-400">Sent {{ $enquiry->created_at->format('d M Y') }}</span>
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $enquiry->subject ?? 'Technical Inquiry' }}
                            </h3>

                            <p class="text-xs text-slate-600 font-medium">
                                To: <strong>{{ $enquiry->supplier->company->company_name ?? $enquiry->supplier->name }}</strong>
                                @if($enquiry->product)
                                    • Product: <strong class="text-slate-900">{{ $enquiry->product->name }}</strong>
                                @endif
                            </p>

                            <p class="text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-100 max-w-2xl">
                                "{{ Str::limit($enquiry->message, 180) }}"
                            </p>
                        </div>

                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="{{ route('messages.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-sky-50 text-slate-700 hover:text-sky-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                                <i class="fa-solid fa-comments"></i>
                                <span>Open Chat</span>
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
