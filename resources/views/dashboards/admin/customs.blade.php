@extends('layouts.admin')

@section('title', 'Customs Declarations & Clearance — Mart Admin')
@section('header_title', 'UK Customs Clearance Oversight')
@section('header_subtitle', 'Review C88 single administrative documents, duty deferment, and Junaid & Co. filings')

@section('content')
<div class="space-y-6" x-data="{ customsModal: false, activeCustomsId: '', activeC88: '', currentStatus: '', currentNotes: '' }">

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h2 class="text-sm font-black text-slate-900">Junaid & Co. Customs Declarations</h2>
                <p class="text-xs text-slate-500">C88 documentation, tariff duty assessments, and HMRC compliance</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">C88 Filing Ref</th>
                        <th class="p-4">Importing Buyer</th>
                        <th class="p-4">Customs Value</th>
                        <th class="p-4">UK Duty (£)</th>
                        <th class="p-4">Import VAT (£)</th>
                        <th class="p-4">Clearance Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($customs as $entry)
                        <tr class="hover:bg-slate-50/70 transition">
                            
                            <!-- C88 Ref -->
                            <td class="p-4 font-mono font-bold text-slate-900">
                                <span class="text-brand-800">{{ $entry->c88_reference }}</span>
                                <span class="text-[10px] text-slate-400 block font-sans">{{ $entry->port_of_entry }}</span>
                            </td>

                            <!-- Buyer -->
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $entry->order->buyer->company->company_name ?? 'Buyer' }}</span>
                                <span class="text-[10px] text-slate-400">Order: #{{ $entry->order->order_number ?? 'N/A' }}</span>
                            </td>

                            <!-- Customs Value -->
                            <td class="p-4 font-bold text-slate-900">
                                £{{ number_format($entry->customs_value, 2) }}
                            </td>

                            <!-- UK Duty -->
                            <td class="p-4 font-bold text-amber-700">
                                £{{ number_format($entry->duty_amount, 2) }}
                            </td>

                            <!-- Import VAT -->
                            <td class="p-4 font-bold text-indigo-700">
                                £{{ number_format($entry->vat_amount, 2) }}
                            </td>

                            <!-- Clearance Status -->
                            <td class="p-4">
                                @php
                                    $customsClasses = [
                                        'cleared' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'inspection' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'docs_submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'pending' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ][$entry->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $customsClasses }}">
                                    {{ ucwords(str_replace('_', ' ', $entry->status)) }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="p-4 text-right">
                                <button type="button" 
                                        @click="customsModal = true; 
                                                activeCustomsId = '{{ $entry->id }}'; 
                                                activeC88 = '{{ $entry->c88_reference }}'; 
                                                currentStatus = '{{ $entry->status }}'; 
                                                currentNotes = '{{ addslashes($entry->notes ?? '') }}';"
                                        class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs inline-flex items-center space-x-1 transition border border-amber-200">
                                    <i class="fa-solid fa-stamp"></i>
                                    <span>Audit Clearance</span>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-stamp text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No customs filings submitted.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customs->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $customs->links() }}
            </div>
        @endif
    </div>

    <!-- Update Customs Clearance Modal -->
    <div x-show="customsModal" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200" 
             @click.away="customsModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black text-slate-900">Customs Clearance Status: <span class="font-mono text-brand-700" x-text="activeC88"></span></h3>
                <button @click="customsModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/customs') }}/' + activeCustomsId + '/update'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Clearance Stage</label>
                    <select name="status" x-model="currentStatus" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="pending">Pending Document Submission</option>
                        <option value="docs_submitted">C88 Documents Submitted to Border Force</option>
                        <option value="inspection">Physical / X-Ray Inspection</option>
                        <option value="duty_assessed">Duty & Import VAT Assessed</option>
                        <option value="cleared">Cleared (Green Channel Release)</option>
                        <option value="rejected">Rejected / Additional Evidence Required</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Clearance Officer / Broker Notes</label>
                    <textarea name="notes" x-model="currentNotes" rows="3" placeholder="e.g. Cleared under Postponed VAT Accounting (PVA); released for haulage..." 
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="customsModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Save Status
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
