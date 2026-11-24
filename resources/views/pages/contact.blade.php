@extends('layouts.app')

@section('title', 'Contact Perlcon International — Global Procurement Support')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Contact Perlcon International</h1>
        <p class="text-slate-600 text-sm mt-2 max-w-2xl">
            Our procurement, logistics, and customs engineering teams are based across London, Felixstowe, Pune, and Shenzhen.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h2 class="text-lg font-black text-slate-900 mb-2">Send an Official Inquiry</h2>
            <p class="text-xs text-slate-500 mb-6">Our client procurement managers will respond within 4 business hours.</p>

            <form action="{{ route('contact') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Your Name</label>
                        <input type="text" required placeholder="Full Name" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Work Email</label>
                        <input type="email" required placeholder="you@company.co.uk" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Company / Organization</label>
                    <input type="text" placeholder="Company Name Ltd" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Message</label>
                    <textarea rows="4" required placeholder="Inquire about enterprise supplier onboarding, bulk machinery imports, or customs brokerage..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                    Submit Message
                </button>
            </form>
        </div>

        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4 text-xs">
                <h3 class="text-base font-extrabold text-slate-900">Perlcon Global Hubs</h3>
                <div class="space-y-3 divide-y divide-slate-100">
                    <div class="pt-2">
                        <strong class="text-slate-900 block font-bold">🇬🇧 London Corporate Headquarters</strong>
                        <span class="text-slate-500">25 Bank Street, Canary Wharf, London E14 5JP</span>
                        <span class="block text-slate-500 mt-0.5">Phone: +44 20 7946 0991</span>
                    </div>
                    <div class="pt-2">
                        <strong class="text-slate-900 block font-bold">🛡️ Customs Brokerage Office (Junaid & Co.)</strong>
                        <span class="text-slate-500">Dock Head House, Port of Felixstowe, IP11 3SY</span>
                        <span class="block text-slate-500 mt-0.5">Phone: +44 1394 670 120</span>
                    </div>
                    <div class="pt-2">
                        <strong class="text-slate-900 block font-bold">🇮🇳 India Manufacturing Desk</strong>
                        <span class="text-slate-500">Plot 42-B, MIDC Industrial Area, Pune 411026</span>
                    </div>
                    <div class="pt-2">
                        <strong class="text-slate-900 block font-bold">🇨🇳 China Export Logistics Desk</strong>
                        <span class="text-slate-500">Baoan High-Tech Industrial Park, Shenzhen 518101</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
