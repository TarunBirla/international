@extends('layouts.admin')

@section('title', 'Manage Buyers — Perlcon Admin')
@section('header_title', 'Registered Buyers & EPC Contractors')
@section('header_subtitle', 'Oversee industrial procurement managers, RFQ history, and corporate purchasing accounts')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            
            <!-- Search -->
            <form action="{{ route('admin.buyers') }}" method="GET" class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search buyer name, company, email..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </form>

            <a href="{{ route('admin.buyers.create') }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5 flex-shrink-0">
                <i class="fa-solid fa-plus"></i>
                <span>+ Add Buyer</span>
            </a>

        </div>
    </div>

    <!-- Buyers Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">Buyer Name & Contact</th>
                        <th class="p-4">Enterprise Company</th>
                        <th class="p-4">Location</th>
                        <th class="p-4">RFQs Posted</th>
                        <th class="p-4">Orders Placed</th>
                        <th class="p-4">Account Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($buyers as $buyer)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- Buyer Name -->
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-700 font-black flex items-center justify-center text-xs border border-sky-100 flex-shrink-0">
                                        {{ strtoupper(substr($buyer->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-extrabold text-slate-900 text-sm block">{{ $buyer->name }}</span>
                                        <span class="text-slate-500 text-[11px] block">{{ $buyer->email }}</span>
                                        @if($buyer->phone)
                                            <span class="text-slate-400 text-[10px]">{{ $buyer->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Company -->
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $buyer->company->company_name ?? 'Individual Buyer' }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $buyer->company->business_type ?? 'Procurement' }}</span>
                            </td>

                            <!-- Location -->
                            <td class="p-4">
                                <span class="font-semibold text-slate-800 block">{{ $buyer->country ?? 'United Kingdom' }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $buyer->city ?? 'London' }}</span>
                            </td>

                            <!-- RFQs Posted -->
                            <td class="p-4 font-bold text-brand-700">
                                {{ $buyer->rfqs->count() }} Tenders
                            </td>

                            <!-- Orders Placed -->
                            <td class="p-4 font-bold text-slate-900">
                                {{ $buyer->orders->count() }} Orders
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('admin.buyers.edit', $buyer->id) }}" 
                                       title="Edit Buyer"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-700 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.buyers.destroy', $buyer->id) }}" method="POST" onsubmit="return confirm('Permanently delete buyer {{ $buyer->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Buyer"
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
                                <i class="fa-solid fa-briefcase text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No buyers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($buyers->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $buyers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
