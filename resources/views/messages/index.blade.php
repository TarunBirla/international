@extends('layouts.app')

@section('title', 'Messages & Enquiries — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Messages & Trade Communications</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-1">Direct communication channel between international buyers and manufacturers.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[550px]">
        
        <!-- Left Contacts List (4 Cols) -->
        <div class="md:col-span-4 border-r border-slate-200 flex flex-col">
            <div class="p-4 border-b border-slate-100">
                <h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Conversations</h2>
            </div>
            
            <div class="divide-y divide-slate-100 flex-1 overflow-y-auto max-h-[500px]">
                @if($contacts->isEmpty())
                    <p class="text-xs text-slate-400 p-6 text-center">No active message threads yet. Sending enquiries or quotations starts a conversation.</p>
                @else
                    @foreach($contacts as $contact)
                        <a href="{{ route('messages.index', ['user_id' => $contact->id]) }}" class="block p-4 hover:bg-slate-50 transition {{ ($activeContact && $activeContact->id === $contact->id) ? 'bg-brand-50/60 border-l-4 border-brand-700' : '' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline mb-0.5">
                                        <strong class="text-xs font-bold text-slate-900 truncate">{{ $contact->name }}</strong>
                                        <span class="text-[10px] text-slate-400 capitalize">{{ $contact->role }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $contact->company->company_name ?? $contact->country }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Chat Window (8 Cols) -->
        <div class="md:col-span-8 flex flex-col bg-slate-50/50">
            @if($activeContact)
                <!-- Chat Header -->
                <div class="p-4 bg-white border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-brand-700 text-white font-bold flex items-center justify-center text-xs">
                            {{ strtoupper(substr($activeContact->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong class="text-sm font-extrabold text-slate-900 block">{{ $activeContact->name }}</strong>
                            <span class="text-xs text-slate-500">{{ $activeContact->company->company_name ?? 'Exporter' }} ({{ $activeContact->country }})</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Online
                    </span>
                </div>

                <!-- Messages Bubbles Body -->
                <div class="flex-1 p-6 overflow-y-auto space-y-4 max-h-[420px]">
                    @foreach($messages as $msg)
                        @php $isMe = ($msg->sender_id === Auth::id()); @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-md {{ $isMe ? 'bg-brand-700 text-white rounded-2xl rounded-tr-none' : 'bg-white text-slate-800 rounded-2xl rounded-tl-none border border-slate-200 shadow-sm' }} p-3.5 text-xs">
                                <p class="leading-relaxed">{{ $msg->message }}</p>
                                <span class="text-[9px] {{ $isMe ? 'text-blue-200' : 'text-slate-400' }} block text-right mt-1.5 font-mono">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if($isMe && $msg->is_read) &check;&check; @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Message Reply Box Form -->
                <div class="p-4 bg-white border-t border-slate-200">
                    <form action="{{ route('messages.send') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                        <input type="text" name="message" required placeholder="Type your trade message, inquiry, or question..." class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <button type="submit" class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Send</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400">
                    <i class="fa-solid fa-comments text-4xl mb-3"></i>
                    <p class="text-xs">Select a contact from the list on the left to start messaging.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
